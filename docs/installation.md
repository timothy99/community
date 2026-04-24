# 설치 가이드

## composer 를 이용한 설치

### PHP 7.4에서
```bash
# 7.4에서 구동이 가능한 최대 버전 설치
composer create-project codeigniter4/appstarter 프로젝트 --no-install '4.4.8'

# 디렉토리 이동
cd 프로젝트

# php환경을 7.4.25으로 변경
composer config platform.php 7.4.25

composer require codeigniter4/framework:4.4.8
composer require phpoffice/phpspreadsheet:1.29.0
```

### PHP 8.3+ 최신버전 설치
```bash
composer create-project codeigniter4/appstarter 프로젝트명
cd 프로젝트명
composer require phpoffice/phpspreadsheet
```

---

## Common.php 에 intl polyfill 작성

php-intl 확장이 설치 되어 있지 않아 오류가 날 경우 작성. 보통은 필요하지 않다.

```php
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

---

## 웹루트를 public 으로 잡지 못할 경우

일부 호스팅의 경우 루트 아래 public 을 웹 루트로 잡지 못하는 경우가 있다.  
루트에 `.htaccess` 파일을 작성한다. `/public/.htaccess` 외에 추가로 작성.

```apacheconf
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>

<FilesMatch "^\.">
    Require all denied
    Satisfy All
</FilesMatch>
```

---

## 초기 화면 파일 삭제

```bash
rm -f ./app/Controllers/Home.php
rm -f ./app/Views/welcome_message.php
```
