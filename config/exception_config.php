<?php

error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

function handle_error(\Base $hive)
{
    $is_cli = \Base::instance()->get('CLI') ?? false;

    if ($is_cli) {
        foreach ($hive->get('ERROR') as $err) {
            if (empty($err)) {
                continue;
            }
            cli_echo($err, 'error');
        }
        exit;
    }

    if ($hive->app_debug === true) {
        Falsum\Run::handleError($hive);
    } else {
        while (ob_get_level())
            ob_end_clean();

        echo render_error_page();
    }
}

$hive = \Base::instance();
$hive->set('ONERROR', 'handle_error');

function render_error_page(): void
{
    echo <<<HTML
    <!doctype html>
    <html lang="en" style="overflow-x: clip;">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <style>
            @font-face {
                font-family: 'Montserrat';
                font-style: normal;
                font-weight: 100 900;
                font-display: swap;
                src: url('/assets/fonts/JTUSjIg1_i6t8kCHKm459Wlhyw.woff2') format('woff2');
                unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA,
                               U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122,
                               U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
            }

            *, *::before, *::after {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }

            html {
                overflow-x: clip;
            }

            body {
                overflow-x: clip;
                height: 100vh;
                max-width: 120rem;
                margin-left: auto;
                margin-right: auto;
                font-family: 'Montserrat', monospace;
            }

            main {
                display: grid;
                place-content: center;
                place-items: center;
                height: 100%;
                text-transform: uppercase;
                font-size: 1rem;
            }

            @media (min-width: 640px) {
                main { font-size: 1.25rem; }
            }

            @media (min-width: 1024px) {
                main { font-size: 1.5rem; }
            }

            .error-label {
                font-weight: 600;
                letter-spacing: 0.025em;
            }

            .error-code {
                font-weight: 900;
                margin-top: 1rem;
                margin-bottom: 1.5rem;
                line-height: 0.75em;
                display: grid;
                grid-auto-flow: column;
                font-size: 8rem;
                letter-spacing: -2rem;
                text-shadow: -0.02em -0.02em 0px rgba(255, 255, 255, 1);
            }

            @media (min-width: 640px) {
                .error-code { font-size: 14rem; }
            }

            @media (min-width: 1024px) {
                .error-code {
                    font-size: 20rem;
                    margin-top: 0.06em;
                    margin-bottom: 0.12em;
                }
            }

            .digit-left {
                transform: translateX(30%);
            }

            .digit-mid {
                isolation: isolate;
            }

            .digit-right {
                transform: translateX(-30%);
            }

            .error-message {
                font-weight: 500;
                letter-spacing: 0.025em;
                max-width: min(35rem, 90%);
                text-align: center;
                text-wrap: balance;
            }
        </style>
    </head>
    <body>
        <main>
            <div class="error-label">Oops! An error occured</div>
            <div class="error-code">
                <span class="digit-left">5</span>
                <span class="digit-mid">0</span>
                <span class="digit-right">0</span>
            </div>
            <div class="error-message">Please try again later</div>
        </main>
    </body>
    </html>
    HTML;
}
