# 개발 환경 설정

## SFTP 설정 (VS Code - SFTP 확장)

VS Code 의 [SFTP 확장](https://marketplace.visualstudio.com/items?itemName=Natizyskunk.sftp)을 사용하는 경우 `.vscode/sftp.json` 에 아래 내용을 작성합니다.

```json
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

> `sftp.json` 파일에는 계정 정보가 포함되므로 반드시 `.gitignore` 에 `.vscode/sftp.json` 을 추가하여 저장소에 커밋되지 않도록 한다.
