<?php

declare(strict_types=1);

namespace Rinvex\Composer\Console\Commands;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rinvex:publish:composer {--f|force : Overwrite any existing files.} {--r|resource=all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish Rinvex Composer Resources.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->alert($this->description);

        $this->call('vendor:publish', ['--tag' => 'rinvex/composer::config', '--force' => $this->option('force')]);

        $this->line('');
    }
}
