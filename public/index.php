<?php

use CodeIgniter\Boot;
use Config\Paths;

/*
 *---------------------------------------------------------------
 * CHECK PHP VERSION
 *---------------------------------------------------------------
 */

$minPhpVersion = '8.2'; // If you update this, don't forget to update `spark`.
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    $message = sprintf(
        'Your PHP version must be %s or higher to run CodeIgniter. Current version: %s',
        $minPhpVersion,
        PHP_VERSION,
    );

    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo $message;

    exit(1);
}

/*
 *---------------------------------------------------------------
 * SET THE CURRENT DIRECTORY
 *---------------------------------------------------------------
 */

// Path to the front controller (this file)
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Ensure the current directory is pointing to the front controller's directory
if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

/*
 *---------------------------------------------------------------
 * BOOTSTRAP THE APPLICATION
 *---------------------------------------------------------------
 * This process sets up the path constants, loads and registers
 * our autoloader, along with Composer's, loads our constants
 * and fires up an environment-specific bootstrapping.
 */

// LOAD OUR PATHS CONFIG FILE
// This is the line that might need to be changed, depending on your folder structure.
require FCPATH . '../app/Config/Paths.php';
// ^^^ Change this line if you move your application folder

$paths = new Paths();

// LOAD THE FRAMEWORK BOOTSTRAP FILE
require $paths->systemDirectory . '/Boot.php';

// Load .env first
$env_directory = $paths->envDirectory ?? $paths->appDirectory . '/../';
require $paths->systemDirectory . '/Config/DotEnv.php';
(new CodeIgniter\Config\DotEnv($env_directory))->load();

// Define ENVIRONMENT // 정해둔 ip인 경우 development 모드로 작동
if (! defined('ENVIRONMENT')) {
    // CLI 환경에서는 development 모드로 설정 (로깅을 위해)
    if (PHP_SAPI === 'cli') {
        define('ENVIRONMENT', 'development');
    } else {
        $ip_arr = explode("||", getenv("development.ip"));
        $ip_addr = $_SERVER["REMOTE_ADDR"] ?? '127.0.0.1';
        if (in_array($ip_addr, $ip_arr)) {
            define('ENVIRONMENT', 'development');
        } else {
            define('ENVIRONMENT', 'production');
        }
    }
}

exit(Boot::bootWeb($paths));
