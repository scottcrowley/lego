<?php

namespace App\Console\Commands;

use App\Set;
use App\SetImageUrl;
use Illuminate\Console\Command;
use App\Gateways\RebrickableApiLego;

class UpdateSetImageUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lego:set-image-url 
                            {--theme= : Update only sets with this theme_id}
                            {--start= : Where in the Set collection to begin processing}
                            {--end= : Where in the Set collection to stop processing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the image url for all sets. Data is retrieved from Rebrickable';

    /**
     * Set image urls collection from existing table
     *
     * @var null
     */
    protected $setImageUrls = null;

    /**
     * Sets from database
     *
     * @var null
     */
    protected $allDbSets = null;

    /**
     * Sets from rebrickable
     *
     * @var null
     */
    protected $sets = null;

    /**
     * Missing sets from database
     *
     * @var array
     */
    protected $missingSets = [];

    /**
     * RebrickableApiLego instance
     *
     * @var null
     */
    protected $api = null;

    /**
     * Set start option
     *
     * @var int
     */
    protected $start = 0;

    /**
     * Set end option
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

        $this->getCurrentSetImages();
        $this->setupApiInstance();
        $this->getRebrickableSets();
        $this->spliceSets();
        $this->processSets();
        $this->displayMissingSets();

        $this->info('');
        $this->goodbye();
    }

    protected function start()
    {
        return $this->confirm('>> This command can take a VERY long time to execute <<');
    }

    protected function getCurrentSetImages()
    {
        $this->setImageUrls = SetImageUrl::all();
    }

    protected function setupApiInstance()
    {
        $this->api = new RebrickableApiLego;
    }

    protected function processSets()
    {
        $sets = $this->sets;

        if (! $sets->count()) {
            $this->warn('** No Sets found on the Rebrickable site **');
            $this->goodbye();
        }

        $allDbSets = $this->getAllDbSets();

        $images = $this->setImageUrls;

        $setsProgress = $this->output->createProgressBar($sets->count());
        $setsProgress->start();

        foreach ($sets as $set) {
            if (! $allDbSets->contains($set['set_num'])) {
                $this->missingSets[] = $set['set_num'];
                continue;
            }

            if (! $images->contains(
                function ($value, $key) use ($set) {
                    return $value->set_num == $set['set_num'] && $value->image_url == $set['set_img_url'];
                }
            )
                && ! is_null($set['set_img_url'])
            ) {
                $newImageUrl = SetImageUrl::create([
                    'set_num' => $set['set_num'],
                    'image_url' => $set['set_img_url']
                ]);
                $this->setImageUrls->push($newImageUrl);
            }

            $setsProgress->advance();
        }

        $setsProgress->finish();

        return true;
    }

    protected function displayMissingSets()
    {
        if (count($this->missingSets)) {
            $this->info('');
            $this->info('');
            $missing = implode(', ', $this->missingSets);
            $this->warn('The following '.count($this->missingSets).' sets are missing from the database: '.$missing);
        }
    }

    protected function goodbye()
    {
        $processLabel = $this->calculateProcessTime();

        $this->info('');
        $this->info('>>>> Process completed in '.$processLabel.'! <<<<');
        die();
    }

    protected function getAllDbSets()
    {
        if ($this->option('theme')) {
            return $this->allDbSets = Set::whereThemeId($this->option('theme'))->get()->pluck('set_num');
        }
        return $this->allDbSets = Set::all()->pluck('set_num');
    }

    protected function getRebrickableSets()
    {
        if ($this->option('theme')) {
            $this->api->setUrlParam('theme_id', $this->option('theme'));
        }
        $this->sets = $this->api->getAllSets();
    }

    protected function calculateSetSplice()
    {
        $setCount = $this->sets->count();

        if ($this->start > $setCount) {
            $this->start = 0;
        }

        if (($this->start > $this->end) || (($this->end - $this->start) > $setCount)) {
            $this->end = $setCount - $this->start;
        } elseif ($this->start > 0 && $this->end > 0 && $this->end < $setCount) {
            $this->end = ($this->end - $this->start) + 1;
        } elseif ($this->start == 0 && $this->end > 0) {
            $this->end = $this->end + 1;
        }
    }

    protected function spliceSets()
    {
        $this->calculateSetSplice();

        if ($this->start > 0 || $this->end > 0) {
            $this->sets = $this->sets->splice($this->start, $this->end);
        }
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
