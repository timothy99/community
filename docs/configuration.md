# 환경 설정

## .env 설정

1. `env` 파일을 `.env` 로 복사합니다.
2. DB 설정을 합니다.
3. 설명에 따라 기타 필요한 사항들을 입력합니다.

```ini
app.baseURL = "https를 포함한 도메인 전체"

session.driver = 'CodeIgniter\Session\Handlers\FileHandler'

app.appTimezone = "Asia/Seoul"

logger.threshold = 4

encryption.key = "암호화키"
encryption.iv = "암호화iv"
encryption.way = "aes-256-gcm"

database.default.hostname = "DB호스트"
database.default.database = "DB명"
database.default.username = "DB아이디"
database.default.password = "암호"
database.default.DBDriver = "MySQLi"
database.default.DBPrefix = "mng_"
database.default.port = 3306

development.ip = "아이피1||아이피2||아이피3"
```

---

## App.php 설정

`index.php` 가 주소줄에 보이는 것을 방지하기 위해 `public $indexPage` 를 빈 값으로 변경.

```php
// 변경 전
public $indexPage = 'index.php';

// 변경 후
public $indexPage = '';
```

---

## BaseController.php 헬퍼 추가

헬퍼는 `initController` 함수 아래에 추가.

```php
$this->helpers = array();
$this->helpers[] = 'alert';
$this->helpers[] = 'array';
$this->helpers[] = 'board';
$this->helpers[] = 'curl';
$this->helpers[] = 'logging';
$this->helpers[] = 'privacy';
$this->helpers[] = 'session';
$this->helpers[] = 'text';
$this->helpers[] = 'view';
$this->helpers[] = 'authority';
$this->helpers[] = 'security';
$this->helpers[] = 'email';
$this->helpers[] = 'paging';
$this->helpers[] = 'date';
```

---

## Constants.php 설정

업로드 경로를 설정해줍니다.

```php
defined("UPLOADPATH") || define("UPLOADPATH", WRITEPATH."uploads/"); // 업로드 경로
```

---

## Events.php 설정

DB 쿼리 로깅 기능 추가 — `logging_helper.php` 추가 필요.

```php
// CI에서 기본적 DB이벤트(select등 모두 포함)가 발생되었을때의 로깅
Events::on("DBQuery", function () {
    logModifyQuery(); // 쿼리 로깅
});
```

권한관리 및 세션 초기화 이벤트 추가.

```php
/*
    사용자 추가 이벤트
    1. 세션을 찾아서 세션에 언어 설정이 없으면 한국어로 기본 설정해서 데이터 넣기
*/
Events::on('post_controller_constructor', function () {
    getUserSession() ?? setBaseSession(); // 사용자 세션이 없다면 기본 세션 생성
    setPreviousUrl();                     // 이전 url 세션에 저장
    checkAuthority(service('request')->getUri()->getSegments()); // 권한 체크
});
```

---

## Routes.php 설정

초기 `welcome_message` 는 삭제하였으므로 초기 route 를 아래로 설정한다.

```php
$routes->get('/', 'User\Home::index');
$routes->get('/home', 'User\Home::index');
$routes->get('/home/main', 'User\Home::main');
```

---

## index.php 설정

`public/index.php` — Boot.php 파일 아래, `exit` 위에 위치 (57번 라인 이후로)

```php
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
```
