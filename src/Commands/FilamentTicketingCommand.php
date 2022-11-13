<?php

namespace SGCompTech\FilamentTicketing\Commands;

use Illuminate\Console\Command;

class FilamentTicketingCommand extends Command
{
    public $signature = 'filament-ticketing';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
