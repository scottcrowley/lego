<?php

namespace App\Console\Commands;

use App\Theme;
use App\ThemeLabel;
use Illuminate\Console\Command;

class UpdateThemeHierarchy extends Command
{
    use CommandHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lego:theme-hierarchy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the Theme hierarchy label';

    protected $processedThemes = [];

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
        $this->getThemeHeirarchy();

        if (! count($this->processedThemes)) {
            $this->warn('** No Themes found in the database **');
            $this->goodbye();
        }

        $this->processed = count($this->processedThemes);

        $this->truncateTable();
        $this->updateThemeLabels();

        $this->info('');
        $this->goodbye();
    }

    protected function getThemeHeirarchy()
    {
        $this->updateStatus('Getting theme hierarchy...');

        $allThemes = Theme::all();

        $themes = [];

        if ($allThemes->count()) {
            foreach ($allThemes as $theme) {
                if (! is_null($theme->parent_id)) {
                    $parents = $this->themeParentHierarchy($theme->toArray(), $allThemes);

                    $themes[$theme->id] = ['parents_label' => $parents['parents_label'], 'theme_label' => $parents['parents_label'].' -> '.$theme->name];
                } else {
                    $themes[$theme->id] = ['parents_label' => 'None', 'theme_label' => $theme->name];
                }
            }
        }
        $this->processedThemes = $themes;
    }

    protected function themeParentHierarchy($theme, $themes)
    {
        if (count($theme)) {
            $parents = [];
            $parentId = $theme['parent_id'];

            while (
                ! is_null(
                    $parent = $themes->where('id', $parentId)->first()
                )
            ) {
                $parents[] = $parent->toArray();
                $parentId = $parent['parent_id'];
            }
            $parents = array_reverse($parents); //make top level parent first

            $theme['parents'] = $parents;
            $theme['parents_label'] = '';
            if (count($parents)) {
                foreach ($parents as $parent) {
                    if ($theme['parents_label'] != '') {
                        $theme['parents_label'] .= ' -> ';
                    }
                    $theme['parents_label'] .= $parent['name'];
                }
            } else {
                $theme['parents_label'] = 'None';
            }
        }

        return $theme;
    }

    protected function truncateTable()
    {
        $this->updateStatus('Truncating table...');

        ThemeLabel::truncate();
    }

    protected function updateThemeLabels()
    {
        $this->updateStatus('Updating themes...');

        $themes = $this->processedThemes;

        $progress = $this->output->createProgressBar(count($themes));
        $progress->start();

        foreach ($themes as $theme_id => $theme) {
            ThemeLabel::create([
                'theme_id' => $theme_id,
                'parents_label' => $theme['parents_label'],
                'theme_label' => $theme['theme_label']
            ]);

            $progress->advance();
        }

        $progress->finish();
    }
}
