<?php

namespace Http\Models;

use DB\Cortex;
use DB\SQL\Schema;
use Exception;
use PDOException;
use Support\ErrorHandler;
use Support\ImageHandler;

class Image extends Cortex
{
    protected $fieldConf = [
        'src' => [
            'type' => Schema::DT_VARCHAR256,
            'nullable' => false,
        ],
        'variant' => [
            'type' => Schema::DT_VARCHAR128,
            'index' => true,
            'nullable' => false,
            'default' => 'image',
        ],
        'alt' => [
            'type' => Schema::DT_TEXT,
            'nullable' => false,
        ],
        // 'imageable_type' => [
        //     'type' => Schema::DT_VARCHAR128,
        //     'nullable' => false,
        // ],
        // 'imageable_id' => [
        //     'type' => Schema::DT_INT,
        //     'index' => true,
        //     'nullable' => false,
        // ],
        // 'user' => [
        //     'has-many' => [User::class, 'img']
        // ]
    ];
    protected $db = 'DB', $table = 'images';
}
