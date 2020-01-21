<?php

namespace App\Console\Commands;

use App\Part;
use App\PartCategory;
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
    protected $signature = 'lego:part-image-url';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the image url for all parts in each PartCategory. Data is retrieved from Rebrickable';

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
        $this->setupApiLegoInstance();
        $this->truncateTable(new PartImageUrl(), true);
        $this->getPartCategories();
        $this->processPartCategories();
        $this->displayMissingParts();
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
     * Retrieve all part_categories from the database
     *
     * @return void
     */
    protected function getPartCategories()
    {
        $this->updateStatus('Getting all part categories from database...');

        $this->partCategories = PartCategory::all();
    }

    /**
     * processPartCategories
     *
     * @return void
     */
    protected function processPartCategories()
    {
        if (! $this->partCategories->count()) {
            $this->warn('** No Part Categories Found In The Database **');
            $this->goodbye();
        }

        $this->updateStatus('Processing each part category...');

        $this->processed = 0;

        $this->getAllParts();

        $categoryProgress = $this->output->createProgressBar($this->partCategories->count());
        $categoryProgress->start();

        foreach ($this->partCategories as $category) {
            $this->getRebrickableParts($category);

            $this->api->resetRequest();

            $categoryProgress->advance();
        }

        $categoryProgress->finish();
    }

    /**
     * Retrieve all parts from the database
     *
     * @return void
     */
    protected function getAllParts()
    {
        $this->updateStatus('Getting all parts from database...');

        $this->allParts = Part::all()->pluck('part_num');
    }

    /**
     * Retrieve all parts from Rebrickable for a given category
     *
     * @param PartCategory $category
     * @return bool
     */
    protected function getRebrickableParts($category)
    {
        $this->updateStatus('Getting Rebrickable parts for category: '.$category->name.'...');

        $this->api->setUrlParam('part_cat_id', $category->id);
        $parts = $this->api->getAllParts();

        if ($parts->count()) {
            $this->processParts($parts);
        }

        return true;
    }

    protected function processParts($parts)
    {
        $this->updateStatus('Adding part image urls...');

        $this->processed = $this->processed + count($parts);

        $images = $this->partImageUrls;
        $allParts = $this->allParts;

        $partsProgress = $this->output->createProgressBar(count($parts));
        $partsProgress->start();

        foreach ($parts as $part) {
            if (! $allParts->contains($part['part_num'])) {
                $this->missingParts[] = $part['part_num'];
                continue;
            }

            if (! is_null($part['part_img_url'])) {
                $newImageUrl = PartImageUrl::create([
                    'part_num' => $part['part_num'],
                    'image_url' => $part['part_img_url']
                ]);
            }

            $partsProgress->advance();
        }

        $partsProgress->finish();

        return true;
    }

    /**
     * Display any missing parts discovered during import
     *
     * @return void
     */
    protected function displayMissingParts()
    {
        if (count($this->missingParts)) {
            $this->info('');
            $this->info('');
            $missing = implode(', ', $this->missingParts);
            $this->warn('The following parts are missing from the database: '.$missing);
        }
    }
}
