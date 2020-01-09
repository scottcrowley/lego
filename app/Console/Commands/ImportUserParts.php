<?php

namespace App\Console\Commands;

use App\UserPart;
use ArrayIterator;
use Illuminate\Console\Command;
use App\Gateways\RebrickableApiUser;

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
     * RebrickableApiUser instance
     *
     * @var null
     */
    protected $api = null;

    /**
     * Rebrickable User Part count
     *
     * @var int
     */
    protected $partCount = 0;

    /**
     * First page of User Parts from Rebrickable
     *
     * @var collection
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
        $this->setupApiInstance();
        $this->truncateTable();
        $this->getRebrickablePartCount();
        $this->importParts();

        $this->info('');
        $this->goodbye();
    }

    protected function start()
    {
        $this->info('>> Please wait while we import all your parts from Rebrickable <<');
        return $this->confirm('>> This command can take a VERY long time to execute <<');
    }

    protected function setupApiInstance()
    {
        $this->updateStatus('Setting up api instance...');

        $this->api = new RebrickableApiUser();
    }

    protected function truncateTable()
    {
        $this->updateStatus('Truncating table...');

        UserPart::truncate();
    }

    protected function getRebrickablePartCount()
    {
        $this->updateStatus('Getting total number User Parts from Rebrickable...');

        $this->firstPage = $this->api->getPartsFirstPage($this->maxPerPage);

        $this->partCount = (int) $this->api->getPartCount();

        $this->pageCount = (int) ceil($this->partCount / $this->maxPerPage);
    }

    protected function getRebrickableUserParts($page = 1)
    {
        $this->updateStatus('Getting page '.$page.'/'.$this->pageCount.' of User Parts from Rebrickable...');

        return $this->api->getPartsByPage($page, $this->maxPerPage);
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

        // $parts = new ArrayIterator($this->parts->toArray());
        // $parts = $this->parts;

        // if (! $parts->count()) {
        //     return false;
        // }
        // $progress = $this->output->createProgressBar($parts->count());
        // $progress->start();

        // while ($part = $parts->current()) {
        // foreach ($parts as $part) {
            // UserPart::create([
            //     'part_num' => $part['part']['part_num'],
            //     'color_id' => $part['color']['id'],
            //     'quantity' => $part['quantity']
            // ]);
            // dump($part['part']['part_num'].' - '.$part['color']['id'].' - '.$part['quantity']);

            // $this->processed++;
            // $progress->advance();
            // $parts->next();
        // }

        // foreach ($parts as $part) {
        //     UserPart::create([
        //         'part_num' => $part['part']['part_num'],
        //         'color_id' => $part['color']['id'],
        //         'quantity' => $part['quantity']
        //     ]);

        //     $this->processed++;
        //     $progress->advance();
        // }

        // $progress->finish();
    }
}
