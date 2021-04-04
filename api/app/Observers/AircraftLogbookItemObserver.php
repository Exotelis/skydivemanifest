<?php

namespace App\Observers;

use App\Models\AircraftLogbookItem;
use Illuminate\Support\Facades\Log;

/**
 * Class AircraftLogbookItemObserver
 * @package App\Observers
 */
class AircraftLogbookItemObserver extends BaseObserver
{
    /**
     * Handle the AircraftLogbookItem "created" event.
     *
     * @param  AircraftLogbookItem $aircraftLogbookItem
     * @return void
     */
    public function created(AircraftLogbookItem $aircraftLogbookItem): void
    {
        Log::info("[Aircraft Logbook Item] '{$aircraftLogbookItem->logString()}' has been created by " .
            "'{$this->executedBy}'");
    }

    /**
     * Handle the AircraftLogbookItem "updated" event.
     *
     * @param  AircraftLogbookItem $aircraftLogbookItem
     * @return void
     */
    public function updated(AircraftLogbookItem $aircraftLogbookItem): void
    {
        $diff = $aircraftLogbookItem->getDiff();
        Log::info("[Aircraft Logbook Item] '{$aircraftLogbookItem->logString()}' has been updated by " .
            "'{$this->executedBy}' ($diff)");
    }

    /**
     * Handle the AircraftLogbookItem "deleted" event.
     *
     * @param  AircraftLogbookItem $aircraftLogbookItem
     * @return void
     */
    public function deleted(AircraftLogbookItem $aircraftLogbookItem): void
    {
        Log::info("[Aircraft Logbook Item] '{$aircraftLogbookItem->logString()}' has been deleted by " .
            "'{$this->executedBy}'");
    }
}
