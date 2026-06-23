<?php

declare(strict_types=1);


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

