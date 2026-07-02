<?php
/**
 * @var object $info
 */

$page_title = '보안설정';
$active_menu_id = 'a-config-security';
$update_url = '/csl/config/security/update';
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
                    <label for="construction_yn" class="form-label">공사중 여부</label>
                    <select class="form-select" id="construction_yn" name="construction_yn">
                        <option value="Y">공사중</option>
                        <option value="N">정상</option>
                    </select>
                    <small class="text-muted">공사중 여부를 선택하세요. 공사중으로 선택하시면 사이트가 비활성화 되며 <a href="/csl/config/ip/list">IP관리</a>에 등록한 IP만 접근이 가능합니다.</small>
                </div>

                <div class="mb-3">
                    <label for="login_required_yn" class="form-label">로그인 필수</label>
                    <select class="form-select" id="login_required_yn" name="login_required_yn">
                        <option value="Y">사용</option>
                        <option value="N">사용안함</option>
                    </select>
                    <small class="text-muted">사용 시 비로그인 사용자(guest)는 로그인 관련 화면을 제외하고 즉시 로그인 페이지로 이동합니다.</small>
                </div>

                <div class="mb-3">
                    <label for="admin_two_factor_yn" class="form-label">관리자 접속시 이메일 인증</label>
                    <select class="form-select" id="admin_two_factor_yn" name="admin_two_factor_yn">
                        <option value="Y">사용</option>
                        <option value="N">사용안함</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="admin_ip_check_yn" class="form-label">관리자 접속시 IP확인</label>
                    <select class="form-select" id="admin_ip_check_yn" name="admin_ip_check_yn">
                        <option value="Y">사용</option>
                        <option value="N">사용안함</option>
                    </select>
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

        $('#admin_two_factor_yn').val('<?= $info->admin_two_factor_yn ?>');
        $('#admin_ip_check_yn').val('<?= $info->admin_ip_check_yn ?>');
        $('#construction_yn').val('<?= $info->construction_yn ?>');
        $('#login_required_yn').val('<?= $info->login_required_yn ?? 'N' ?>');
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
