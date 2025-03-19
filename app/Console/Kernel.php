<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\RoomZoomCall;
use App\Http\Controllers\VideoCallController;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $meetings = RoomZoomCall::where('status', 'scheduled')
                ->where('start_time', '<=', now()->subMinutes(2)) // Meeting selesai
                ->get();

            foreach ($meetings as $meeting) {
                $recordingUrl = app(VideoCallController::class)->fetchRecordingUrl($meeting->zoom_meeting_id);

                if ($recordingUrl) {
                    $meeting->update(['recording_url' => $recordingUrl]);
                }
            }
        })->everyFiveMinutes(); // Cek tiap 5 menit
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
