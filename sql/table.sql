drop table if exists mng_member;
create table mng_member (
    member_idx int not null auto_increment comment '인덱스',
    member_id varchar(64) not null comment '사용자 아이디',
    member_password varchar(1000) not null comment '암호',
    member_name varchar(60) not null comment '이름',
    member_nickname varchar(60) not null comment '별명',
    email_yn enum('Y', 'N') default 'Y' null comment '뉴스레터 수신 동의 여부',
    sms_yn enum('Y', 'N') default 'Y' null comment 'SMS 수신 동의 여부',
    email varchar(100) default null comment '이메일',
    phone varchar(13) default null comment '휴대전화 번호',
    post_code varchar(5) default null comment '우편번호',
    addr1 varchar(200) default null comment '주소1',
    addr2 varchar(200) default null comment '주소2',
    auth_group varchar(20) not null comment '권한 그룹',
    last_login_date varchar(14) not null comment '최종 로그인 시간',
    last_login_ip varchar(15) default null comment '마지막 로그인 ip',
    member_point int not null default 0 comment '회원 포인트',
    del_yn enum('Y', 'N') not null comment '삭제 여부',
    ins_id varchar(70) default null comment '등록자',
    ins_date varchar(14) not null comment '등록일',
    upd_id varchar(70) default null comment '수정자',
    upd_date varchar(14) not null comment '수정일',
    unique key member_index1 (member_id, del_yn),
    primary key (member_idx)
) comment='회원정보' collate='utf8mb4_unicode_ci';

create table mng_admin (
    admin_idx int not null auto_increment comment '관리자 번호',
    member_id int not null comment '회원 번호',
    start_date varchar(14) not null comment '관리자 권한 부여일',
    end_date varchar(14) not null default '99991231235959' comment '관리자 권한 삭제일',
    ins_id varchar(70) not null comment '입력자',
    ins_date varchar(14) not null comment '입력일',
    upd_id varchar(70) not null comment '수정자',
    upd_date varchar(14) not null comment '수정일',
    primary key (admin_idx),
    unique key admin_index1 (member_id)
) comment='관리자 권한 부여' collate='utf8mb4_unicode_ci';

drop table if exists mng_menu;
create table mng_menu (
    menu_idx int not null auto_increment comment '인덱스',
    upper_idx int not null default 0 comment '상위 인덱스',
    language varchar(20) default null comment '언어',
    idx1 int not null default 0 comment '인덱스1',
    idx2 int not null default 0 comment '인덱스2',
    order_no int not null default 0 comment '정렬순서',
    menu_position int not null default 0 comment '메뉴 위치',
    menu_name varchar(500) null comment '메뉴명',
    url_link varchar(500) null comment '링크',
    del_yn enum('Y', 'N') not null comment '삭제 여부',
    ins_id varchar(70) default null comment '입력자',
    ins_date varchar(14) not null comment '입력일',
    upd_id varchar(70) default null comment '수정자',
    upd_date varchar(14) not null comment '수정일',
    primary key (menu_idx),
    key menu_idx1 (idx1, idx2)
) comment='메뉴' collate='utf8mb4_unicode_ci';

create table mng_board (
    board_idx int not null auto_increment comment '게시물 번호',
    board_idx_desc int not null default 0 comment '게시물 번호 역순',
    board_id varchar(20) default null comment '게시판 아이디',
    category varchar(20) default null comment '카테고리',
    title varchar(1000) not null comment '제목',
    contents longtext not null comment '내용',
    main_file_id varchar(32) default null comment '대표파일 id',
    url_link varchar(500) default null comment '인터넷 링크',
    comment_cnt int not null default 0 comment '댓글 등록수',
    heart_cnt int not null default 0 comment '공감수',
    hit_cnt int not null default 0 comment '조회수',
    reg_date varchar(14) null comment '등록일-정렬을 위해 사용자가 입력한 날짜',
    notice_yn enum('Y', 'N') not null default 'N' comment '공지 여부',
    display_yn enum('Y', 'N') not null default 'Y' comment '노출 여부',
    del_yn enum('Y', 'N') not null default 'N' comment '삭제 여부',
    ins_id varchar(70) not null comment '입력자',
    ins_date varchar(14) not null comment '입력일',
    upd_id varchar(70) not null comment '수정자',
    upd_date varchar(14) not null comment '수정일',
    key board_index1 (board_id),
    key board_index2 (board_id, notice_yn, category, board_idx_desc),
    key board_index3 (board_id, notice_yn, category, reg_date),
    key board_index4 (board_id, ins_id, board_idx_desc),
    primary key (board_idx)
) comment='게시판' collate='utf8mb4_unicode_ci';

create table mng_board_comment (
    board_comment_idx int not null auto_increment comment '인덱스',
    board_idx int not null comment '게시물 번호',
    comment varchar(4000) not null comment '댓글',
    del_yn enum('Y', 'N') not null comment '삭제 여부',
    ins_id varchar(70) default null comment '입력자',
    ins_date varchar(14) not null comment '입력일',
    upd_id varchar(70) default null comment '수정자',
    upd_date varchar(14) not null comment '수정일',
    key board_comment_index1 (board_idx),
    primary key (board_comment_idx)
) comment='게시판 댓글' collate='utf8mb4_unicode_ci';

drop table if exists mng_board_file;
create table mng_board_file (
    board_file_idx int not null auto_increment comment '인덱스',
    board_idx int not null comment '게시물 번호',
    file_id varchar(32) default null comment '파일 불러오기를 위한 id',
    key board_file_index1 (board_idx),
    key board_file_index2 (file_id),
    primary key (board_file_idx)
) comment='게시판 파일' collate='utf8mb4_unicode_ci';

drop table if exists mng_board_config;
create table mng_board_config (
    board_config_idx int not null auto_increment comment '인덱스',
    board_id varchar(20) default null comment '게시판 아이디',
    type varchar(100) default null comment '타입(스킨)',
    category varchar(300) default null comment '카테고리',
    category_yn enum('Y', 'N') default 'N' not null comment '카테고리 사용여부',
    user_write enum('Y', 'N') default 'N' not null comment '사용자가 글쓰기 가능하게 할지 여부',
    comment_write enum('Y', 'N') default 'N' not null comment '사용자가 댓글쓰기 가능하게 할지 여부',
    title varchar(1000) not null comment '제목',
    base_rows int not null comment '화면에 기본으로 보여줄 줄 수',
    reg_date_yn enum('Y', 'N') default 'N' not null comment '입력일 수정 기능 사용 여부',
    file_cnt int not null comment '최대 첨부파일 업로드 수',
    file_upload_size_limit int null comment '최대 파일 업로드 용량 제한(서버 설정에 영향을 받는다.)',
    file_upload_size_total int null comment '총 파일 업로드 용량 제한(서버 설정에 영향을 받는다.)',
    form_style varchar(4000) null comment '게시판 폼 스타일',
    form_style_yn enum('Y', 'N') default 'N' not null comment '게시판 폼 스타일 사용 여부',
    write_point int not null default 0 comment '글 작성시 지급 포인트',
    comment_point int not null default 0 comment '댓글 작성시 지급 포인트',
    hit_edit_yn enum('Y', 'N') default 'Y' not null comment '조회수 수정 기능 사용 여부',
    hit_yn enum('Y', 'N') default 'Y' not null comment '조회수 사용 여부',
    heart_yn enum('Y', 'N') default 'Y' not null comment '공감 기능 사용 여부',
    pdf_yn enum('Y', 'N') default 'N' not null comment 'PDF 보기 기능 사용 여부',
    youtube_yn enum('Y', 'N') default 'N' not null comment '유튜브 기능 사용 여부',
    del_yn enum('Y', 'N') not null default 'N' comment '삭제 여부',
    ins_id varchar(70) not null comment '입력자',
    ins_date varchar(14) not null comment '입력일',
    upd_id varchar(70) not null comment '수정자',
    upd_date varchar(14) not null comment '수정일',
    key board_config_index1 (board_id),
    primary key (board_config_idx)
) comment='게시판 설정 관리' collate='utf8mb4_unicode_ci';

drop table if exists mng_file;
create table mng_file (
    file_idx int not null auto_increment comment '연번',
    file_id varchar(32) default null comment '파일 불러오기를 위한 id',
    file_name_org varchar(1000) not null comment '원본 파일명',
    file_directory varchar(10) not null comment '저장된 파일의 경로(연/월)',
    file_name_uploaded varchar(1000) not null comment '저장된 파일 전체 경로',
    file_size int not null comment '파일 크기',
    file_ext varchar(10) default null comment '파일확장자',
    image_width int not null default 0 comment '가로해상도(이미지)',
    image_height int not null default 0 comment '세로해상도(이미지)',
    mime_type varchar(200) not null comment '파일 mime type',
    category varchar(100) not null comment '사용자가 지정한 파일 형식',
    del_yn enum('Y', 'N') not null comment '삭제 여부',
    ins_id varchar(70) not null comment '입력자',
    ins_date varchar(14) not null comment '입력일',
    upd_id varchar(70) not null comment '수정자',
    upd_date varchar(14) not null comment '수정일',
    unique key file_index1 (file_id),
    primary key (file_idx)
) comment='파일 정보' collate='utf8mb4_unicode_ci';

drop table if exists mng_member_reset;
create table mng_member_reset (
    member_reset_idx int not null auto_increment comment '인덱스',
    member_id varchar(64) not null comment '사용자 아이디',
    email varchar(100) default null comment '이메일',
    reset_key varchar(32) default null comment '리셋키',
    expire_date varchar(14) not null comment '암호화 변경 만료 시간(현재 시간으로부터 15분)',
    key mng_member_reset_reset_key (reset_key),
    primary key (member_reset_idx)
) comment='암호를 초기화 하기 위한 정보' collate='utf8mb4_unicode_ci';

drop table if exists mng_contents;
create table mng_contents (
    contents_idx int auto_increment comment '콘텐츠 인덱스',
    contents_id varchar(50) null comment '콘텐츠 아이디',
    meta_title varchar(500) null comment '메타 제목',
    title varchar(1000) not null comment '제목',
    contents longtext not null comment '내용',
    del_yn varchar(1) default 'N' not null comment '삭제 여부',
    ins_id varchar(70) not null comment '입력자',
    ins_date varchar(14) not null comment '입력일',
    upd_id varchar(70) not null comment '수정자',
    upd_date varchar(14) not null comment '수정일',
    key contents_index1 (contents_id),
    primary key (contents_idx)
) comment '콘텐츠' collate='utf8mb4_unicode_ci';

drop table if exists mng_slide;
create table mng_slide (
    slide_idx int auto_increment comment '슬라이드 인덱스',
    title varchar(1000) not null comment '제목',
    sub_title varchar(1000) null comment '부제목',
    contents varchar(4000) not null comment '내용-슬라이드에선 실제 내용 출력되지 않으므로 alt내용을 의미함',
    url_link varchar(1000) not null comment 'http 링크',
    order_no int null comment '순서',
    slide_file varchar(32) null comment '슬라이드 이미지',
    start_date varchar(14) default '20000101000000' not null comment '게시 시작시간',
    end_date varchar(14) default '99991231235959' not null comment '게시 종료시간',
    display_yn enum ('Y', 'N') default 'Y' not null comment '노출 여부',
    del_yn enum ('Y', 'N') default 'N' not null comment '삭제 여부',
    ins_id varchar(70) not null comment '입력자',
    ins_date varchar(14) not null comment '입력일',
    upd_id varchar(70) not null comment '수정자',
    upd_date varchar(14) not null comment '수정일',
    primary key (slide_idx)
) comment '슬라이드' collate='utf8mb4_unicode_ci';

drop table if exists mng_popup;
create table mng_popup (
    popup_idx int auto_increment comment '팝업 인덱스' primary key,
    title varchar(1000) not null comment '제목',
    popup_file varchar(32) null comment '레이어 팝업 이미지',
    url_link varchar(1000) not null comment 'http 링크',
    position_left int null comment '좌측 위치',
    position_top int null comment '상단 위치',
    popup_width int null comment '너비',
    popup_height int null comment '높이',
    disabled_hours int null comment '다시 보지 않음 누를시 안보이는 시간',
    start_date varchar(14) default '20000101000000' not null comment '게시 시작시간',
    end_date varchar(14) default '99991231235959' not null comment '게시 종료시간',
    display_yn enum ('Y', 'N') default 'Y' not null comment '노출 여부',
    del_yn enum ('Y', 'N') default 'N' not null comment '삭제 여부',
    ins_id varchar(70) not null comment '입력자',
    ins_date varchar(14) not null comment '입력일',
    upd_id varchar(70) not null comment '수정자',
    upd_date varchar(14) not null comment '수정일'
) comment '레이어 팝업' collate='utf8mb4_unicode_ci';

drop table if exists mng_inquiry;
create table mng_inquiry (
    inquiry_idx int auto_increment comment '문의 인덱스',
    name varchar(200) not null comment '이름',
    contents varchar(4000) default null comment '내용',
    phone varchar(32) not null comment '전화',
    email varchar(200) not null comment '이메일',
    del_yn enum ('Y', 'N') default 'N' not null comment '삭제 여부',
    ins_date varchar(14) not null comment '입력일',
    upd_date varchar(14) not null comment '수정일',
    primary key (inquiry_idx)
) comment '단순문의' collate='utf8mb4_unicode_ci';

drop table if exists mng_shortlink;
create table mng_shortlink (
    short_link_idx int not null auto_increment comment '단축url 인덱스',
    title varchar(1000) not null comment '제목',
    url_link varchar(1000) not null comment '이동할 링크',
    del_yn enum('Y', 'N') not null default 'N' comment '삭제 여부',
    ins_id varchar(70) not null comment '입력자',
    ins_date varchar(14) not null comment '입력일',
    upd_id varchar(70) not null comment '수정자',
    upd_date varchar(14) not null comment '수정일',
    primary key (short_link_idx)
) comment='단축url' collate='utf8mb4_unicode_ci';

drop table if exists mng_privacy;
create table mng_privacy (
    privacy_idx int auto_increment comment '인덱스',
    url_link varchar(1000) not null comment '링크',
    memo varchar(2000) not null comment '상담메모',
    ip_addr varchar(15) not null comment 'IP주소',
    del_yn enum ('Y', 'N') default 'N' not null comment '삭제 여부',
    ins_id varchar(70) not null comment '입력자',
    ins_date varchar(14) not null comment '입력일',
    upd_id varchar(70) not null comment '수정자',
    upd_date varchar(14) not null comment '수정일',
    key privacy_index1 (ins_id),
    key privacy_index2 (upd_id),
    primary key (privacy_idx)
) comment '개인정보 처리시스템' collate='utf8mb4_unicode_ci';

drop table if exists mng_youtube;
create table mng_youtube (
    youtube_idx int not null auto_increment comment '인덱스',
    title varchar(200) not null comment '제목',
    category varchar(30) not null comment '채널형인지 재생목록형인지',
    play_id varchar(500) not null comment '채널 또는 재생목록의 아이디',
    del_yn enum('Y', 'N') not null default 'N' comment '삭제 여부',
    ins_id varchar(70) not null comment '입력자',
    ins_date varchar(14) not null comment '입력일',
    upd_id varchar(70) not null comment '수정자',
    upd_date varchar(14) not null comment '수정일',
    key youtube_index1 (play_id),
    primary key (youtube_idx)
) comment='유튜브 재생 목록' collate='utf8mb4_unicode_ci';

create table gst_config (
    config_idx int auto_increment comment '인덱스' primary key,
    title varchar(300) null comment '회사명',
    phone varchar(13) null comment '전화',
    fax varchar(13) null comment '팩스',
    email varchar(200) null comment '이메일',
    work_hour varchar(300) null comment '업무시간',
    post_code varchar(5) null comment '우편번호',
    addr1 varchar(200) null comment '주소1',
    addr2 varchar(200) null comment '주소2',
    biz_no varchar(12) null comment '사업자등록번호',
    company_logo varchar(32) null comment '회사로고',
    program_ver varchar(32) null comment '프로그램 버젼',
    smtp_yn varchar(1) default 'N' not null comment '메일발송기능 사용여부',
    smtp_host varchar(200) null comment 'SMTP 호스트',
    smtp_user varchar(200) null comment 'SMTP 사용자아이디',
    smtp_pass varchar(200) null comment 'SMTP 암호',
    smtp_port varchar(200) null comment 'SMTP 포트',
    smtp_name varchar(200) null comment 'SMTP 발송자 이름',
    smtp_mail varchar(200) null comment 'SMTP 발송자 메일'
) comment '설정 관리';

INSERT INTO gst_config (title, phone, fax, email, work_hour, post_code, addr1, addr2, biz_no, company_logo, program_ver, smtp_yn, smtp_host, smtp_user, smtp_pass, smtp_port, smtp_name, smtp_mail) VALUES ('회사', '000-111-2222', '', 'email@test.com', '평일 9:00 ~ 18:00 토요일/공휴일 휴무', '00000', '서울 중구 세종로', '', '123-45-67890', '', '1', 'Y', 'smtp.mail.nate.com', 'bjm1175', 'Nate4728!', '587', '회사', 'bjm1175@nate.com');
