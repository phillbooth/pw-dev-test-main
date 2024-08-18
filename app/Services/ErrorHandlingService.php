<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Class ErrorHandlingService
 * Handles error reporting and logging.
 */
class ErrorHandlingService
{
    /**
     * Reports the error to the log.
     *
     * @param Throwable $e
     * @return void
     */
    public function report(Throwable $e): void
    {
        Log::error('An error occurred', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
    }

    /**
     * Sends an alert to the admin or monitoring system.
     *
     * @param Throwable $e
     * @return void
     */
    public function alertAdmin(Throwable $e): void
    {
        // Code to notify admin or send alert (e.g., via email or Slack)
        Log::alert('Critical error: ' . $e->getMessage());
    }
}
