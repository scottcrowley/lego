<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Schema;

trait CommandHelpers
{
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
     * Total items processed
     *
     * @var null
     */
    protected $processed = 0;

    /**
     * RebrickableApi(Lego|User) instance
     *
     * @var null
     */
    protected $api = null;

    /**
     * End the command execution and display process time and processed item count
     *
     * @return void
     */
    protected function goodbye()
    {
        $processLabel = $this->calculateProcessTime();

        $this->info('');
        $this->info('');
        $this->info('>>>> Process completed in '.$processLabel.'! <<<<');
        if ($this->processed) {
            $this->info('>>>> A total of '.$this->processed.' items were processed. <<<<');
        }
        die();
    }

    /**
     * Calculates the total process time for the command
     *
     * @return void
     */
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

    /**
     * Adds a info message to be displayed
     *
     * @param string $status
     * @return void
     */
    protected function updateStatus($status)
    {
        $this->info('----> '.$status);
    }

    /**
     * Sets up the api instance base on the type given
     *
     * @param string $type
     * @return void
     */
    protected function setupApiInstance($type)
    {
        $this->updateStatus('Setting up api instance...');

        $this->api = ($type == 'lego') ? new \App\Gateways\RebrickableApiLego : new \App\Gateways\RebrickableApiUser;
    }

    /**
     * Sets up the RebrickableApiLego instance
     *
     * @return void
     */
    protected function setupApiLegoInstance()
    {
        $this->setupApiInstance('lego');
    }

    /**
     * Sets up the RebrickableApiUser instance
     *
     * @return void
     */
    protected function setupApiUserInstance()
    {
        $this->setupApiInstance('user');
    }

    /**
     * Truncate the table for a given model
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param bool $disableFK
     * @return void
     */
    protected function truncateTable($model, $disableFK = false)
    {
        $this->updateStatus('Truncating table...');

        if ($disableFK) {
            Schema::disableForeignKeyConstraints();
            $model::truncate();
            Schema::enableForeignKeyConstraints();
        }

        $model::truncate();
    }

    // protected function spliceCollection($collection)
    // {
    //     $this->calculateCollectionSplice($collection->count());

    //     dump('start: '.$this->start);
    //     dump('end: '.$this->end);
    //     dump('begin: '.$this->begin);
    //     dump('limit: '.$this->limit);
    //     // $indexStart = $collection->search(function ($item, $key) {
    //     //     return $item['set_num'] == '41303-1';
    //     // });
    //     // $indexEnd = $collection->search(function ($item, $key) {
    //     //     return $item['set_num'] == '71010-17';
    //     // });
    //     $splice = $collection->splice($this->begin, $this->limit);
    //     // dump('count: '.$splice->count());
    //     // dump('index start: '.$indexStart);
    //     // dump('index end: '.$indexEnd);
    //     // dump($collection[$indexStart]->toArray());
    //     // dump($collection[$indexEnd]->toArray());
    //     // dump($splice->take(2)->toArray());
    //     // dd($splice->take(-2)->toArray());

    //     // $collection->each(function ($item, $key) {
    //     //     dump($key.'::'.$item['set_num']);
    //     // });
    //     // die();

    //     $prevKey = $this->begin - 1;
    //     $prev = ($prevKey > 0) ? $collection[$prevKey] : null;
    //     if ($prev) {
    //         dump('prevKey: '.$prevKey);
    //         dump('prev: '.$prev['set_num']);
    //     }
    //     $splice->each(function ($item, $key) {
    //         dump($key.'::'.$item['set_num']);
    //     });
    //     die();

    //     if ($this->limit > 0) {
    //         return $collection->splice($this->begin, $this->limit);
    //     }
    // }

    // protected function calculateCollectionSplice($count)
    // {
    //     if ($this->start > $count) {
    //         $this->begin = 0;
    //     }

    //     if ($this->start > 1) {
    //         $this->begin = $this->start - 1;
    //     }

    //     if (($this->start > $this->end) || (($this->end - $this->start) > $count)) {
    //         $this->limit = $count - $this->begin;
    //     } elseif ($this->begin > 1 && $this->end > 0 && $this->end < $count) {
    //         $this->limit = $this->end - $this->begin;
    //     } elseif ($this->begin == 0 && $this->end > 0) {
    //         $this->limit = $this->end;
    //     }
    // }
}
