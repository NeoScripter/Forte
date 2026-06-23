<?php

declare(strict_types=1);

namespace Support;

use Log;
use Throwable;

final class ErrorHandler
{
    static function handle(
        Throwable $exception,
        array $context = [],
        int $code = 500,
        string $message = 'We are sorry, but a server error occurred'
    ): void {
        $is_debug = \Base::instance()->get('app_debug') ?? true;
        $is_cli = \Base::instance()->get('cli') ?? false;

        if ($is_cli) {
            echo $exception->getMessage();
            exit;
        }

        if ($is_debug) {
            throw $exception;
        }

        $lines = preg_replace('/\s+/', ' ', $exception->getMessage());

        foreach ($context as $key => $value) {
            $lines .= "\n";
            $lines .= "{$key}: {$value}";
        }

        $logger = new Log('error.log');
        $logger->write($lines, format: 'd-m-Y H:i:s');

        echo view('pages/shared/error', [
            'code'    => $code,
            'message' => $message,
        ]);
        exit;
    }
}
