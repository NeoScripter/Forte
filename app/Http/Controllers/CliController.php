<?php

namespace Http\Controllers;

use Http\Models\User;

const SCREEN_WIDTH = 152;
const METHOD_WIDTH = 12;

class CliController
{
    function routes(\Base $hive)
    {
        $routes = $hive->get('ROUTES');

        foreach ($routes as $url => $methods) {
            foreach ($methods as $route) {
                foreach ($route as $method => $meta) {
                    [$handler, $name] = [$meta[0], $meta[3]];
                    $handler = str_replace('Http\Controllers\\', '', $handler);

                    $color = match (trim($method)) {
                        'GET' => 'info',
                        'DELETE' => 'error',
                        default => 'warning',
                    };

                    $prefix = str_pad($method, METHOD_WIDTH) . ' ';
                    $suffix      = trim($name) !== '' ? " {$name} > {$handler}" : " {$handler}";
                    $url = str_pad($url, SCREEN_WIDTH - (METHOD_WIDTH + strlen($suffix)), '.');
                    echo cli_color($prefix, $color) . $url . $suffix . "\n";
                }
            }
        }
    }

    function migrate(\Base $hive)
    {
        $files = glob(APP_DIR . '/db/migrations/*');
        sort($files);

        $pdo = $hive->get('DB')->pdo();

        foreach ($files as $file) {
            $sql = file_get_contents($file);
            $pdo->exec($sql);
        }

        echo "Migration completed.\n";
    }

    function drop(\Base $hive)
    {
        // try {
        //     \Support\Auth::clear();           // clears SESSION.user in hive + singleton
        //     $hive->clear('SESSION');            // clears entire SESSION hive
        //     print_r($hive->COOKIE);

        //     \Cache::instance()->reset();
        // } catch (Throwable $e) {
        //     ErrorHandler::handle($e);
        // }

        $tables = get_db_table_names();

        foreach ($tables as $table) {
            if (str_contains($table, '_view')) {
                $hive->get('DB')->exec("DROP VIEW IF EXISTS $table CASCADE;");
            } else {
                $hive->get('DB')->exec("DROP TABLE IF EXISTS $table CASCADE;");
            }
        }

        delete_files_recursive(
            glob(APP_DIR . '/public/storage/uploads' . '/*')
        );

        echo "All tables deleted.\n";
    }

    function fresh(\Base $hive)
    {
        $this->drop($hive);
        $this->migrate($hive);
    }

    function link()
    {
        echo APP_DIR . PHP_EOL;
        $storage = APP_DIR  . '/storage/public';
        $link    = APP_DIR  . '/public/storage';

        if (file_exists($link)) {
            echo "Link already exists at {$link}" . PHP_EOL;
            return;
        }

        echo "Storage: {$storage}" . PHP_EOL;
        echo "Link: {$link}" . PHP_EOL;

        if (!is_dir($storage)) {
            mkdir($storage, 0755, true);
            echo "Created storage directory: {$storage}" . PHP_EOL;
        }

        if (symlink($storage, $link)) {
            echo "Symlink created: {$link} -> {$storage}" . PHP_EOL;
        } else {
            echo "Failed to create symlink" . PHP_EOL;
        }
    }

    function create_user(\Base $hive)
    {
        $name = $hive->get('GET.name');
        $email = $hive->get('GET.email');
        $password = $hive->get('GET.password');

        if (empty($name) || empty($email) || strlen($password) < 8) {
            cli_echo("❌ Usage: php index.php create_user --name=John --email=john@example.com --password=mypassword123", 'error');
            cli_echo("   Password must be at least 8 characters", 'error');
            exit(1);
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        if ($hash === false) {
            cli_echo("❌ Failed to hash password", 'error');
            exit(1);
        }

        try {
            $user = new User();
            $user->copyFrom(['name' => $name, 'email' => $email, 'password' => $hash]);
            $user->save();

            cli_echo("User created successfully!");
            cli_echo("   ID: {$user->id}");
            cli_echo("   Name: $name");
            cli_echo("   Email: $email");
        } catch (\Exception $e) {
            cli_echo("❌ Failed: {$e->getMessage()}", 'error');
            exit(1);
        }
    }

    function update_password(\Base $hive)
    {
        $email = $hive->get('GET.email');
        $new_password = $hive->get('GET.password');

        if (empty($email) || strlen($new_password) < 8) {
            cli_echo("❌ Usage: php index.php reset_password --email=john@example.com --password=mypassword123", 'error');
            cli_echo("   Password must be at least 8 characters", 'error');
            exit(1);
        }

        $hash = password_hash($new_password, PASSWORD_DEFAULT);

        if ($hash === false) {
            cli_echo("❌ Failed to hash password", 'error');
            exit(1);
        }

        try {
            $user = new User();
            $user->load(['email=?', $email]);
            $user->copyFrom(['password' => $hash]);
            $user->save();

            cli_echo("Password updated successfully!");
            cli_echo("   ID: {$user->id}");
            cli_echo("   Name: $user->name");
            cli_echo("   Email: $email");
        } catch (\Exception $e) {
            cli_echo("❌ Failed: {$e->getMessage()}", 'error');
            exit(1);
        }
    }
}
