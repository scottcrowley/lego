<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Gateways\RebrickableApiUser;
use Symfony\Component\Console\Question\Question;

class install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lego:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets up the initial install of your Lego Project App';

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
        $this->welcome();
        $this->createEnvFile();

        if (strlen(config('app.key')) === 0) {
            $this->call('key:generate');
            $this->line('~ Secret key properly generated.');
        }

        $credentials_db = $this->requestDatabaseCredentials();
        $this->updateEnvironmentFile($credentials_db);
        $this->line('~ Database credentials successfully added.');

        if ($this->confirm('Do you want to migrate the database?', false)) {
            $this->migrateDatabaseWithFreshCredentials($credentials_db);
            $this->line('~ Database successfully migrated.');
        }

        $credentials_rb = $this->requestRebrickableCredentials();
        $credentials_rb['REBRICKABLE_USER_TOKEN'] = $this->generateUserToken($credentials_rb);
        $this->updateEnvironmentFile($credentials_rb);
        $this->line('~ Rebrickable credentials successfully added.');

        $this->call('cache:clear');
        $this->goodbye();
    }

    /**
     * Display the welcome message.
     */
    protected function welcome()
    {
        $this->info('>> Welcome to the Lego Project App installation process! <<');
    }

    /**
     * Create the initial .env file.
     */
    protected function createEnvFile()
    {
        if (! file_exists('.env')) {
            copy('.env.example', '.env');

            $this->line('.env file successfully created');
        }
    }

    /**
     * Update the .env file from an array of $key => $value pairs.
     *
     * @param  array $updatedValues
     * @return void
     */
    protected function updateEnvironmentFile($updatedValues)
    {
        $envFile = $this->laravel->environmentFilePath();

        foreach ($updatedValues as $key => $value) {
            file_put_contents($envFile, preg_replace(
                "/{$key}=(.*)/",
                $key.'='.strtr($value, ['\\\\' => '\\\\\\\\', '$' => '\\$']),
                file_get_contents($envFile)
            ));
        }
    }

    /**
     * Request the local database details from the user.
     *
     * @return array
     */
    protected function requestDatabaseCredentials()
    {
        return [
            'DB_DATABASE' => $this->ask('Database name'),
            'DB_PORT' => $this->ask('Database port', 3306),
            'DB_USERNAME' => $this->ask('Database user'),
            'DB_PASSWORD' => $this->askHiddenWithDefault('Database password (leave blank for no password)'),
        ];
    }

    /**
     * Request the Rebrickable credentials from the user.
     *
     * @return array
     */
    protected function requestRebrickableCredentials()
    {
        return [
            'REBRICKABLE_EMAIL' => $this->ask('Rebrickable Login Email'),
            'REBRICKABLE_USERNAME' => $this->ask('Rebrickable Username'),
            'REBRICKABLE_PASSWORD' => $this->ask('Rebrickable Login password'),
            'REBRICKABLE_API_KEY' => $this->ask('Rebrickable API Key'),
        ];
    }

    /**
     * Generates the Rebrickable User Token
     *
     * @return array
     */
    protected function generateUserToken($credentials_rb)
    {
        $api = new RebrickableApiUser(true);
        $api->updateCredentials('email', $credentials_rb['REBRICKABLE_EMAIL']);
        $api->updateCredentials('password', $credentials_rb['REBRICKABLE_PASSWORD']);
        $api->updateCredentials('key', $credentials_rb['REBRICKABLE_API_KEY']);

        $result = $api->generateToken();
        if (isset($result['status'])) {
            $this->warn('>> An error occurred while trying to generate the Rebrickable User Token <<');
            $this->warn('>> '.$result['status'].': '.$result['detail'].' <<');
            die();
        }

        return $result['user_token'];
    }

    /**
     * Migrate the db with the new credentials.
     *
     * @param array $credentials
     * @return void
     */
    protected function migrateDatabaseWithFreshCredentials($credentials)
    {
        foreach ($credentials as $key => $value) {
            $configKey = strtolower(str_replace('DB_', '', $key));

            if ($configKey === 'password' && $value == 'null') {
                config(["database.connections.mysql.{$configKey}" => '']);

                continue;
            }

            config(["database.connections.mysql.{$configKey}" => $value]);
        }

        $this->call('migrate');
    }

    /**
     * Prompt the user for optional input but hide the answer from the console.
     *
     * @param  string  $question
     * @param  bool    $fallback
     * @return string
     */
    public function askHiddenWithDefault($question, $fallback = true)
    {
        $question = new Question($question, 'null');

        $question->setHidden(true)->setHiddenFallback($fallback);

        return $this->output->askQuestion($question);
    }

    /**
     * Display the completion message.
     */
    protected function goodbye()
    {
        $this->info('>> The installation process is complete. Enjoy your new Lego App! <<');
    }
}
