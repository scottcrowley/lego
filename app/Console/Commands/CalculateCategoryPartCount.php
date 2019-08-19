<?php

namespace App\Console\Commands;

use App\PartCategory;
use App\CategoryPartCount;
use Illuminate\Console\Command;

class CalculateCategoryPartCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lego:category-part-count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculates the category part count and populates the category_part_count table';

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
        $this->start();

        $this->truncateTable();

        $this->updateCount();

        $this->info('');
    }

    protected function start()
    {
        $this->info('>> Please wait while we update the part count for each Part Category <<');
    }

    protected function truncateTable()
    {
        CategoryPartCount::truncate();
    }

    protected function updateCount()
    {
        $categories = PartCategory::with('parts')->get();

        $progress = $this->output->createProgressBar($categories->count());

        $progress->start();

        foreach ($categories as $category) {
            $category->addPartCount($category->parts->count());

            $progress->advance();
        }

        $progress->finish();
    }
}
