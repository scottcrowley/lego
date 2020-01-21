<?php

namespace App\Console\Commands;

use App\PartCategory;
use App\CategoryPartCount;
use Illuminate\Console\Command;

class CalculateCategoryPartCount extends Command
{
    use CommandHelpers;

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
        $this->processStart = microtime(true);

        $this->start();

        $this->info('');
        $this->truncateTable(new CategoryPartCount());
        $this->updateCount();
        $this->goodbye();
    }

    /**
     * Display command details
     *
     * @return bool
     */
    protected function start()
    {
        $this->info('>> Please wait while we update the part count for each Part Category <<');
    }

    protected function updateCount()
    {
        $categories = $this->getPartCategories();

        $this->updateStatus('Updating category part count...');

        $progress = $this->output->createProgressBar($categories->count());
        $progress->start();

        foreach ($categories as $category) {
            $category->addPartCount($category->parts->count());

            $progress->advance();
        }

        $this->processed = $categories->count();

        $progress->finish();
    }

    protected function getPartCategories()
    {
        $this->updateStatus('Getting part categories from database...');

        return PartCategory::with('parts')->get();
    }
}
