<form id="frm" name="frm">

<input type="hidden" id="slide_idx" name="slide_idx" value="<?= $info->slide_idx ?>">
<input type="hidden" id="slide_file_hidden" name="slide_file_hidden" value="<?= $info->slide_file ?>">

<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
        <h3>슬라이드</h3>

        <div class="card mb-4">
            <div class="card-header bg-success bg-opacity-75 text-white">기본정보</div>
            <div class="card-body">
                <!-- 정렬순서 -->
                <div class="mb-3">
                    <label for="order_no" class="form-label">정렬순서 <span class="text-danger">*</span></label>
                    <input type="number" class="form-control w-25" id="order_no" name="order_no" placeholder="숫자를 입력하세요" value="<?= $info->order_no ?>">
                </div>

                <!-- 제목 -->
                <div class="mb-3">
                    <label for="title" class="form-label">제목 <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="제목을 입력하세요" value="<?= $info->title ?>">
                </div>

                <!-- 소제목 -->
                <div class="mb-3">
                    <label for="sub_title" class="form-label">소제목</label>
                    <input type="text" class="form-control" id="sub_title" name="sub_title" placeholder="소제목을 입력하세요" value="<?= $info->sub_title ?>">
                </div>

                <!-- 내용 (alt) -->
                <div class="mb-3">
                    <label for="contents" class="form-label">내용 <span class="text-muted small">(이미지 alt 텍스트)</span></label>
                    <textarea class="form-control" id="contents" name="contents" rows="3" placeholder="슬라이드 이미지의 대체 텍스트를 입력하세요"><?= $info->contents ?></textarea>
                </div>

                <!-- URL 링크 -->
                <div class="mb-3">
                    <label for="url_link" class="form-label">링크 URL <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="url_link" name="url_link" placeholder="http로 시작하는 주소 전체. 내부 링크는 /부터 입력도 가능합니다." value="<?= $info->url_link ?>">
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
                            <label class="form-check-label" for="display_yn_n">미노출</label>
                        </div>
                    </div>
                </div>

                <!-- 슬라이드 이미지 -->
                <div class="mb-3">
                    <label for="slide_file" class="form-label">슬라이드 이미지</label>
                    <input type="file" class="form-control" id="slide_file" name="slide_file" onchange="uploadFile(this.id, 'image', 'uploadAfter')">
                    <div class="mb-2 mt-2" id="slide_file_view">
<?php   if ($info->slide_file_info != null) { ?>
                        <div class="row g-2 align-items-center">
                            <div class="col">
                                <img src="/csl/file/view/<?= $info->slide_file ?>" class="img-thumbnail" style="width: 300px; height: auto;">
                            </div>
                            <div class="col">
                                <small class="text-muted">원본파일명</small><br>
                                <?= $info->slide_file_info->file_name_org ?>
                            </div>
                            <div class="col">
                                <small class="text-muted">가로해상도</small><br>
                                <?= $info->slide_file_info->image_width_txt ?>px
                            </div>
                            <div class="col">
                                <small class="text-muted">세로해상도</small><br>
                                <?= $info->slide_file_info->image_height_txt ?>px
                            </div>
                            <div class="col">
                                <small class="text-muted">사이즈</small><br>
                                <?= $info->slide_file_info->file_size_kb ?>KB
                            </div>
                        </div>
<?php   } ?>
                    </div>
                </div>

            </div>
            <div class="card-footer text-end">
                <div class="d-flex gap-2 justify-content-end">
                    <button type="button" class="btn btn-primary" onclick="slideUpdate()">저장</button>
                    <a href="/csl/slide/list" class="btn btn-secondary">목록</a>
                </div>
            </div>
        </div>

    </div>
</main>

</form>

<script>
    // 메뉴강조
    $(window).on('load', function() {
        // $('#li-slide').addClass('active-level-1').attr({'data-bs-toggle': 'collapse', 'aria-expanded': 'true'});
        $('#li-slide').addClass('active-level-1');

        $("input[name='display_yn'][value='<?=$info->display_yn ?>']").prop("checked", true);
        $("#start_date").inputmask("datetime", {inputFormat:"yyyy-mm-dd"});
        $("#end_date").inputmask("datetime", {inputFormat:"yyyy-mm-dd"});
    });

    function slideUpdate() {
        ajax1('/csl/slide/update', 'frm', 'slideUpdateAfter');
    }

    function slideUpdateAfter(proc_result) {
        var result = proc_result.result;
        var message = proc_result.message;
        if (result == true) {
            location.href = '/csl/slide/list';
        } else {
            alert(message);
        }
    }

    function uploadAfter(proc_result) {
        var result = proc_result.result;
        var message = proc_result.message;
        var info = proc_result.info;
        if (result == true) {
            $('#slide_file_hidden').val(info.file_id);
            var html = '<div class="row g-2 align-items-center">';
            html += '<div class="col"><img src="/csl/file/view/' + info.file_id + '" class="img-thumbnail" style="width: 300px; height: auto;"></div>';
            html += '<div class="col"><small class="text-muted">원본파일명</small><br>' + info.file_name_org + '</div>';
            html += '<div class="col"><small class="text-muted">가로해상도</small><br>' + info.image_width_txt + 'px</div>';
            html += '<div class="col"><small class="text-muted">세로해상도</small><br>' + info.image_height_txt + 'px</div>';
            html += '<div class="col"><small class="text-muted">사이즈</small><br>' + info.file_size_kb + 'KB</div>';
            html += '</div>';
            $('#slide_file_view').html(html);
        } else {
            alert(message);
        }
    }
</script>