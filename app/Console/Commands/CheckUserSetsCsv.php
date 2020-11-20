<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckUserSetsCsv extends Command
{
    use CommandHelpers, CsvHelpers, CheckHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lego:check-user-sets-csv
                            {--bulk : Command being run with other commands}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifies that all table dependencies are present from csv file generated by Rebrickable.';

    /**
     * CSV File Url
     *
     * @var string
     */
    protected $url = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->checkTables = [
            'part_categories' => ['id', 'part_category_id']
        ];

        $this->missingDeps = $this->loadedDeps = [
            'part_categories' => []
        ];

        $this->keys = collect([
            'part_num',
            'name',
            'part_category_id',
            'part_material_id',
        ]);

        $this->url = 'https://rebrickable.com/users/'.(config('rebrickable.api.username')).'/setlists/sets/?format=rbsetscsv&';
    }

    /**
     * Display command details and request confirmation to continue
     *
     * @return bool
     */
    protected function start()
    {
        $this->info('>> Please wait while we verify all the dependencies are available for User Sets <<');
        return true;
    }
}
