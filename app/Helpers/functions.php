<?php

use Support\ErrorHandler;

define('VITE_DEV_SERVER', 'http://localhost:5173');

function vite_is_dev(): bool
{
    $handle = @fsockopen('localhost', 5173, timeout: 1);
    if (!$handle) return false;
    fclose($handle);
    return true;
}

// function vite_tags(): string
// {
//     if (vite_is_dev()) {
//         return implode(PHP_EOL, [
//             "<script type='module' src='" . VITE_DEV_SERVER . "/@vite/client'></script>",
//             "<script type='module' src='" . VITE_DEV_SERVER . "/ui/ts/main.ts'></script>",
//         ]) . PHP_EOL;
//     }

//     $assets = glob('dist/assets/*');

//     $js  = current(preg_grep('/\.js$/', $assets));
//     $css = current(preg_grep('/\.css$/', $assets));

//     return implode(PHP_EOL, array_filter([
//         $js  ? "<script type='module' src='/dist/assets/" . basename($js)  . "'></script>" : '',
//         $css ? "<link rel='stylesheet' href='/dist/assets/" . basename($css) . "'>"         : '',
//     ])) . PHP_EOL;
// }


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

function get_db_table_names()
{
    $files = glob(APP_DIR . '/db/migrations/*');
    sort($files);

    return array_filter(
        array_map(
            function ($file) {
                $filename = basename($file);

                if (preg_match('/create_([a-z_]+)_table/', $filename, $matches)) {
                    return $matches[1];
                }

                return '';
            },
            $files
        ),
        'strlen'
    );
}


function delete_files_recursive(array $files)
{
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        } else if (is_dir($file)) {
            delete_files_recursive(glob($file . '/*'));
            rmdir($file);
        }
    }
}


function cli_echo(string $message, string $type = 'success'): void
{
    $color = match ($type) {
        'success' => '32',
        'error'   => '31',
        'warning' => '33',
        'info'    => '36',
        default   => '0'
    };

    echo "\033[{$color}m{$message}\033[0m\n";
}

function is_image_attached(int $parent_id, string $parent_type, ?string $where = ''): bool
{
    $where = $where ? "AND $where " : '';

    $rows = Base::instance()->get('DB')->exec(
        "SELECT * FROM images WHERE imageable_id = ? AND imageable_type = ? {$where}LIMIT 1",
        [$parent_id, $parent_type]
    );

    if (empty($rows)) {
        return false;
    }

    return true;
}

function convert_to_plural($word)
{
    $word = strtolower($word);

    // Already plural
    if (str_ends_with($word, 's')) {
        return $word;
    }

    // Consonant + Y → change to IES
    if (preg_match('/[^aeiou]y$/', $word)) {
        return substr($word, 0, -1) . 'ies';
    }

    // Vowel + Y or anything else → just add S
    return $word . 's';
}

function convert_to_snake_case($word)
{
    return strtolower(preg_replace('/(?<!^)(?=[A-Z])/', '_', $word));
}

function convert_to_kebab_case($word)
{
    return strtolower(preg_replace('/(?<!^)(?=[A-Z])/', '-', $word));
}

function to_wildcards(array $arr, ?string $placeholder = '?')
{
    return implode(
        ',',
        array_fill(0, count($arr), $placeholder)
    );
}

function get_latest_id(string $table): int
{
    $res = Base::instance()->get('DB')->exec("SELECT MAX(id) AS max_id FROM {$table}");
    return $res[0]['max_id'] ?? 1;
}

function dd(...$vars)
{
    foreach ($vars as $v) {
        echo "<pre>";
        var_dump($v);
        echo "</pre>";
    }
    die(1);
}

function add_markdown_field(array &$data, string $from, string $to)
{
    if (! isset($data[$from]) || ! is_string($data[$from])) {
        return;
    }

    $data[$to] = \Markdown::instance()->convert($data[$from]);
}

function image_variants(array $sizes): array
{
    $sizes = array_map(
        fn($size) => count($size) > 1 ? $size : [$size[0], 0],
        $sizes
    );
    $variants = [];
    $formats  = ['webp', 'avif'];
    $scales   = [1, 2, 3];

    foreach ($sizes as $idx => [$name, $width]) {
        foreach ($formats as $format) {
            foreach ($scales as $scale) {
                $suffix     = $scale > 1 ? "_{$scale}x" : '';
                $variants[] = ["{$name}_{$format}{$suffix}", $width * $scale, $format];
            }
        }

        $variants[] = ["{$name}_tiny", 10 * ($idx + 2), 'webp'];
    }

    return $variants;
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
        $e = new Exception('No csrf token found');

        ErrorHandler::handle(exception: $e, code: 403);
    }
}

function normalize_image_input(array $input)
{
    $data = [];
    foreach ($input as $key => $value) {
        if (! is_array($value)) {
            $data[$key] = [$value];
        } else {
            $data[$key] = $value;
        }
    }

    return $data;
}
