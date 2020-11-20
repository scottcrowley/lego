<?php

namespace App\Console\Commands;

use App\UserSet;
use Illuminate\Console\Command;

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
     * Rebrickable User Sets
     *
     * @var object
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

        $this->start();

        $this->info('');
        $this->setupApiUserInstance();
        $this->truncateTable(new UserSet());
        $this->getRebrickableUserSets();
        $this->importSets();
        $this->goodbye();
    }

    /**
     * Display command details and request confirmation to continue
     *
     * @return bool
     */
    protected function start()
    {
        $this->info('>> Please wait while we import all your sets from Rebrickable <<');
    }

    /**
     * Retrieve all user sets from Rebrickable
     *
     * @return void
     */
    protected function getRebrickableUserSets()
    {
        $this->updateStatus('Getting all User Sets from Rebrickable...');

        $this->sets = $this->api->getAll('sets');
    }

    /**
     * Process to import all user sets
     *
     * @return void
     */
    protected function importSets()
    {
        $this->updateStatus('Importing User Sets into Database...');

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
