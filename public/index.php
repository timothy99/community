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

// Define ENVIRONMENT // mng_ip 테이블의 IP 목록 기반으로 환경 모드를 결정
if (! defined('ENVIRONMENT')) {
    if (PHP_SAPI === 'cli') {
        define('ENVIRONMENT', 'development');
    } else {
        $ip_addr  = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $env_mode = 'production';

        try {
            $db_host = getenv('database.default.hostname') ?: 'localhost';
            $db_port = getenv('database.default.port') ?: 3306;
            $db_name = getenv('database.default.database') ?: '';
            $db_user = getenv('database.default.username') ?: '';
            $db_pass = getenv('database.default.password') ?: '';

            $pdo = new PDO(
                "mysql:host={$db_host};port={$db_port};dbname={$db_name};charset=utf8mb4",
                $db_user,
                $db_pass,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_TIMEOUT => 3]
            );
            $stmt = $pdo->prepare(
                "SELECT environment_mode FROM mng_ip WHERE ip = ? AND del_yn = 'N' LIMIT 1"
            );
            $stmt->execute([$ip_addr]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row !== false) {
                $env_mode = $row['environment_mode'];
            }
        } catch (Exception $e) {
            // DB 연결 실패 시 production으로 fallback
        }

        define('ENVIRONMENT', $env_mode);
    }
}

exit(Boot::bootWeb($paths));
