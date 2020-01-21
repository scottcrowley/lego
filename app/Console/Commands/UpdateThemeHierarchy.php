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

    /**
     * Themes that have been processed
     *
     * @var array
     */
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
        $this->start();
        $this->processStart = microtime(true);

        $this->info('');
        $this->getThemeHeirarchy();
        $this->validateHeirarchy();
        $this->truncateTable(new ThemeLabel());
        $this->updateThemeLabels();
        $this->goodbye();
    }

    /**
     * Display command details
     *
     * @return void
     */
    protected function start()
    {
        $this->info('>> This command updates the Theme Heirarchy for all Themes in the Database <<');
    }

    /**
     * Validate the processedThemes
     *
     * @return bool
     */
    protected function validateHeirarchy()
    {
        if (count($this->processedThemes)) {
            return true;
        }

        $this->warn('** No Themes found in the database **');
        $this->goodbye();
    }

    /**
     * getThemeHeirarchy
     *
     * @return void
     */
    protected function getThemeHeirarchy()
    {
        $this->updateStatus('Getting all Themes from the Database...');

        $allThemes = Theme::all();

        $themes = [];

        $this->updateStatus('Calculating Theme hierarchy...');

        if ($allThemes->count()) {
            foreach ($allThemes as $theme) {
                if (! is_null($theme->parent_id)) {
                    $parents = $this->themeParentHierarchy($theme->toArray(), $allThemes);

                    $themes[$theme->id] = ['parents_label' => $parents['parents_label'], 'theme_label' => $parents['parents_label'].' / '.$theme->name];
                } else {
                    $themes[$theme->id] = ['parents_label' => 'None', 'theme_label' => $theme->name];
                }
            }
        }
        $this->processedThemes = $themes;
    }

    /**
     * Creates the Theme Heirarchy based on a given theme
     *
     * @param array $theme
     * @param \Illuminate\Support\Collection $themes
     * @return array
     */
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
                        $theme['parents_label'] .= ' / ';
                    }
                    $theme['parents_label'] .= $parent['name'];
                }
            } else {
                $theme['parents_label'] = 'None';
            }
        }

        return $theme;
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

            $this->processed++;
            $progress->advance();
        }

        $progress->finish();
    }
}
