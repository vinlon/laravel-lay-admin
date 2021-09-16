<?php

namespace Vinlon\Laravel\LayAdmin\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Input\InputArgument;

class AdminControllerMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'admin:controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Admin Controller';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $modelName = trim($this->argument('model'));
        Artisan::call('make:controller', [
            'name' => 'Admin/' . $modelName . 'Controller',
            '--type' => 'admin',
            '--api' => true,
            '--model' => $modelName,
        ], $this->output);

        return 0;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['model', InputArgument::REQUIRED, 'The name of the class'],
        ];
    }
}
