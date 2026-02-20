# community
코드이그나이터 프레임워크로 개발된 커뮤니티 기능을 하는 프로그램

# 기본설정

## composer 를 이용한 설치
### PHP 8.3+ 최신버전 설치
```
composer create-project codeigniter4/appstarter 프로젝트명
cd 프로젝트명
composer require phpoffice/phpspreadsheet
```

### Common.php 에 intl 을 우회하기 위한 polyfill 작성
- php-intl 확장이 설치 되어 있지 않아 오류가 날 경우 작성. 보통은 필요하지 않다.
```
// Temporary polyfill for Locale class when intl extension is not available
if (!class_exists('Locale')) {
    class Locale
    {
        public const DEFAULT_LOCALE = 'ko';

        public static function acceptFromHttp($header)
        {
            return self::DEFAULT_LOCALE;
        }

        public static function getDefault()
        {
            return self::DEFAULT_LOCALE;
        }

        public static function setDefault($locale)
        {
            return true;
        }
    }
}
```

### 웹루트를 public 으로 잡지 못할 경우
- 일부 호스팅의 경우 루트 아래 public 을 웹 루트로 잡지 못하는 경우가 있다.
- 루트에 .htaccess 파일을 작성한다. /public/.htaccess 외에 추가로 작성
```
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>

<FilesMatch "^\.">
    Require all denied
    Satisfy All
</FilesMatch>
```

### App.php 설정
* index.php가 주소줄에 보이는것을 방지하기 위해 40번 라인의 public $indexPage 를 널로 변경
```
public $indexPage = 'index.php'     ->      public $indexPage = ''
```

### BaseController.php 헬퍼 추가
- 헬퍼는 initController함수 아래에 추가.
```
$this->helpers = ['alert', 'array', 'board', 'curl', 'logging', 'privacy', 'session', 'text', 'view'];
```

### Constants.php 설정
* 업로드 경로를 설정해줍니다.
```
defined("UPLOADPATH") || define("UPLOADPATH", WRITEPATH."uploads/"); // 업로드 경로
```

### 초기 화면 파일 삭제
```
rm -f ./app/Controllers/Home.php
rm -f ./app/Views/welcome_message.php
```
### Events.php 설정
- DB쿼리 기능 추가
- logging_helper.php 추가 필요.
```
// CI에서 기본적 DB이벤트(select등 모두 포함)가 발생되었을때의 로깅
Events::on("DBQuery", function () {
    logModifyQuery(); // 쿼리 로깅
});
```
- 권한관리 모델 추가
- AuthorityModel.php 추가 필요
- session_helpoer.php 추가 필요
```
/*
    사용자 추가 이벤트
    1. 세션을 찾아서 세션에 언어 설정이 없으면 한국어로 기본 설정해서 데이터 넣기
*/
Events::on("post_controller_constructor", function () {
    $authority_model = new AuthorityModel();

    getUserSession() ?? setBaseSession(); // 사용자 세션이 없다면 기본 세션 생성

    $authority_model->setPreviousUrl(); // 이전 url 세션에 저장

    $request = \Config\Services::request();
    $segments = $request->getUri()->getSegments(); // segments 확인
    $authority_model->checkAuthority($segments); // 권한 체크
});
```

### env 설정
1. env파일을 .env로 복사합니다.
2. DB설정을 합니다.
3. 설명에 따라 기타 필요한 사항들을 입력합니다.

### sftp 설정
```
{
    "name": "별칭",
    "host": "호스트",
    "protocol": "sftp",
    "port": 22,
    "username": "아이디",
    "password": "암호",
    "remotePath": "경로",
    "ignore": [
        ".vscode",
        ".git",
        ".DS_Store",
        ".history",
        "*.sql",
        "*.log",
        "*.md",
        "*env*",
        "*.zip",
        ".gitignore",
        "*cs-fixer*",
        "builds",
        "vendor",
        "tests",
        "LICENSE",
        "composer*",
        "phpunit.xml.dist"
    ],
    "downloadOnOpen": false,
    "uploadOnSave": true,
    "useTempFile": false,
    "openSsh": false
}
```
### Routes.php 설정
- 초기 welcome_message 는 삭제 하였으므로 초기 route를 아래로 설정한다.
```
$routes->get('/', 'User\Home::index');
$routes->get('/home', 'User\Home::index');
$routes->get('/home/main', 'User\Home::main');
```

### index.php 설정
- public/index.php
```
// Load .env first
$env_directory = $paths->envDirectory ?? $paths->appDirectory . '/../';
require $paths->systemDirectory . '/Config/DotEnv.php';
(new CodeIgniter\Config\DotEnv($env_directory))->load();

// 현재 서버 IP 확인
$ip_addr = $_SERVER['REMOTE_ADDR'];

// .env의 development.ip 확인
$development_ip_addr = explode("||", $_ENV['development.ip']);

// IP 매칭 여부로 ENVIRONMENT 결정
if (in_array($ip_addr, $development_ip_addr, true)) {
    define('ENVIRONMENT', 'development');
} else {
    define('ENVIRONMENT', 'production');
}
```

## 디렉토리 구성 및 일부 필수 수정해야할 파일 목록
* 위 내용을 정리해둔 것으로 이중 헬퍼는 설명된것 외에 추가로 더 있습니다. 헬퍼등 각 파일에 대한 설명은 파일 내의 주석을 확인하시기 바랍니다.
```
+-- Config                      # 설정파일
|   └-- App.php                 # App 기본설정, 사이트별 설정은 여기에서 하지 말고 .env파일에서 설정하도록 한다.
|   └-- Constants.php           # 시스템 기본 상수 설정
|   └-- Events.php              # 프로그램이 최초 작동할때 실행되어야 할 함수들
|   └-- Routes.php              # 프로그램이 실행될때의 기능 get, post, cli등으로 구분된다.
+-- Helpers
|   └-- logging_helper.php      # 쿼리 로깅등을 지원해 주는 헬퍼
|   └-- session_helper.php      # 세션을 편리하게 사용하기 위한 세션 헬퍼
+-- Controller
|   └-- Console                 # 관리자 컨트롤러
|   └-- User                    # 사용자 컨트롤러
+-- Model
|   └-- Console                 # 관리자 모델
|   └-- User                    # 사용자 모델
+-- Views
|   └-- Console                 # 관리자 뷰
|   └-- User                    # 사용자 뷰
+-- env                         # 각종환경설정 가이드 파일
+-- README.md                   # 지금보고 있는 파일
```
