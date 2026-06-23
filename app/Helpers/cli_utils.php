<?php

declare(strict_types=1);

function cli_color(string $message, string $status = 'success'): string
{
    $color = match ($status) {
        'success' => '32',
        'error'   => '31',
        'warning' => '33',
        'info'    => '34',
        default   => '0'
    };

    return "\033[{$color}m{$message}\033[0m";
}

function cli_echo(string $message, string $status = 'success'): void
{
    echo cli_color($message . "\n", $status);
}

