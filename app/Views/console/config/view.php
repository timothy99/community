<form id="frm" name="frm">

<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
        <h3>환경설정</h3>

        <div class="card mb-4">
            <div class="card-header bg-success bg-opacity-75 text-white">기본정보</div>
            <div class="card-body">
                <!-- 회사명 -->
                <div class="mb-3">
                    <label class="form-label">회사명</label>
                    <input type="text" class="form-control bg-light" value="<?= $info->title ?>" readonly>
                </div>

                <!-- 회사로고 -->
                <div class="mb-3">
                    <label class="form-label">회사로고</label>
                    <div class="mt-2">
<?php   if ($info->company_logo != null) { ?>
                        <div class="row g-2 align-items-center">
                            <div class="col">
                                <img src="/csl/file/view/<?= $info->company_logo_info ?>" class="img-thumbnail" style="width: 300px; height: auto;">
                            </div>
                            <div class="col">
                                <small class="text-muted">원본파일명</small><br>
                                <?= $info->company_logo_info->file_name_org ?>
                            </div>
                            <div class="col">
                                <small class="text-muted">가로해상도</small><br>
                                <?= $info->company_logo_info->image_width_txt ?>px
                            </div>
                            <div class="col">
                                <small class="text-muted">세로해상도</small><br>
                                <?= $info->company_logo_info->image_height_txt ?>px
                            </div>
                            <div class="col">
                                <small class="text-muted">사이즈</small><br>
                                <?= $info->company_logo_info->file_size_kb ?> KB
                            </div>
                        </div>
<?php   } else { ?>
                        <p class="text-muted">등록된 이미지가 없습니다.</p>
<?php   } ?>
                    </div>
                </div>

                <!-- 프로그램 버전 -->
                <div class="mb-3">
                    <label for="program_ver" class="form-label">프로그램 버전</label>
                    <input type="text" id="program_ver" class="form-control bg-light" value="<?= $info->program_ver ?>" readonly>
                </div>

                <!-- 전화번호 -->
                <div class="mb-3">
                    <label for="phone" class="form-label">전화번호</label>
                    <input type="text" class="form-control bg-light" id="phone" name="phone" value="<?= $info->phone ?>" readonly>
                </div>

                <!-- 팩스번호 -->
                <div class="mb-3">
                    <label for="fax" class="form-label">팩스번호</label>
                    <input type="text" class="form-control bg-light" id="fax" name="fax" value="<?= $info->fax ?>" readonly>
                </div>

                <!-- 이메일 -->
                <div class="mb-3">
                    <label for="email" class="form-label">이메일</label>
                    <input type="text" class="form-control bg-light" id="email" name="email" value="<?= $info->email ?>" readonly>
                </div>

                <!-- 업무시간 -->
                <div class="mb-3">
                    <label for="work_hour" class="form-label">업무시간</label>
                    <input type="text" class="form-control bg-light" id="work_hour" name="work_hour" value="<?= $info->work_hour ?>" readonly>
                </div>

                <!-- 우편번호 -->
                <div class="mb-3">
                    <label for="post_code" class="form-label">우편번호</label>
                    <input type="text" class="form-control bg-light" id="post_code" name="post_code" value="<?= $info->post_code ?>" readonly>
                </div>

                <!-- 주소1 -->
                <div class="mb-3">
                    <label for="addr1" class="form-label">주소1</label>
                    <input type="text" class="form-control bg-light" id="addr1" name="addr1" value="<?= $info->addr1 ?>" readonly>
                </div>

                <!-- 주소2 -->
                <div class="mb-3">
                    <label for="addr2" class="form-label">주소2</label>
                    <input type="text" class="form-control bg-light" id="addr2" name="addr2" value="<?= $info->addr2 ?>" readonly>
                </div>

                <!-- 사업자등록번호 -->
                <div class="mb-3">
                    <label for="biz_no" class="form-label">사업자등록번호</label>
                    <input type="text" class="form-control bg-light" id="biz_no" name="biz_no" value="<?= $info->biz_no ?>" readonly>
                </div>

                <!-- 메일발송기능 사용여부 -->
                <div class="mb-3">
                    <label for="smtp_yn" class="form-label">메일발송기능 사용여부</label>
                    <input type="text" class="form-control bg-light" id="smtp_yn" name="smtp_yn" value="<?= $info->smtp_yn ?>" readonly>
                </div>

                <!-- SMTP 호스트 -->
                <div class="mb-3">
                    <label for="smtp_host" class="form-label">SMTP 호스트</label>
                    <input type="text" class="form-control bg-light" id="smtp_host" name="smtp_host" value="<?= $info->smtp_host ?>" readonly>
                </div>

                <!-- SMTP 이메일 주소 -->
                <div class="mb-3">
                    <label for="smtp_mail" class="form-label">SMTP 이메일 주소</label>
                    <input type="text" class="form-control bg-light" id="smtp_mail" name="smtp_mail" value="<?= $info->smtp_mail ?>" readonly>
                </div>

                <!-- SMTP 사용자아이디 -->
                <div class="mb-3">
                    <label for="smtp_user" class="form-label">SMTP 사용자아이디</label>
                    <input type="text" class="form-control bg-light" id="smtp_user" name="smtp_user" value="<?= $info->smtp_user ?>" readonly>
                </div>

                <!-- SMTP 암호 -->
                <div class="mb-3">
                    <label for="smtp_pass" class="form-label">SMTP 비밀번호</label>
                    <input type="text" class="form-control bg-light" id="smtp_pass" name="smtp_pass" value="<?= $info->smtp_pass ?>" readonly>
                </div>

                <!-- SMTP 포트 -->
                <div class="mb-3">
                    <label for="smtp_port" class="form-label">SMTP 포트</label>
                    <input type="text" class="form-control bg-light" id="smtp_port" name="smtp_port" value="<?= $info->smtp_port ?>" readonly>
                </div>

                <!-- SMTP 발송자명 -->
                <div class="mb-3">
                    <label for="smtp_name" class="form-label">SMTP 발송자명</label>
                    <input type="text" class="form-control bg-light" id="smtp_name" name="smtp_name" value="<?= $info->smtp_name ?>" readonly>
                </div>

            </div>
            <div class="card-footer text-end">
                <div class="d-flex gap-2 justify-content-end">
                    <a href="/csl/config/edit" class="btn btn-primary">수정</a>
                </div>
            </div>
        </div>

    </div>
</main>

</form>

<script>
    // 메뉴강조
    $(window).on('load', function() {
        $('#li-config').addClass('active-level-1');
    });
</script>