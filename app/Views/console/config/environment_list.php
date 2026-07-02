<?php
/**
 * @var object $info
 * @var array $list
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
            <div class="card-header bg-success bg-opacity-75 text-white">공통 설정</div>
            <div class="card-body">
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
            </div>
            <div class="card-footer text-end">
                <div class="d-flex gap-2 justify-content-end">
                    <button type="button" class="btn btn-primary" onclick="configSectionUpdate()">공통설정 수정</button>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-success bg-opacity-75 text-white">언어별 설정 요약</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>언어명</th>
                                <th>코드</th>
                                <th>회사명</th>
                                <th>이메일</th>
                                <th>전화번호</th>
                                <th>주소</th>
                                <th style="width: 120px;" class="text-center">관리</th>
                            </tr>
                        </thead>
                        <tbody>
<?php   foreach ($list as $no => $val) { ?>
                            <tr>
                                <td><?= $val->language_name ?></td>
                                <td><?= $val->language_code ?></td>
                                <td><?= $val->title ?? '' ?></td>
                                <td><?= $val->email ?? '' ?></td>
                                <td><?= $val->phone ?? '' ?></td>
                                <td>[<?= $val->post_code ?? '' ?>] <?= $val->addr1 ?? '' ?> <?= $val->addr2 ?? '' ?></td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-outline-primary" href="/csl/config/environment/edit/<?= $val->language_code ?>">편집</a>
                                </td>
                            </tr>
<?php   } ?>
                        </tbody>
                    </table>
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
        if (confirm('공통설정을 수정하시겠습니까?')) {
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
