<?php

namespace Http\Models;

use DB\Cortex;
use DB\SQL\Mapper;
use DB\SQL\Schema;

class User extends Cortex
{
    protected $fieldConf = [
        'name' => [
            'type' => Schema::DT_VARCHAR256,
            'nullable' => false,
        ],
        'email' => [
            'type' => Schema::DT_VARCHAR128,
            'index' => true,
            'unique' => true,
            'nullable' => false,
        ],
        'logo' => [
            'type' => Schema::DT_INT,
        ],
        'role' => [
            'type' => Schema::DT_TINYINT,
            'default' => 1,
        ],
    ];
    protected $db = 'DB', $table = 'users';

    public function set_logo(Image|int|null $img) {
        if (is_int($img) || is_null($img)) {
            return $img;
        }

        return $img?->id ?? null;
    }

    public function get_logo(?int $id) {
        if (! $id) {
            return null;
        }

        $img = new Image();
        $img->load(['id = ?', $id]);

        if ($img->dry()) {
            return null;
        }

        return $img;
    }

    public function get_img() {
        return 'foo';
    }
}
