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
                            {--category=}
                            {--start=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the image url for all parts. Data is retrieved from Rebrickable';

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

        $this->getCurrentPartImages();
        $this->getPartCategories();
        $this->getParts();
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

    protected function goodbye()
    {
        $this->info('');
        $this->info('>>>> Process Completed! <<<<');
        die();
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

    protected function getCurrentPartImages()
    {
        $this->partImageUrls = PartImageUrl::all();
    }

    protected function getPartCategories()
    {
        if ($this->option('category')) {
            return $this->partCategories = PartCategory::findOrFail($this->option('category'));
        }

        $this->partCategories = PartCategory::all();

        if ($this->option('start')) {
            $start = (int) $this->option('start');
            $this->partCategories = $this->partCategories->splice(($start - 1));
        }
    }

    protected function getParts()
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

        if ($this->option('category')) {
            $this->api->setUrlParam('part_cat_id', $this->option('category'));
            $parts = $this->api->getAllParts();

            if ($parts->count()) {
                $this->processParts($parts);
            }

            return;
        }

        $categoryProgress = $this->output->createProgressBar($this->partCategories->count());

        $categoryProgress->start();

        foreach ($this->partCategories as $category) {
            $this->api->setUrlParam('part_cat_id', $category->id);
            $parts = $this->api->getAllParts();

            if ($parts->count()) {
                $this->processParts($parts);
            }

            $this->api->resetRequest();

            $categoryProgress->advance();
        }

        $categoryProgress->finish();
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
                $newImage = PartImageUrl::create([
                    'part_num' => $part['part_num'],
                    'image_url' => $part['part_img_url']
                ]);
                $this->partImageUrls->push($newImage);
            }

            $partsProgress->advance();
        }

        $partsProgress->finish();

        return true;
    }
}
