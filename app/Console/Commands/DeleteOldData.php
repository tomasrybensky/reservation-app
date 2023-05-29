<?php

namespace App\Console\Commands;

use App\Models\FullTable;
use App\Models\Reservation;
use Illuminate\Console\Command;

class DeleteOldData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-old-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Reservation::whereDate(Reservation::START, '<', now()->toDateString())
            ->delete();

        FullTable::whereDate(FullTable::DATE, '<', now()->toDateString())
            ->delete();

        return Command::SUCCESS;
    }
}
