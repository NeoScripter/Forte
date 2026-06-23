<?php

declare(strict_types=1);

define('VITE_DEV_SERVER', 'http://localhost:5173');

function vite_is_dev(): bool
{
    $handle = @fsockopen('localhost', 5173, timeout: 1);
    if (!$handle) return false;
    fclose($handle);
    return true;
}

function vite_tags(string $entry = ''): string
{
    if (vite_is_dev()) {
        return <<<HTML
        <script type="module" src="http://localhost:5173/@vite/client"></script>
        <script type="module" src="http://localhost:5173/{$entry}"></script>
        HTML;
    }

    $manifestPath = APP_DIR . '/public/dist/.vite/manifest.json';
    if (!file_exists($manifestPath)) {
        $manifestPath = APP_DIR . '/public/dist/manifest.json';
    }

    if (!file_exists($manifestPath)) {
        throw new RuntimeException('Vite manifest not found. Run `vite build`.');
    }

    $manifest = json_decode(file_get_contents($manifestPath), true);

    if (!isset($manifest[$entry])) {
        throw new RuntimeException("Entry '{$entry}' not found in Vite manifest.");
    }

    $chunk = $manifest[$entry];
    $tags = [];

    $tags[] = "<script type='module' src='/dist/{$chunk['file']}'></script>";

    foreach ($chunk['css'] ?? [] as $css) {
        $tags[] = "<link rel='stylesheet' href='/dist/{$css}'>";
    }

    foreach ($chunk['imports'] ?? [] as $importKey) {
        foreach ($manifest[$importKey]['css'] ?? [] as $css) {
            $tags[] = "<link rel='stylesheet' href='/dist/{$css}'>";
        }
    }

    return implode(PHP_EOL, $tags) . PHP_EOL;
}

function build_src_set(string $path, string $size, string $ext): string
{
    $sources = [
        ["{$path}-{$size}.{$ext}",     '1x'],
        ["{$path}-{$size}2x.{$ext}",   '2x'],
        ["{$path}-{$size}3x.{$ext}",   '3x'],
    ];

    return implode(
        ', ',
        array_map(
            fn($s) => "{$s[0]} {$s[1]}",
            array_filter($sources, fn($s) => !empty($s[0]))
        )
    );
}

function component(string $path, array $props = []): string
{
    return View::instance()->render("/components/{$path}.php", "text/html", $props);
}

function slot(string $path, array $props = []): void
{
    \Base::instance()->push(
        'SLOTS',
        ['path' => $path, 'props' => $props]
    );
    ob_start();
}

function end_slot(): void
{
    $current = \Base::instance()->pop('SLOTS');
    $content = trim(ob_get_clean());

    echo View::instance()->render(
        "/components/{$current['path']}.php",
        "text/html",
        array_merge($current['props'], ['slot' => $content])
    );
}

function view(string $name, array $props = []): void
{
    echo View::instance()->render("{$name}.php", "text/html", $props);
}

function serialize_attrs(array|null $attrs)
{
    $attr_string = '';

    foreach ($attrs as $key => $val) {
        if ($val === true) {
            $attr_string .= " $key";
        } elseif ($val !== false && $val !== null) {
            $attr_string .= " $key=\"$val\"";
        }
    }

    return $attr_string;
}

function svg($name)
{
    $path = APP_DIR . "/public/assets/svgs/{$name}.svg";

    if (file_exists($path)) {
        include($path);
    } else {
        return '';
    };
}


function set_values(array $values)
{
    $flash = \Flash::instance();

    foreach ($values as $key => $val) {
        $flash->setKey("values.{$key}", $val);
    }
};

function set_errors(array $errors)
{
    $flash = \Flash::instance();

    foreach ($errors as $key => $val) {
        $flash->setKey("errors.{$key}", $val);
    }
};

function notify(string $notification)
{
    \Flash::instance()->addMessage($notification);
}

function csrf()
{
    $token = \Base::instance()->get('CSRF');
    return "<input type='hidden' name='token' value='{$token}'>";
}

function check_csrf(array $data)
{
    $csrf = \Base::instance()->get('CSRF');
    $token = $data['token'] ?? null;

    if (empty($token) || empty($csrf) || $token !== $csrf) {
        throw new Exception('No csrf token found');
    }
}


