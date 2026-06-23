<?php

const BYTES_IN_KB = 1024;

$validation = \Validation::instance();
$validation->loadLang();
$validation->onError(function ($text, $key) {
    \Base::instance()->set('errors.' . $key, $text);

    $flash = \Flash::instance();

    [$field,] = explode('.', $key);
    if (! $flash->hasKey('errors.' . $field)) {
        $flash->setKey('errors.' . $field, $text);
    }
});

$validation->addValidator('exists', function ($field, $input, $param) {
    $parts = explode(':', $param);

    if (count($parts) !== 2) {
        throw new Exception('Wrong syntax for the exists validation rule');
    }

    [$db_table, $db_col] = $parts;

    $db = Base::instance()->get('DB');

    $res = $db->exec("
            SELECT EXISTS (
                SELECT 1 FROM $db_table WHERE $db_col = ?
            ) AS exists
        ", [$input[$field]]);

    if (empty($res) || !$res[0]['exists']) {
        return false;
    }

    return true;
}, 'We could not find the existing {0}');

$validation->addValidator('unique', function ($field, $input, $param) {
    $parts = explode(':', $param);

    if (count($parts) < 2) {
        throw new Exception('Wrong syntax for the unique validation rule');
    }

    [$db_table, $db_col] = $parts;
    $col_parts = explode('*', $db_col);
    $db_col = $col_parts[0];

    $db = Base::instance()->get('DB');

    $args = [$input[$field]];
    $stmt = '';

    if (count($col_parts) === 2) {
        $excluded = $col_parts[1];
        $stmt = "AND $db_col != ?";
        $args[] = $excluded;
    }

    $res = $db->exec("
            SELECT EXISTS (
                SELECT 1 FROM $db_table WHERE $db_col = ? $stmt
            ) AS exists
        ", $args);

    if ($res[0]['exists']) {
        return false;
    }

    return true;
}, 'The {0} already exists');

$validation->addFilter('capitalize', function ($value) {
    $value = preg_replace('/\s+/', ' ', $value);
    $parts = explode(' ', $value);
    $skipped = ['in', 'at', 'on', 'for'];

    for ($i = 0; $i < count($parts); $i++) {
        if (! in_array($parts[$i], $skipped)) {
            $parts[$i] = mb_strtoupper($parts[$i][0]) . mb_strtolower(substr($parts[$i], 1));
        } else {
            $parts[$i] = mb_strtolower($parts[$i]);
        }
    }

    return implode(' ', $parts);
});

$validation->addValidator('boolean', function ($value) {
    return is_bool($value);
}, 'The value {0} must be a boolean');

$validation->addFilter('boolean', function ($value) {
    return (bool) in_array($value, ['1', 'true', 'on', 'yes']);
});

$validation->addValidator('max_size', function ($field, $input, $param) {
    if (! is_numeric($param)) {
        return false;
    }

    $limit_kb = (int) $param;
    $files = normalize_image_input($input[$field]);

    if (! array_key_exists('size', $files)) {
        return false;
    }

    foreach ($files['tmp_name'] as $file) {
        $size_bytes = filesize($file);
        $size_kb = intdiv((int) $size_bytes, BYTES_IN_KB);
        if ($size_kb > $limit_kb) {
            return false;
        }
    }

    return true;
}, 'The {0} size must not exceed {1} kb');


$validation->addValidator('image', function ($field, $input, $param) {
    if (! isset($input[$field]['error']) || ! isset($input[$field]['tmp_name'])) {
        return false;
    }

    $data = normalize_image_input($input[$field]);

    foreach ($data['error'] as $error) {
        if ($error !== UPLOAD_ERR_OK) {
            return false;
        }
    }

    if (empty($param)) {
        return false;
    }

    $allowed_mime_types = explode('*', $param);
    $filters = [];

    foreach ($allowed_mime_types as $mime) {
        $filters[$mime] = "image/{$mime}";
    }

    // $dangerous = ['<?php', '<?=', '<%', '<script', 'eval(', 'base64_decode(', 'exec(', 'system(', 'passthru(', 'shell_exec(',]; // pint ignore/line


    foreach ($data['tmp_name'] as $file) {
        if (! is_uploaded_file($file)) {
            return false;
        }
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if (! array_search(
            $finfo->file($file),
            $filters,
            true
        )) {
            return false;
        }

        // $contents = file_get_contents($file);

        // if ($contents === false) {
        //     return false;
        // }

        // foreach ($dangerous as $pattern) {
        //     if (stripos($contents, $pattern) !== false) {
        //         return false;
        //     }
        // }
    }

    return true;
}, 'The {0} image must be of the following formats: {1}');

$validation->addFilter('nullsafe', function ($value) {
    return $value ?? [];
});
