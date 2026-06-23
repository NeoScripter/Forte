<?php

use DB\SQL;

$path = APP_DIR . '/db/database.sqlite';

$db = new SQL(
    "sqlite:$path"
);

$hive->set('DB', $db);
