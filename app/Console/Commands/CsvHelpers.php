<?php

namespace App\Console\Commands;

use Illuminate\Support\LazyCollection;
use Illuminate\Support\Facades\Storage;
use wapmorgan\UnifiedArchive\UnifiedArchive;

trait CsvHelpers
{
    protected $csvDetails = null;

    /**
     * Reset directory
     *
     * @return void
     */
    protected function checkDirectory()
    {
        Storage::deleteDirectory('csv_files');
        Storage::makeDirectory('csv_files');
    }

    /**
     * Retrieve the actual csv file from rebrickable
     *
     * @return void
     */
    protected function retrieveFile()
    {
        $this->updateStatus('Retrieving CSV file from Rebrickable...');

        $csv = file_get_contents($this->url);
        if (Storage::put('csv_files/rebrickable.csv.gz', $csv)) {
            $file = UnifiedArchive::open(storage_path('app/csv_files/rebrickable.csv.gz'));
            $this->csvDetails = $file->getFileData('rebrickable.csv.gz');
            $file->extractFiles(storage_path('app/csv_files/'));
            Storage::move('csv_files/rebrickable.csv.gz', 'csv_files/rebrickable.csv');
            return true;
        }

        $this->error('** The file was unable to be retrieved from '.$this->url.' **');
        $this->goodbye();
    }

    protected function lazyCollection()
    {
        return LazyCollection::make(function () {
            $handle = fopen(storage_path('app/csv_files/rebrickable.csv'), 'r');

            while (($line = fgets($handle)) !== false) {
                if (substr($line, 0, strlen($this->keys->first())) == $this->keys->first()) {
                    continue;
                }
                yield $this->keys->combine(str_getcsv($line), ',');
            }
        });
    }

    /**
     * Delete the downloaded csv file
     *
     * @return void
     */
    protected function cleanUp()
    {
        Storage::delete('csv_files/rebrickable.csv');
    }

    protected function displayCsvDate()
    {
        $this->updateStatus('CSV file has a timestamp of '.(date('M d, Y, h:i a', $this->csvDetails->modificationTime)).' <<');
    }
}
