<?php

declare(strict_types=1);

namespace Http;

use Support\Validator;

abstract class Request
{
    protected \Base $hive;
    protected array $data = [];

    public function __construct(\Base $hive)
    {
        $this->hive = $hive;
        $this->data = array_merge($hive->POST, $hive->FILES);
    }

    abstract public function rules(): array;

    protected function prepare_data(): array
    {
        return $this->data;
    }

    /**
     * Runs validation. On failure, calls onFailure() and returns false.
     * On success, whitelists data and returns true.
     */
    public function validate(): bool
    {
        check_csrf($this->hive->POST);

        $rules   = $this->rules();
        $data    = $this->prepare_data();
        $v       = Validator::instance();

        if (! $v->validate($rules, $data)) {
            $this->flash_errors($v->errors());
            $this->on_failure();
            return false;
        }

        $this->data = array_intersect_key($data, $rules);
        return true;
    }

    protected function on_failure(): void
    {
        $this->hive->reroute($this->redirect_on_failure());
    }

    protected function redirect_on_failure(): string
    {
        return '/';
    }

    public function all(): array
    {
        return $this->data;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }

    protected function flash_errors(array $errors)
    {
        foreach ($errors as $key => $text) {
            \Base::instance()->set('errors.' . $key, $text);

            $flash = \Flash::instance();

            [$field,] = explode('.', $key);
            if (! $flash->hasKey('errors.' . $field)) {
                $flash->setKey('errors.' . $field, $text);
            }
        }
    }
}
