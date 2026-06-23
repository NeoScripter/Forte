<?php

declare(strict_types=1);

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
