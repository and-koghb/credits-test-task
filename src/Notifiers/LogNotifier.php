<?php

namespace App\Notifiers;

use Psr\Log\LoggerInterface;

class LogNotifier
{
    public function __construct(private LoggerInterface $logger) {}

    public function notify(string $message): void
    {
        $this->logger->info($message, [
            'source' => 'credit_approval'
        ]);
    }
}
