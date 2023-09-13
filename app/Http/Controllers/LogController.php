<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LogController extends Controller
{
    public function showLogs()
    {
        $logPath = storage_path('logs/laravel.log');
        $logData = File::exists($logPath) ? File::get($logPath) : 'No log files found';

        $logEntries = explode("\n", $logData);

        return view('logs', ['logEntries' => $logEntries]);
    }

    public function clearLogs()
    {
        $logPath = storage_path('logs/laravel.log');
        File::put($logPath, ''); // Clear the log file
        return redirect('/logs'); // Redirect back to the logs page
    }

}
