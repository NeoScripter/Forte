<?php

declare(strict_types=1);

use DB\SQL;

require APP_DIR . '/vendor/autoload.php';

// foreach (glob(APP_DIR . '/app/Helpers/*.php') as $filename) {
//     require_once $filename;
// }

$hive = Base::instance();

$hive->set('AUTOLOAD', APP_DIR . '/app/;');
$hive->set('UI', APP_DIR . '/ui/views/');
$hive->set('LOGS', APP_DIR . '/storage/logs/');
define('UPLOAD_DIR', APP_DIR . '/public/storage/uploads/');

$hive->config(APP_DIR . '/config/config.ini');

$is_local = $hive->get('app_debug');
$hive->set('DEBUG', $is_local ? 3 : 0);

require APP_DIR . '/config/error_handler.php';
require APP_DIR . '/app/Helpers/functions.php';

$path = APP_DIR . '/db/database.sqlite';

$db = new SQL(
    "sqlite:$path"
);

$hive->set('DB', $db);

$flash = \Flash::instance();
$hive->set('FLASH', $flash);

require APP_DIR . '/config/validation.php';

// $hive->route('GET /api/seed [cli]', 'seeders\Seeder->run');
// $hive->route('GET /@action [cli]', 'Http\Controllers\ConsoleController->@action');

$hive->run();
