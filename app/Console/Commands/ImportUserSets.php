<?php

namespace App\Console\Commands;

use App\UserSet;
use Illuminate\Console\Command;
use App\Gateways\RebrickableApiUser;

class ImportUserSets extends Command
{
    use CommandHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lego:import-user-sets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports all the user sets from Rebrickable';

    /**
     * RebrickableApiUser instance
     *
     * @var null
     */
    protected $api = null;

    /**
     * Rebrickable User Sets
     *
     * @var null
     */
    protected $sets = null;

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

        $this->start();

        $this->info('');
        $this->setupApiInstance();
        $this->truncateTable();
        $this->getRebrickableUserSets();
        $this->importSets();

        $this->info('');
        $this->goodbye();
    }

    protected function start()
    {
        $this->info('>> Please wait while we import all your sets from Rebrickable <<');
    }

    protected function setupApiInstance()
    {
        $this->updateStatus('Setting up api instance...');

        $this->api = new RebrickableApiUser();
    }

    protected function truncateTable()
    {
        $this->updateStatus('Truncating table...');

        UserSet::truncate();
    }

    protected function getRebrickableUserSets()
    {
        $this->updateStatus('Getting all User Sets from Rebrickable...');

        $this->sets = $this->api->getAll('sets');
    }

    protected function importSets()
    {
        $this->updateStatus('Importing Rebrickable Sets into Database...');

        $sets = $this->sets;

        if (! $sets->count()) {
            return false;
        }
        $progress = $this->output->createProgressBar($sets->count());
        $progress->start();

        foreach ($sets as $set) {
            UserSet::create([
                'set_num' => $set['set']['set_num'],
                'quantity' => $set['quantity']
            ]);

            $this->processed++;
            $progress->advance();
        }

        $progress->finish();
    }
}
