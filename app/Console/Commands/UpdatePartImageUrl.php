<?php

namespace App\Console\Commands;

use App\Part;
use App\PartImageUrl;
use Illuminate\Console\Command;

class UpdatePartImageUrl extends Command
{
    use CommandHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lego:part-image-url
                            {--categories= : The only category ids to update}
                            {--exclude-categories= : Category ids to exclude from update}
                            {--no-truncate : Do not truncate the part_image_urls table}
                            {--force-truncate : Force truncate the part_image_urls table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the image url for all parts in each PartCategory. Data is retrieved from Rebrickable';

    /**
     * Part image urls collection from existing table
     *
     * @var null
     */
    protected $partImageUrls = null;

    /**
     * Part categories from database
     *
     * @var null
     */
    protected $partCategories = null;

    /**
     * Parts from database
     *
     * @var null
     */
    protected $allParts = null;

    /**
     * Missing parts from database
     *
     * @var array
     */
    protected $missingParts = [];

    /**
     * Whether or not to truncate the table
     *
     * @var boolean
     */
    protected $truncate = true;

    /**
     * Category Ids that should only be processed
     *
     * @var array
     */
    protected $onlyCategories = [];

    /**
     * Category Ids that should not be processed
     *
     * @var array
     */
    protected $excludeCategories = [];

    /**
     * Number of parts updated during process
     *
     * @var int
     */
    protected $updatedParts = 0;

    /**
     * Number of parts add during process
     *
     * @var int
     */
    protected $addedParts = 0;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (! $this->start()) {
            return false;
        }

        $this->processStart = microtime(true);

        $this->info('');
        $this->checkOptions();
        $this->setupApiLegoInstance();
        $this->getAllParts();
        if ($this->truncate) {
            $this->truncateTable(new PartImageUrl());
        }
        $this->processPartChunks();
        $this->displayPartsProcessed();
        $this->goodbye();
    }

    /**
     * Display command details and request confirmation to continue
     *
     * @return bool
     */
    protected function start()
    {
        $this->info('>> It is advisable to make sure parts are up to date in the DB before running this command <<');
        $this->info('>> This command will add part image urls for all parts in the database, if applicable <<');
        $this->info('>> This command will also overwrite all images listed in the part-image-urls table <<');
        return $this->confirm('This command can take a VERY long time to execute. Continue?');
    }

    /**
     * Check for options provided by the user
     *
     * @return void
     */
    protected function checkOptions()
    {
        if ($this->option('categories')) {
            $this->onlyCategories = explode(',', $this->option('categories'));
            $this->truncate = false;
        }

        if ($this->option('exclude-categories')) {
            $this->excludeCategories = explode(',', $this->option('exclude-categories'));
            $this->truncate = false;
        }

        if ($this->option('no-truncate')) {
            $this->truncate = false;
        }

        if ($this->option('force-truncate')) {
            $this->truncate = true;
        }
    }

    /**
     * Retrieve parts from the database
     *
     * @return void
     */
    protected function getAllParts()
    {
        $this->updateStatus('Getting parts from database...');

        $allParts = Part::select('part_num')->without('category');

        if (count($this->onlyCategories)) {
            $allParts->whereIn('part_category_id', $this->onlyCategories);
        }

        if (count($this->excludeCategories)) {
            $allParts->whereNotIn('part_category_id', $this->excludeCategories);
        }

        $this->allParts = $allParts->cursor();
    }

    /**
     * Process a collection of parts
     *
     * @return void
     */
    protected function processPartChunks()
    {
        $partList = [];
        $this->allParts->chunk(500)->each(function ($parts) use (&$partList) {
            $partList[] = $parts->implode('part_num', ',');
        });

        $partListCount = count($partList);

        foreach ($partList as $i => $parts) {
            $this->getRebrickableParts($parts, ($i + 1), $partListCount);

            $this->api->resetRequest();
        }
    }

    /**
     * Retrieve all parts from Rebrickable for a given part list
     *
     * @param string $partList
     * @param int $currentChunk
     * @param int $totalChunks
     * @return bool
     */
    protected function getRebrickableParts($partList, $currentChunk, $totalChunks)
    {
        $this->info('');
        $this->updateStatus("Getting Rebrickable parts for chunk $currentChunk of $totalChunks");

        $this->api->setUrlParam('part_nums', $partList);
        $parts = $this->api->getAllParts();

        if (! $parts instanceof \Illuminate\Support\Collection) {
            if (is_array($parts)) {
                $this->displayError('A problem was encountered! Rebrickable response: '.$parts['status'].' - '.$parts['detail']);
            }
            $this->displayError('A unknown problem was encountered! Rebrickable response: {'.$parts.'}');
        }

        if ($parts->count()) {
            $this->processParts($parts);
        }

        return true;
    }

    /**
     * Process a given sets of parts from Rebrickable
     *
     * @param Collection $parts
     * @return bool
     */
    protected function processParts($parts)
    {
        $this->info('--------> Adding part image urls...');

        $currentImages = PartImageUrl::whereIn('part_num', $parts->pluck('part_num'))->get();

        $partsProgress = $this->output->createProgressBar(count($parts));
        $partsProgress->start();

        $sqlList = [];

        foreach ($parts as $part) {
            if (! is_null($part['part_img_url']) && (! $currentImages->where('part_num', $part['part_num'])->where('image_url', $part['part_img_url'])->count())) {
                $this->processed++;

                $image = $currentImages->where('part_num', $part['part_num'])->first();

                if ($image && $image->image_url != $part['part_img_url']) {
                    $this->updatedParts++;
                    $image->update(['image_url' => $part['part_img_url']]);
                } else {
                    $this->addedParts++;
                    $sqlList[] = [
                        'part_num' => $part['part_num'],
                        'image_url' => $part['part_img_url']
                    ];
                }
            }

            $partsProgress->advance();
        }

        if (count($sqlList)) {
            PartImageUrl::insert($sqlList);
        }

        $partsProgress->finish();

        return true;
    }

    /**
     * Display any missing parts discovered during import
     *
     * @return void
     */
    protected function displayPartsProcessed()
    {
        $this->info('');
        $this->info('');
        $this->warn(
            '>>>> There '
            .(($this->updatedParts == 1) ? 'was 1 part' : 'were '.$this->updatedParts.' parts').
            ' updated and '
            .(($this->addedParts == 1) ? '1 part' : $this->addedParts.' parts').
            ' added during the process <<<<'
        );
    }
}
