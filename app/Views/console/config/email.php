<?php
/**
 * @var object $info
 */

$page_title = '이메일 설정';
$active_menu_id = 'a-config-email';
$update_url = '/csl/config/email/update';
?>

<form id="frm" name="frm">

<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
        <h3><?= $page_title ?></h3>

        <div class="card mb-4">
            <div class="card-header bg-success bg-opacity-75 text-white">기본정보</div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="smtp_yn" class="form-label">메일발송기능 사용여부</label>
                    <select class="form-select" id="smtp_yn" name="smtp_yn">
                        <option value="Y">사용</option>
                        <option value="N">사용안함</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="manager_email" class="form-label">담당자 이메일</label>
                    <input type="text" class="form-control" id="manager_email" name="manager_email" value="<?= $info->manager_email ?>">
                    <small class="text-muted">담당자 이메일을 입력하세요. 이 메일 주소로 문의사항이 전달됩니다.</small>
                </div>

                <div class="mb-3">
                    <label for="smtp_host" class="form-label">SMTP 호스트</label>
                    <input type="text" class="form-control" id="smtp_host" name="smtp_host" value="<?= $info->smtp_host ?>">
                </div>

                <div class="mb-3">
                    <label for="smtp_mail" class="form-label">SMTP 이메일 주소</label>
                    <input type="text" class="form-control" id="smtp_mail" name="smtp_mail" value="<?= $info->smtp_mail ?>">
                </div>

                <div class="mb-3">
                    <label for="smtp_user" class="form-label">SMTP 사용자아이디</label>
                    <input type="text" class="form-control" id="smtp_user" name="smtp_user" value="<?= $info->smtp_user ?>">
                </div>

                <div class="mb-3">
                    <label for="smtp_pass" class="form-label">SMTP 비밀번호</label>
                    <input type="text" class="form-control" id="smtp_pass" name="smtp_pass" value="<?= $info->smtp_pass ?>">
                </div>

                <div class="mb-3">
                    <label for="smtp_port" class="form-label">SMTP 포트</label>
                    <input type="text" class="form-control" id="smtp_port" name="smtp_port" value="<?= $info->smtp_port ?>">
                </div>

                <div class="mb-3">
                    <label for="smtp_name" class="form-label">SMTP 발송자명</label>
                    <input type="text" class="form-control" id="smtp_name" name="smtp_name" value="<?= $info->smtp_name ?>">
                </div>
            </div>
            <div class="card-footer text-end">
                <div class="d-flex gap-2 justify-content-end">
                    <button type="button" class="btn btn-primary" onclick="configSectionUpdate()">수정</button>
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
        $('#collapse-config').addClass('show').addClass('submenu');
        $('#<?= $active_menu_id ?>').addClass('active-level-2');

        $('#smtp_yn').val('<?= $info->smtp_yn ?>');
    });

    function configSectionUpdate() {
        if (confirm('수정하시겠습니까?')) {
            ajax1('<?= $update_url ?>', 'frm', 'configUpdateAfter');
        }
    }

    function configUpdateAfter(proc_result) {
        var result = proc_result.result;
        var message = proc_result.message;
        var return_url = proc_result.return_url;
        if (result == true) {
            location.href = return_url;
        } else {
            alert(message);
        }
    }
</script>
