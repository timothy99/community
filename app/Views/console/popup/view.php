<form id="frm" name="frm">

<input type="hidden" id="popup_idx" name="popup_idx" value="<?= $info->popup_idx ?>">

<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
        <h3>레이어 팝업</h3>

        <div class="card mb-4">
            <div class="card-header bg-success bg-opacity-75 text-white">기본정보</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap">
                        <colgroup>
                            <col style="width: 15%;">
                            <col style="width: 80%;">
                        </colgroup>
                        <tbody>
                            <tr>
                                <th class="align-middle bg-light">제목</th>
                                <td><?= $info->title ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">링크 URL</th>
                                <td><a href="<?= $info->url_link ?>" target="_blank"><?= $info->url_link ?></a></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">팝업 위치</th>
                                <td>좌측: <?= $info->position_left ?>px / 상단: <?= $info->position_top ?>px</td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">팝업 크기</th>
                                <td>가로: <?= $info->popup_width ?>px / 세로: <?= $info->popup_height ?>px</td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">다시 보지 않음 시간</th>
                                <td><?= $info->disabled_hours ?>시간</td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">게시기간</th>
                                <td><?= $info->start_date_txt ?> ~ <?= $info->end_date_txt ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">노출여부</th>
                                <td><?= code_replace('display_yn', $info->display_yn) ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">팝업 이미지</th>
                                <td>
<?php   if ($info->popup_file_info != null) { ?>
                                    <div class="mb-3">
                                        <img src="/csl/file/view/<?= $info->popup_file ?>" class="img-thumbnail img-fluid" style="max-width: 100%; height: auto;">
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-auto">
                                            <small class="text-muted d-block">원본파일명</small>
                                            <strong><?= $info->popup_file_info->file_name_org ?></strong>
                                        </div>
                                        <div class="col-auto">
                                            <small class="text-muted d-block">해상도</small>
                                            <strong><?= $info->popup_file_info->image_width_txt ?> × <?= $info->popup_file_info->image_height_txt ?> px</strong>
                                        </div>
                                        <div class="col-auto">
                                            <small class="text-muted d-block">파일 크기</small>
                                            <strong><?= $info->popup_file_info->file_size_kb ?> KB</strong>
                                        </div>
                                    </div>
<?php   } else { ?>
                                    <p class="text-muted mb-0">등록된 이미지가 없습니다.</p>
<?php   } ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-end">
                <div class="d-flex gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger" onclick="popupDelete()">삭제</button>
                    <a href="/csl/popup/list" class="btn btn-secondary">목록</a>
                    <a href="/csl/popup/edit/<?= $info->popup_idx ?>" class="btn btn-primary">수정</a>
                </div>
            </div>
        </div>
    </div>
</main>

</form>

<script>
    // 메뉴강조
    $(window).on('load', function() {
        $('#li-popup').addClass('active-level-1');
    });

    function popupDelete() {
        if (confirm('정말 삭제하시겠습니까?')) {
            ajax1('/csl/popup/delete', 'frm', 'popupDeleteAfter');
        }
    }

    function popupDeleteAfter(proc_result) {
        var result = proc_result.result;
        var message = proc_result.message;
        if (result == true) {
            location.href = '/csl/popup/list';
        } else {
            alert(message);
        }
    }
</script>