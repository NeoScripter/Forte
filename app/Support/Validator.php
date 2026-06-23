<?php

declare(strict_types=1);

namespace Support;

use Exception;
use finfo;

const BYTES_IN_KB = 1024;

class Validator
{
    private array $errors = [];

    public static function instance(): static
    {
        return new static();
    }

    /**
     * @param array $rules  ['field' => ['filter' => ..., 'validate' => ..., 'post_filter' => ...]]
     * @param array $data   Passed by reference so filters mutate it in place
     */
    public function validate(array $rules, array &$data): bool
    {
        $this->errors = [];

        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;

            if (isset($rule['filter'])) {
                $data[$field] = $this->apply('filter', $rule['filter'], $value, $field, $data);
                $value = $data[$field];
            }

            if (isset($rule['validate'])) {
                $field_errors = $this->apply('validate', $rule['validate'], $value, $field, $data);

                if (! empty(array_filter($field_errors))) {
                    $this->errors[$field] = array_first(array_filter($field_errors));
                    return false;
                }
            }

            if (isset($rule['post_filter'])) {
                $data[$field] = $this->apply('post_filter', $rule['post_filter'], $data[$field], $field, $data);
            }
        }

        return empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    // ── Pipeline ──────────────────────────────────────────────────────────────

    private function apply(string $type, string $pipe, mixed $value, string $field, array &$data): mixed
    {
        $prefix = match ($type) {
            'filter'      => 'filter_',
            'post_filter' => 'post_filter_',
            'validate'    => 'validate_',
        };

        $results = [];

        foreach (explode('|', $pipe) as $segment) {
            [$name, $args] = $this->parse($segment);
            $method = $prefix . $name;

            if (! method_exists($this, $method)) {
                throw new \InvalidArgumentException("Unknown {$type}: {$name}");
            }

            $result = $this->$method($value, $field, $data, ...$args);

            if ($type === 'validate') {
                $results[] = $result;

                // Return early if there is a validation error
                if (! empty($result)) break;
            } else {
                $value = $result;
            }
        }

        return $type === 'validate' ? $results : $value;
    }

    // ── Parsing ───────────────────────────────────────────────────────────────

    /**
     * "max_len,200"      → ['max_len', ['200']]
     * "image,webp.jpg"   → ['image',   ['webp', 'jpg']]
     * "required"         → ['required', []]
     */
    private function parse(string $segment): array
    {
        $parts = explode(':', $segment, 2);
        $name  = trim($parts[0]);
        $args  = isset($parts[1]) ? explode(',', $parts[1]) : [];

        return [$name, $args];
    }

    // ── Filters ───────────────────────────────────────────────────────────────
    //
    protected function filter_trim_spaces(mixed $value, string $field, array $data): mixed
    {
        return is_string($value) ? preg_replace('/\s+/', ' ', $value) : $value;
    }

    protected function filter_trim(mixed $value, string $field, array $data): mixed
    {
        return is_string($value) ? trim($value) : $value;
    }

    protected function filter_capitalize(mixed $value, string $field, array $data): mixed
    {
        return is_string($value) ? ucwords(strtolower($value)) : $value;
    }

    protected function filter_uppercase(mixed $value, string $field, array $data): mixed
    {
        return is_string($value) ? strtoupper($value) : $value;
    }

    protected function filter_lowercase(mixed $value, string $field, array $data): mixed
    {
        return is_string($value) ? strtolower($value) : $value;
    }

    protected function filter_boolean(mixed $value, string $field, array $data): bool
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    protected function filter_int(mixed $value, string $field, array $data): int
    {
        return (int) $value;
    }

    protected function filter_float(mixed $value, string $field, array $data): float
    {
        return (float) $value;
    }

    protected function filter_strip_tags(mixed $value, string $field, array $data): mixed
    {
        return is_string($value) ? strip_tags($value) : $value;
    }

    protected function filter_slug(mixed $value, string $field, array $data): mixed
    {
        return is_string($value)
            ? strtolower(preg_replace('/[^a-z0-9]+/i', '-', trim($value)))
            : $value;
    }

    protected function filter_file(mixed $props, string $field, array $data): mixed
    {
        if (! is_array($props) || ! array_key_exists('error', $props) || ! array_key_exists('tmp_name', $props)) {
            return $props;
        }

        $num_files = is_array($props['error'])
            ? count($props['error'])
            : 1;

        $files = array_fill(0, $num_files, []);

        foreach ($props as $prop => $field) {
            if (is_array($field)) {
                foreach ($field as $idx => $val) {
                    $files[$idx][$prop] = $val;
                }
            } else {
                $files[0][$prop] = $field;
            }
        }
        return $files;
    }

    // ── Post Filters ──────────────────────────────────────────────────────────

    protected function post_filter_trim(mixed $value, string $field, array $data): mixed
    {
        return is_string($value) ? trim($value) : $value;
    }

    protected function post_filter_uppercase(mixed $value, string $field, array $data): mixed
    {
        return is_string($value) ? strtoupper($value) : $value;
    }

    protected function post_filter_lowercase(mixed $value, string $field, array $data): mixed
    {
        return is_string($value) ? strtolower($value) : $value;
    }

    protected function post_filter_truncate(mixed $value, string $field, array $data, string $length = '100'): mixed
    {
        return is_string($value) ? substr($value, 0, (int) $length) : $value;
    }

    // ── Validators ────────────────────────────────────────────────────────────

    protected function validate_required(mixed $value, string $field, array $data): ?string
    {
        $is_empty = ($value === null || $value === '');

        if ($is_empty) {
            return $this->label($field) . ' is required.';
        }

        return null;
    }

    protected function validate_min_len(mixed $value, string $field, array $data, string $min): ?string
    {
        $min_length = (int) $min;
        $is_too_short = is_string($value) && strlen($value) < $min_length;

        if ($is_too_short) {
            return $this->label($field) . " must be at least {$min} characters.";
        }

        return null;
    }

    protected function validate_max_len(mixed $value, string $field, array $data, string $max): ?string
    {
        $max_length = (int) $max;
        $is_too_long = is_string($value) && strlen($value) > $max_length;

        if ($is_too_long) {
            return $this->label($field) . " must not exceed {$max} characters.";
        }

        return null;
    }

    protected function validate_min(mixed $value, string $field, array $data, string $min): ?string
    {
        $is_below_min = (float) $value < (float) $min;

        if ($is_below_min) {
            return $this->label($field) . " must be at least {$min}.";
        }

        return null;
    }

    protected function validate_max(mixed $value, string $field, array $data, string $max): ?string
    {
        $is_above_max = (float) $value > (float) $max;

        if ($is_above_max) {
            return $this->label($field) . " must not exceed {$max}.";
        }

        return null;
    }

    protected function validate_boolean(mixed $value, string $field, array $data): ?string
    {
        $accepted_boolean_values = [true, false, 0, 1, '0', '1'];
        $is_valid = in_array($value, $accepted_boolean_values, strict: true);

        if (! $is_valid) {
            return $this->label($field) . ' must be a boolean.';
        }

        return null;
    }

    protected function validate_email(mixed $value, string $field, array $data): ?string
    {
        $is_valid = filter_var($value, FILTER_VALIDATE_EMAIL) !== false;

        if (!$is_valid) {
            return $this->label($field) . ' must be a valid email address.';
        }

        return null;
    }

    protected function validate_numeric(mixed $value, string $field, array $data): ?string
    {
        if (! is_numeric($value)) {
            return $this->label($field) . ' must be numeric.';
        }

        return null;
    }

    protected function validate_integer(mixed $value, string $field, array $data): ?string
    {
        $is_valid = filter_var($value, FILTER_VALIDATE_INT) !== false;

        if (!$is_valid) {
            return $this->label($field) . ' must be an integer.';
        }

        return null;
    }

    protected function validate_url(mixed $value, string $field, array $data): ?string
    {
        $is_valid = filter_var($value, FILTER_VALIDATE_URL) !== false;

        if (!$is_valid) {
            return $this->label($field) . ' must be a valid URL.';
        }

        return null;
    }

    protected function validate_exists(mixed $value, string $field, array $data, string ...$args): ?string
    {
        if (count($args) !== 2) {
            throw new Exception('Wrong syntax for the exists validation rule');
        }

        [$db_table, $db_col] = $args;

        $db = \Base::instance()->get('DB');

        $res = $db->exec("
            SELECT EXISTS (
                SELECT 1 FROM $db_table WHERE $db_col = ?
            ) AS exists
        ", [$value]);

        if (empty($res) || !$res[0]['exists']) {
            return 'We could not find an existing record for ' . $this->label($field);
        }

        return null;
    }

    protected function validate_unique(mixed $value, string $field, array $data, string ...$args): ?string
    {
        if (count($args) < 2) {
            throw new Exception('Wrong syntax for the unique validation rule');
        }

        [$db_table, $db_col] = $args;
        $col_parts = explode('*', $db_col);
        $db_col = $col_parts[0];

        $db = \Base::instance()->get('DB');

        $args = [$value];
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
            return 'The ' . $this->label($field) . ' already exists';
        }

        return null;
    }

    protected function validate_matches(mixed $value, string $field, array $data, string $other): ?string
    {
        return (($data[$other] ?? null) !== $value)
            ? $this->label($field) . " must match {$other}."
            : null;
    }

    protected function validate_max_size(mixed $files, string $field, array $data, string $max_kb): ?string
    {
        if (empty($files)) {
            return null;
        }

        if (empty($max_kb)) {
            return 'No max size was configured for ' . $this->label($field) . '.';
        }

        if (
            !is_array($files) ||
            array_any($files, fn($file) => !array_key_exists('size', $file))
        ) {
            return 'Malformed file input for ' . $this->label($field) . '.';
        }

        $max_size_kb = (int) $max_kb;

        foreach ($files as $file) {
            $file_size_kb = intdiv((int) $file['size'], BYTES_IN_KB);

            if ($file_size_kb > $max_size_kb) {
                return $this->label($field) . ": {$file['name']} must not exceed {$max_kb}KB.";
            }
        }

        return null;
    }

    protected function validate_image(mixed $files, string $field, array $data, string ...$allowed_types): ?string
    {
        if (empty($files)) {
            return null;
        }

        if (empty($allowed_types)) {
            return 'No allowed image types were configured for ' . $this->label($field) . '.';
        }

        if (
            ! is_array($files) ||
            array_any($files, fn($arr) => ! array_key_exists('name', $arr)) ||
            array_any($files, fn($arr) => ! array_key_exists('tmp_name', $arr)) ||
            array_any($files, fn($arr) => ! array_key_exists('error', $arr)) ||
            array_any($files, fn($arr) => ! array_key_exists('full_path', $arr))
        ) {
            return 'Malformed file input for ' . $this->label($field) . '.';
        }

        $allowed_mime_types = array_map(fn($type) => "image/{$type}", $allowed_types);

        $dangerous_patterns = ['<?php', '<?=', '<%', '<script', 'eval(', 'base64_decode(', 'exec(', 'system(', 'passthru(', 'shell_exec('];

        foreach ($files as $file) {
            $file_name   = $file['name'];
            $field_label = $this->label($field);

            $upload_error = $file['error'];
            if ($upload_error !== UPLOAD_ERR_OK) {
                return match ($upload_error) {
                    UPLOAD_ERR_INI_SIZE   => "{$file_name}: the file exceeds the server's maximum upload size.",
                    UPLOAD_ERR_FORM_SIZE  => "{$file_name}: the file exceeds the form's maximum upload size.",
                    UPLOAD_ERR_PARTIAL    => "{$file_name}: the file was only partially uploaded.",
                    UPLOAD_ERR_NO_FILE    => "{$field_label}: no file was uploaded.",
                    UPLOAD_ERR_NO_TMP_DIR => "{$file_name}: the server is missing a temporary upload folder.",
                    UPLOAD_ERR_CANT_WRITE => "{$file_name}: the server failed to save the file to disk.",
                    UPLOAD_ERR_EXTENSION  => "{$file_name}: the upload was blocked by a server extension.",
                    default               => "{$file_name}: an unknown upload error occurred.",
                };
            }

            if (!is_uploaded_file($file['tmp_name'])) {
                return "{$file_name}: the file must be uploaded through the web form.";
            }

            $finfo         = new finfo(FILEINFO_MIME_TYPE);
            $detected_mime = $finfo->file($file['tmp_name']);
            $mime_is_allowed = in_array($detected_mime, $allowed_mime_types, strict: true);

            if (!$mime_is_allowed) {
                $allowed_list = implode(', ', $allowed_types);
                return "{$field_label}: {$file_name} must be one of the following types: {$allowed_list}.";
            }

            $contents = file_get_contents($file['tmp_name']);
            if ($contents === false) {
                return "{$file_name}: the file could not be read for validation.";
            }

            $file_header = substr($contents, 0, 512);
            foreach ($dangerous_patterns as $pattern) {
                if (stripos($file_header, $pattern) !== false) {
                    return "{$file_name}: the file contains invalid or unsafe content.";
                }
            }
        }

        return null;
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function label(string $field): string
    {
        return ucfirst(str_replace('_', ' ', $field));
    }
}
