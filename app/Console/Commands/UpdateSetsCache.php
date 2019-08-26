<?php

namespace App\Console\Commands;

use App\Set;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class UpdateSetsCache extends Command
{
    use CommandHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lego:sets-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the Sets cache';

    /**
     * All sets
     *
     * @var Set
     */
    protected $sets;

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
        $this->processStart = microtime(true);

        $this->info('');
        $this->getSets();
        $this->clearCache();
        $this->cacheSets();

        $this->info('');
        $this->goodbye();
    }

    protected function getSets()
    {
        $this->updateStatus('Getting sets...');

        $allSets = collect([]);

        $sets = Set::with('theme')->get();

        $sets->each->append('theme_label');

        $this->sets = $sets;
    }

    protected function clearCache()
    {
        $this->updateStatus('Clearing set cache...');

        Cache::forget('sets');
    }

    protected function cacheSets()
    {
        $this->updateStatus('Caching set collection...');

        $sets = $this->sets->keyBy('set_num');

        $sets = $sets->toArray();

        Cache::forever('sets', $this->sets);

        $this->processed = $this->sets->count();
    }
}
