<?php

namespace App\Console\Commands;

use App\UserPart;
use Illuminate\Console\Command;

class ImportUserParts extends Command
{
    use CommandHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lego:import-user-parts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports all the user parts from Rebrickable';

    /**
     * Rebrickable User Part count
     *
     * @var int
     */
    protected $partCount = 0;

    /**
     * First page of User Parts from Rebrickable
     *
     * @var \Illuminate\Support\Collection
     */
    protected $firstPage;

    /**
     * Max User Parts per page from Rebrickable
     *
     * @var int
     */
    protected $maxPerPage = 1000;

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
        $this->setupApiUserInstance();
        $this->truncateTable(new UserPart());
        $this->getRebrickablePartCount();
        $this->importParts();
        $this->goodbye();
    }

    /**
     * Display command details and request confirmation to continue
     *
     * @return bool
     */
    protected function start()
    {
        $this->info('>> Please wait while we import all your parts from Rebrickable <<');
        return $this->confirm('This command can take a VERY long time to execute. Continue?');
    }

    /**
     * Get user part count from Rebrickable
     *
     * @return void
     */
    protected function getRebrickablePartCount()
    {
        $this->updateStatus('Getting total number User Parts from Rebrickable...');

        $this->firstPage = $this->api->getPartsFirstPage($this->maxPerPage);

        $this->partCount = (int) $this->api->getPartCount();

        $this->pageCount = (int) ceil($this->partCount / $this->maxPerPage);
    }

    protected function importParts()
    {
        $this->updateStatus('Importing Rebrickable Parts into Database...');

        if (! $this->partCount) {
            return $this->goodbye();
        }

        for ($i = 1; $i <= $this->pageCount; $i++) {
            if ($i == 1) {
                $this->updateStatus('Getting page 1/'.$this->pageCount.' of User Parts from Rebrickable...');
                $parts = $this->firstPage;
            } else {
                $parts = $this->getRebrickableUserParts($i);
            }

            $this->updateStatus('Importing page '.$i.'/'.$this->pageCount.' of User Parts into Database...');
            $progress = $this->output->createProgressBar($parts->count());
            $progress->start();

            foreach ($parts as $part) {
                UserPart::create([
                    'part_num' => $part['part']['part_num'],
                    'color_id' => $part['color']['id'],
                    'quantity' => $part['quantity']
                ]);

                $this->processed++;
                $progress->advance();
            }
            $progress->finish();
            $this->info('');
        }
    }

    /**
     * Retrive user parts from Rebrickable for a given page
     *
     * @param mixed $page
     * @return void
     */
    protected function getRebrickableUserParts($page = 1)
    {
        $this->updateStatus('Getting page '.$page.'/'.$this->pageCount.' of User Parts from Rebrickable...');

        return $this->api->getPartsByPage($page, $this->maxPerPage);
    }
}
