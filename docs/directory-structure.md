# 디렉토리 구조

프로젝트의 주요 디렉토리 및 파일 구성입니다.  
헬퍼 등 각 파일에 대한 상세 설명은 파일 내의 주석을 확인하시기 바랍니다.

```
+-- app/
|   +-- Config/                         # 설정 파일
|   |   +-- App.php                     # App 기본설정. 사이트별 설정은 .env 파일에서 설정
|   |   +-- Constants.php               # 시스템 기본 상수 설정
|   |   +-- Events.php                  # 프로그램 최초 작동 시 실행되어야 할 함수들
|   |   └-- Routes.php                  # 라우팅 설정 (get, post, cli 등으로 구분)
|   +-- Controllers/
|   |   +-- Console/                    # 관리자 컨트롤러
|   |   └-- User/                       # 사용자 컨트롤러
|   +-- Models/
|   |   +-- Console/                    # 관리자 모델
|   |   └-- User/                       # 사용자 모델
|   +-- Views/
|   |   +-- console/                    # 관리자 뷰
|   |   └-- user/                       # 사용자 뷰
|   +-- Helpers/
|   |   +-- alert_helper.php            # 알림 처리 헬퍼
|   |   +-- array_helper.php            # 배열 처리 헬퍼
|   |   +-- authority_helper.php        # 권한 관리 헬퍼
|   |   +-- board_helper.php            # 게시판 헬퍼
|   |   +-- curl_helper.php             # cURL 요청 헬퍼
|   |   +-- date_helper.php             # 날짜 처리 헬퍼
|   |   +-- logging_helper.php          # 쿼리 로깅 등을 지원하는 헬퍼
|   |   +-- paging_helper.php           # 페이징 헬퍼
|   |   +-- privacy_helper.php          # 개인정보 처리 헬퍼
|   |   +-- security_helper.php         # 보안 헬퍼
|   |   +-- session_helper.php          # 세션을 편리하게 사용하기 위한 헬퍼
|   |   +-- text_helper.php             # 텍스트 처리 헬퍼
|   |   └-- view_helper.php             # 뷰 관련 헬퍼
|   +-- Database/
|   |   +-- Migrations/                 # 마이그레이션 파일
|   |   └-- Seeds/                      # 시드 데이터
|   └-- Filters/                        # 필터
+-- public/                             # 웹 루트 (DocumentRoot)
|   +-- index.php                       # 진입점
|   └-- resource/                       # 정적 자원 (css, js, 이미지 등)
|       +-- console/                    # 관리자용 리소스
|       └-- user/                       # 사용자용 리소스
+-- docs/                               # 프로젝트 문서
+-- env                                 # 각종 환경설정 가이드 파일 (.env 작성 참고용)
+-- sql/
|   +-- table.sql                       # 테이블 생성 스크립트
|   └-- insert.sql                      # 초기 데이터 삽입 스크립트
+-- writable/
|   +-- cache/                          # 캐시 저장소
|   +-- logs/                           # 로그 저장소
|   +-- session/                        # 세션 저장소
|   └-- uploads/                        # 업로드 파일 저장소
└-- tests/                              # 테스트 코드
```
