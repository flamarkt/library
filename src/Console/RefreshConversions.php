<?php

namespace Flamarkt\Library\Console;

use Flamarkt\Library\CreateConversions;
use Flamarkt\Library\File;
use Illuminate\Console\Command;
use Illuminate\Contracts\Queue\Queue;

class RefreshConversions extends Command
{
    protected $signature = 'flamarkt:library:refresh';

    protected $queue;

    public function __construct(Queue $queue)
    {
        parent::__construct();

        $this->queue = $queue;
    }

    public function handle()
    {
        $this->info('Re-creating conversions for all files.');

        $progress = $this->output->createProgressBar(File::query()->count());

        File::query()->each(function (File $file) use ($progress) {
            // TODO: force sync
            $this->queue->push(new CreateConversions($file));

            $progress->advance();
        });

        $progress->finish();

        $this->info('Done.');
    }
}
