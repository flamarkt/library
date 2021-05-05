<?php

namespace Flamarkt\Library;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class CreateConversions implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $file;

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    public function handle()
    {
        resolve(ConversionRepository::class)->regenerate($this->file);
    }
}
