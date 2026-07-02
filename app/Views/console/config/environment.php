<?php
/**
 * @var object $info
 */

$page_title = '일반환경';
$active_menu_id = 'a-config-environment';
$update_url = '/csl/config/environment/update';
?>

<form id="frm" name="frm">

<input type="hidden" id="company_logo_hidden" name="company_logo_hidden" value="<?= $info->company_logo ?>">

<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
        <h3><?= $page_title ?></h3>

        <div class="card mb-4">
            <div class="card-header bg-success bg-opacity-75 text-white">기본정보</div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="title" class="form-label">회사명</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?= $info->title ?>">
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">회사 소개(메타용)</label>
                    <input type="text" class="form-control" id="description" name="description" value="<?= $info->description ?>">
                </div>

                <div class="mb-3">
                    <label for="company_logo" class="form-label">회사로고</label>
                    <input type="file" class="form-control" id="company_logo" name="company_logo" onchange="uploadFile(this.id, 'image', 'uploadAfter')">
                    <div class="mt-2" id="company_logo_view">
<?php   if ($info->company_logo != null && $info->company_logo_info != null) { ?>
                        <div class="row g-2 align-items-center">
                            <div class="col">
                                <img src="/file/view/<?= $info->company_logo ?>" class="img-thumbnail" style="width: 300px; height: auto;">
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

                <div class="mb-3">
                    <label for="program_ver" class="form-label">프로그램 버전</label>
                    <input type="text" id="program_ver" name="program_ver" class="form-control" value="<?= $info->program_ver ?>">
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">전화번호</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?= $info->phone ?>">
                </div>

                <div class="mb-3">
                    <label for="fax" class="form-label">팩스번호</label>
                    <input type="text" class="form-control" id="fax" name="fax" value="<?= $info->fax ?>">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">이메일</label>
                    <input type="text" class="form-control" id="email" name="email" value="<?= $info->email ?>">
                </div>

                <div class="mb-3">
                    <label for="work_hour" class="form-label">업무시간</label>
                    <input type="text" class="form-control" id="work_hour" name="work_hour" value="<?= $info->work_hour ?>">
                </div>

                <div class="mb-3">
                    <label for="post_code" class="form-label">우편번호</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="post_code" name="post_code" placeholder="우편번호" maxlength="5" value="<?= $info->post_code ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="addr1" class="form-label">주소1</label>
                    <input type="text" class="form-control" id="addr1" name="addr1" value="<?= $info->addr1 ?>">
                </div>

                <div class="mb-3">
                    <label for="addr2" class="form-label">주소2</label>
                    <input type="text" class="form-control" id="addr2" name="addr2" value="<?= $info->addr2 ?>">
                </div>

                <div class="mb-3">
                    <label for="biz_no" class="form-label">사업자등록번호</label>
                    <input type="text" class="form-control" id="biz_no" name="biz_no" value="<?= $info->biz_no ?>">
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

    function uploadAfter(proc_result) {
        var result = proc_result.result;
        var message = proc_result.message;
        var info = proc_result.info;
        if (result == true) {
            $('#company_logo_hidden').val(info.file_id);
            var html = '<div class="row g-2 align-items-center">';
            html += '<div class="col"><img src="/file/view/' + info.file_id + '" class="img-thumbnail" style="width: 300px; height: auto;"></div>';
            html += '<div class="col"><small class="text-muted">원본파일명</small><br>' + info.file_name_org + '</div>';
            html += '<div class="col"><small class="text-muted">가로해상도</small><br>' + info.image_width_txt + 'px</div>';
            html += '<div class="col"><small class="text-muted">세로해상도</small><br>' + info.image_height_txt + 'px</div>';
            html += '<div class="col"><small class="text-muted">사이즈</small><br>' + info.file_size_kb + 'KB</div>';
            html += '</div>';
            $('#company_logo_view').html(html);
        } else {
            alert(message);
        }
    }
</script>
