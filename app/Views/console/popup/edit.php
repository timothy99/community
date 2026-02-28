<form id="frm" name="frm">

<input type="hidden" id="popup_idx" name="popup_idx" value="<?= $info->popup_idx ?>">
<input type="hidden" id="popup_file_hidden" name="popup_file_hidden" value="<?= $info->popup_file ?>">

<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
        <h3>레이어 팝업</h3>

        <div class="card mb-4">
            <div class="card-header bg-success bg-opacity-75 text-white">기본정보</div>
            <div class="card-body">
                <!-- 제목 -->
                <div class="mb-3">
                    <label for="title" class="form-label">제목 <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="제목을 입력하세요" value="<?= $info->title ?>">
                </div>

                <!-- URL 링크 -->
                <div class="mb-3">
                    <label for="url_link" class="form-label">링크 URL <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="url_link" name="url_link" placeholder="http로 시작하는 주소 전체. 내부 링크는 /부터 입력도 가능합니다." value="<?= $info->url_link ?>">
                </div>

                <!-- 팝업 위치 -->
                <div class="mb-3">
                    <label class="form-label">팝업 위치 <span class="text-danger">*</span></label>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text">좌측</span>
                                <input type="number" class="form-control" id="position_left" name="position_left" placeholder="100" value="<?= $info->position_left ?>">
                                <span class="input-group-text">px</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text">상단</span>
                                <input type="number" class="form-control" id="position_top" name="position_top" placeholder="100" value="<?= $info->position_top ?>">
                                <span class="input-group-text">px</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 팝업 크기 -->
                <div class="mb-3">
                    <label class="form-label">팝업 크기 <span class="text-danger">*</span></label>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text">가로</span>
                                <input type="number" class="form-control" id="popup_width" name="popup_width" placeholder="400" value="<?= $info->popup_width ?>">
                                <span class="input-group-text">px</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text">세로</span>
                                <input type="number" class="form-control" id="popup_height" name="popup_height" placeholder="500" value="<?= $info->popup_height ?>">
                                <span class="input-group-text">px</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 다시 보지 않음 시간 -->
                <div class="mb-3">
                    <label for="disabled_hours" class="form-label">다시 보지 않음 시간 <span class="text-danger">*</span></label>
                    <div class="input-group w-50">
                        <input type="number" class="form-control" id="disabled_hours" name="disabled_hours" placeholder="24" value="<?= $info->disabled_hours ?>">
                        <span class="input-group-text">시간</span>
                    </div>
                    <small class="text-muted">사용자가 '오늘 하루 보지 않기' 버튼을 클릭할 경우 다시 표시되지 않을 시간을 설정합니다.</small>
                </div>

                <!-- 게시기간 -->
                <div class="mb-3">
                    <label for="start_date" class="form-label">게시기간</label>
                    <div class="row g-2 align-items-center">
                        <div class="col-auto">
                            <input type="text" class="form-control" id="start_date" name="start_date" value="<?=$info->start_date_txt ?>">
                        </div>
                        <div class="col-auto text-muted">~</div>
                        <div class="col-auto">
                            <input type="text" class="form-control" id="end_date" name="end_date" value="<?=$info->end_date_txt ?>">
                        </div>
                    </div>
                </div>

                <!-- 노출여부 -->
                <div class="mb-3">
                    <label class="form-label">노출여부</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="display_yn" id="display_yn_y" value="Y">
                            <label class="form-check-label" for="display_yn_y">노출</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="display_yn" id="display_yn_n" value="N">
                            <label class="form-check-label" for="display_yn_n">숨김</label>
                        </div>
                    </div>
                </div>

                <!-- 팝업 이미지 -->
                <div class="mb-3">
                    <label for="popup_file" class="form-label">팝업 이미지 <span class="text-danger">*</span></label>
                    <input type="file" class="form-control" id="popup_file" name="popup_file" onchange="uploadFile(this.id, 'image', 'uploadAfter')">
                    <div class="mb-2 mt-2" id="popup_file_view">
<?php   if ($info->popup_file_info != null) { ?>
                        <div class="row g-2 align-items-center">
                            <div class="col">
                                <img src="/csl/file/view/<?= $info->popup_file ?>" class="img-thumbnail" style="width: 300px; height: auto;">
                            </div>
                            <div class="col">
                                <small class="text-muted">원본파일명</small><br>
                                <?= $info->popup_file_info->file_name_org ?>
                            </div>
                            <div class="col">
                                <small class="text-muted">가로해상도</small><br>
                                <?= $info->popup_file_info->image_width_txt ?>px
                            </div>
                            <div class="col">
                                <small class="text-muted">세로해상도</small><br>
                                <?= $info->popup_file_info->image_height_txt ?>px
                            </div>
                            <div class="col">
                                <small class="text-muted">사이즈</small><br>
                                <?= $info->popup_file_info->file_size_kb ?>KB
                            </div>
                        </div>
<?php   } ?>
                    </div>
                </div>

            </div>
            <div class="card-footer text-end">
                <div class="d-flex gap-2 justify-content-end">
                    <a href="/csl/popup/view/<?= $info->popup_idx ?>" class="btn btn-secondary">취소</a>
                    <button type="button" class="btn btn-primary" onclick="popupUpdate()">저장</button>
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

        $("input[name='display_yn'][value='<?=$info->display_yn ?>']").prop("checked", true);
        $("#start_date").inputmask("datetime", {inputFormat:"yyyy-mm-dd"});
        $("#end_date").inputmask("datetime", {inputFormat:"yyyy-mm-dd"});
    });

    function popupUpdate() {
        ajax1('/csl/popup/update', 'frm', 'popupUpdateAfter');
    }

    function popupUpdateAfter(proc_result) {
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
            $('#popup_file_hidden').val(info.file_id);
            var html = '<div class="row g-2 align-items-center">';
            html += '<div class="col"><img src="/csl/file/view/' + info.file_id + '" class="img-thumbnail" style="width: 300px; height: auto;"></div>';
            html += '<div class="col"><small class="text-muted">원본파일명</small><br>' + info.file_name_org + '</div>';
            html += '<div class="col"><small class="text-muted">가로해상도</small><br>' + info.image_width_txt + 'px</div>';
            html += '<div class="col"><small class="text-muted">세로해상도</small><br>' + info.image_height_txt + 'px</div>';
            html += '<div class="col"><small class="text-muted">사이즈</small><br>' + info.file_size_kb + 'KB</div>';
            html += '</div>';
            $('#popup_file_view').html(html);
        } else {
            alert(message);
        }
    }
</script>
