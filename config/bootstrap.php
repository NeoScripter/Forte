<?php

declare(strict_types=1);

require APP_DIR . '/vendor/autoload.php';

$hive = Base::instance();

$hive->set('AUTOLOAD', APP_DIR . '/app/;');
$hive->set('UI', APP_DIR . '/ui/views/');
$hive->set('LOGS', APP_DIR . '/storage/logs/');
define('UPLOAD_DIR', APP_DIR . '/public/storage/uploads/');

$hive->config(APP_DIR . '/config/variables.ini');
$hive->config(APP_DIR . '/config/routes.ini');

$is_local = $hive->get('app_debug');
$hive->set('DEBUG', $is_local ? 3 : 0);

require APP_DIR . '/config/exception_config.php';
require APP_DIR . '/config/database.php';

$flash = \Flash::instance();
$hive->set('FLASH', $flash);

require APP_DIR . '/config/validation.php';

$hive->run();
