<?php
/**
 * @var object $language_info
 */

$page_title = '일반환경 (언어별)';
$active_menu_id = 'a-config-environment';
$update_url = '/csl/config/environment/language/update';
?>

<form id="frm" name="frm">

<input type="hidden" id="language_code" name="language_code" value="<?= $language_info->language_code ?? '' ?>">

<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h3 class="mb-0"><?= $page_title ?></h3>
            <a href="/csl/config/environment/list" class="btn btn-outline-secondary btn-sm">목록으로</a>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-success bg-opacity-75 text-white">언어: <?= $language_info->language_name ?? '' ?> (<?= $language_info->language_code ?? '' ?>)</div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="title" class="form-label">회사명</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?= $language_info->title ?? '' ?>">
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">회사 소개(메타용)</label>
                    <input type="text" class="form-control" id="description" name="description" value="<?= $language_info->description ?? '' ?>">
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">전화번호</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?= $language_info->phone ?? '' ?>">
                </div>

                <div class="mb-3">
                    <label for="fax" class="form-label">팩스번호</label>
                    <input type="text" class="form-control" id="fax" name="fax" value="<?= $language_info->fax ?? '' ?>">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">이메일</label>
                    <input type="text" class="form-control" id="email" name="email" value="<?= $language_info->email ?? '' ?>">
                </div>

                <div class="mb-3">
                    <label for="work_hour" class="form-label">업무시간</label>
                    <input type="text" class="form-control" id="work_hour" name="work_hour" value="<?= $language_info->work_hour ?? '' ?>">
                </div>

                <div class="mb-3">
                    <label for="post_code" class="form-label">우편번호</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="post_code" name="post_code" placeholder="우편번호" maxlength="5" value="<?= $language_info->post_code ?? '' ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="addr1" class="form-label">주소1</label>
                    <input type="text" class="form-control" id="addr1" name="addr1" value="<?= $language_info->addr1 ?? '' ?>">
                </div>

                <div class="mb-3">
                    <label for="addr2" class="form-label">주소2</label>
                    <input type="text" class="form-control" id="addr2" name="addr2" value="<?= $language_info->addr2 ?? '' ?>">
                </div>

                <div class="mb-3">
                    <label for="biz_no" class="form-label">사업자등록번호</label>
                    <input type="text" class="form-control" id="biz_no" name="biz_no" value="<?= $language_info->biz_no ?? '' ?>">
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
