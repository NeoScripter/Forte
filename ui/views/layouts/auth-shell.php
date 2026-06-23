<!doctype html>
<html lang="en" class="overflow-x-clip <?= (\Base::instance()->COOKIE['theme'] ?? '') === 'dark' ? 'dark' : ''  ?>">

<head>
    <meta charset="UTF-8" />
    <link rel="icon" href="/favicon.webp" type="image/webp">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link
        rel="preload"
        href="/assets/fonts/InstrumentSans-SemiBold.woff2"
        as="font"
        type="font/woff2"
        crossorigin />
    <link
        rel="preload"
        href="/assets/fonts/InstrumentSans-Medium.woff2"
        as="font"
        type="font/woff2"
        crossorigin />
    <link
        rel="preload"
        href="/assets/fonts/InstrumentSans-Bold.woff2"
        as="font"
        type="font/woff2"
        crossorigin />
    <link
        rel="preload"
        href="/assets/fonts/InstrumentSans-Regular.woff2"
        as="font"
        type="font/woff2"
        crossorigin />

    <style>
        @font-face {
            font-family: 'InstrumentSans';
            font-style: normal;
            font-weight: 400;
            font-display: swap;
            src: url('/assets/fonts/InstrumentSans-Regular.woff2') format('woff2');
            unicode-range:
                U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6,
                U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC,
                U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }

        @font-face {
            font-family: 'InstrumentSans';
            font-style: normal;
            font-weight: 500;
            font-display: swap;
            src: url('/assets/fonts/InstrumentSans-Medium.woff2') format('woff2');
            unicode-range:
                U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6,
                U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC,
                U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }

        @font-face {
            font-family: 'InstrumentSans';
            font-style: normal;
            font-weight: 500;
            font-display: swap;
            src: url('/assets/fonts/InstrumentSans-Medium.woff2') format('woff2');
            unicode-range:
                U+0301, U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
        }

        @font-face {
            font-family: 'InstrumentSans';
            font-style: normal;
            font-weight: 600;
            font-display: swap;
            src: url('/assets/fonts/InstrumentSans-SemiBold.woff2') format('woff2');
            unicode-range:
                U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6,
                U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC,
                U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }

        @font-face {
            font-family: 'InstrumentSans';
            font-style: normal;
            font-weight: 700;
            font-display: swap;
            src: url('/assets/fonts/InstrumentSans-Bold.woff2') format('woff2');
            unicode-range:
                U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6,
                U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC,
                U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }
    </style>

    <style>
        html {
            background-color: oklch(1 0 0);
        }

        html.dark {
            background-color: oklch(0.2435 0 0);
        }
    </style>

    <?= vite_tags('ui/ts/auth.ts') ?>
    <link rel="icon" href="/favicon.webp" type="image/webp">
    <title><?= "Admin Panel" . (isset($title) ? " - {$title}" : '') ?></title>
</head>

<body class="overflow-x-clip font-sans">
    <div id="app" class="min-h-screen">
        <?= view($inc, $props ?? []) ?>
    </div>

    <?= component('ui/toast') ?>
</body>

</html>
