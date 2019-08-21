<?php

namespace App\Console\Commands;

use App\Part;
use App\PartCategory;
use App\PartImageUrl;
use Illuminate\Console\Command;
use App\Gateways\RebrickableApiLego;

class UpdatePartImageUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lego:part-image-url 
                            {--category= : The only part_category_id to update}
                            {--start= : Where in the PartCategory collection to begin processing}
                            {--end= : Where in the PartCategory collection to stop processing}';

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
     * RebrickableApiLego instance
     *
     * @var null
     */
    protected $api = null;

    /**
     * Part Category start option
     *
     * @var int
     */
    protected $start = 0;

    /**
     * Part Category end option
     *
     * @var int
     */
    protected $end = 0;

    /**
     * Process start time
     *
     * @var float
     */
    protected $processStart = 0;

    /**
     * Process end time
     *
     * @var float
     */
    protected $processEnd = 0;

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

        $this->start = ($this->option('start')) ? ((int) $this->option('start')) - 1 : 0;
        $this->end = ($this->option('end')) ? ((int) $this->option('end')) - 1 : 0;

        $this->getCurrentPartImages();
        $this->getPartCategories();
        $this->setupApiInstance();
        $this->processPartCategories();
        $this->displayMissingParts();

        $this->info('');
        $this->goodbye();
    }

    protected function start()
    {
        return $this->confirm('>> This command can take a VERY long time to execute <<');
    }

    protected function getCurrentPartImages()
    {
        $this->partImageUrls = PartImageUrl::all();
    }

    protected function getPartCategories()
    {
        if ($this->option('category')) {
            $this->partCategories = PartCategory::findOrFail($this->option('category'));
            return;
        }

        $this->partCategories = PartCategory::all();

        $this->calculatePartCategorySplice();

        if ($this->start > 0 || $this->end > 0) {
            $this->partCategories = $this->partCategories->splice($this->start, $this->end);
        }
    }

    protected function getAllParts()
    {
        $this->allParts = Part::all()->pluck('part_num');
    }

    protected function setupApiInstance()
    {
        $this->api = new RebrickableApiLego;
    }

    protected function processPartCategories()
    {
        if (! $this->partCategories->count()) {
            $this->warn('** No Part Categories Found In The Database **');
            $this->goodbye();
        }

        $this->getAllParts();

        if ($this->option('category')) {
            return $this->getRebrickableParts($this->option('category'));
        }

        $categoryProgress = $this->output->createProgressBar($this->partCategories->count());

        $categoryProgress->start();

        foreach ($this->partCategories as $category) {
            $this->getRebrickableParts($category->id);

            $this->api->resetRequest();

            $categoryProgress->advance();
        }

        $categoryProgress->finish();
    }

    protected function displayMissingParts()
    {
        if (count($this->missingParts)) {
            $this->info('');
            $this->info('');
            $missing = implode(', ', $this->missingParts);
            $this->warn('The following parts are missing from the database: '.$missing);
        }
    }

    protected function goodbye()
    {
        $processLabel = $this->calculateProcessTime();

        $this->info('');
        $this->info('>>>> Process completed in '.$processLabel.'! <<<<');
        die();
    }

    protected function getRebrickableParts($category)
    {
        $this->api->setUrlParam('part_cat_id', $category);
        $parts = $this->api->getAllParts();

        if ($parts->count()) {
            $this->processParts($parts);
        }

        return true;
    }

    protected function calculatePartCategorySplice()
    {
        $partCategoryCount = $this->partCategories->count();

        if ($this->start > $partCategoryCount) {
            $this->start = 0;
        }

        if (($this->start > $this->end) || (($this->end - $this->start) > $partCategoryCount)) {
            $this->end = $partCategoryCount - $this->start;
        } elseif ($this->start > 0 && $this->end > 0 && $this->end < $partCategoryCount) {
            $this->end = ($this->end - $this->start) + 1;
        } elseif ($this->start == 0 && $this->end > 0) {
            $this->end = $this->end + 1;
        }
    }

    protected function processParts($parts)
    {
        $images = $this->partImageUrls;
        $allParts = $this->allParts;

        $partsProgress = $this->output->createProgressBar(count($parts));

        $partsProgress->start();

        foreach ($parts as $part) {
            if (! $allParts->contains($part['part_num'])) {
                $this->missingParts[] = $part['part_num'];
                continue;
            }

            if (! $images->contains(
                function ($value, $key) use ($part) {
                    return $value->part_num == $part['part_num'] && $value->image_url == $part['part_img_url'];
                }
            )
                && ! is_null($part['part_img_url'])
            ) {
                $newImageUrl = PartImageUrl::create([
                    'part_num' => $part['part_num'],
                    'image_url' => $part['part_img_url']
                ]);
                $this->partImageUrls->push($newImageUrl);
            }

            $partsProgress->advance();
        }

        $partsProgress->finish();

        return true;
    }

    protected function calculateProcessTime()
    {
        $this->processEnd = microtime(true);

        $processTime = $this->processEnd - $this->processStart;
        $processLabel = ' seconds';
        if ($processTime > 90) {
            $processTime = $processTime / 60;
            $processLabel = ' minutes';
        }

        return round($processTime, 2).$processLabel;
    }
}
