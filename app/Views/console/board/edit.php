<form id="frm" name="frm">

<input type="hidden" id="board_idx" name="board_idx" value="<?= $info->board_idx ?>">
<input type="hidden" id="board_id" name="board_id" value="<?= $info->board_id ?>">
<input type="hidden" id="main_image_hidden" name="main_image_hidden" value="<?= $info->main_image_id ?>">
<input type="hidden" id="pdf_file_hidden" name="pdf_file_hidden" value="<?= $info->pdf_file_id ?>">

<input type="hidden" id="contents_code" name="contents_code" value='<?=base64_encode($info->contents) ?>'>
<input type="hidden" id="summer_code" name="summer_code">

<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
        <h3>게시판 글쓰기</h3>

        <div class="card mb-4">
            <div class="card-header bg-success bg-opacity-75 text-white">기본정보</div>
            <div class="card-body">

                <!-- 공지여부 -->
                <div class="mb-3">
                    <label class="form-label">공지여부</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="notice_yn" id="notice_yn_y" value="Y">
                            <label class="form-check-label" for="notice_yn_y">공지</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="notice_yn" id="notice_yn_n" value="N">
                            <label class="form-check-label" for="notice_yn_n">일반</label>
                        </div>
                    </div>
                </div>

<?php   if ($board_config->category_yn == 'Y') { ?>
                <!-- 카테고리 -->
                <div class="mb-3">
                    <label for="category" class="form-label">카테고리</label>
                    <select class="form-select" id="category" name="category">
                        <option value="">선택하세요</option>
<?php       foreach ($board_config->category_arr as $no => $val) { ?>
                        <option value="<?=$val ?>"><?=$val ?></option>
<?php       } ?>
                    </select>
                </div>
<?php   } ?>

                <!-- 제목 -->
                <div class="mb-3">
                    <label for="title" class="form-label">제목 <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="제목을 입력하세요" value="<?= $info->title ?>">
                </div>

                <!-- 내용 -->
                <div class="mb-3">
                    <label for="contents" class="form-label">내용 <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="contents" name="contents" rows="10" placeholder="내용을 입력하세요"><?= $info->contents ?></textarea>
                </div>

                <!-- 대표 이미지 -->
                <div class="mb-3">
                    <label for="main_image" class="form-label">대표 이미지</label>
                    <input type="file" class="form-control" id="main_image" name="main_image" onchange="uploadFile(this.id, 'image', 'uploadMainImageAfter')">
                    <div class="mb-2 mt-2 ml-2 mr-2 p-3 border rounded" id="main_image_view" style="display:none;">
<?php   if ($info->main_image_info != null) { ?>
                        <div class="row g-2 align-items-center">
                            <div class="col">
                                <img src="/file/view/<?= $info->main_image_id ?>" class="img-thumbnail" style="width: 300px; height: auto;">
                            </div>
                            <div class="col">
                                <small class="text-muted">원본파일명</small><br>
                                <?= $info->main_image_info->file_name_org ?>
                            </div>
                            <div class="col">
                                <small class="text-muted">가로해상도</small><br>
                                <?= $info->main_image_info->image_width_txt ?>px
                            </div>
                            <div class="col">
                                <small class="text-muted">세로해상도</small><br>
                                <?= $info->main_image_info->image_height_txt ?>px
                            </div>
                            <div class="col">
                                <small class="text-muted">사이즈</small><br>
                                <?= $info->main_image_info->file_size_kb ?>KB
                            </div>
                        </div>
<?php   } ?>
                    </div>
                    <small class="text-muted">갤러리 형태에 사용하는 대표 이미지입니다. 목록에 이미지가 표시되는 형태가 아니라면 동작하지 않습니다.</small>
                </div>

<?php   if ($board_config->url_link_yn == 'Y') { ?>
                <!-- URL 링크 -->
                <div class="mb-3">
                    <label for="url_link" class="form-label">링크 URL</label>
                    <input type="text" class="form-control" id="url_link" name="url_link" placeholder="http로 시작하는 주소 전체" value="<?= $info->url_link ?>">
                </div>
<?php   } ?>

<?php   if ($board_config->pdf_yn == 'Y') { ?>
                <!-- PDF 파일 -->
                <div class="mb-3">
                    <label for="pdf_file" class="form-label">PDF 파일</label>
                    <input type="file" class="form-control" id="pdf_file" name="pdf_file" onchange="uploadFile(this.id, 'general', 'uploadPdfFileAfter')">
                    <div class="mb-2 mt-2 ml-2 mr-2 p-3 border rounded" id="pdf_file_view" style="display:none;">
<?php       if ($info->pdf_file_info != null) { ?>
                        <div class="row g-2 align-items-center">
                            <div class="col">
                                <small class="text-muted">원본파일명</small><br>
                                <?= $info->pdf_file_info->file_name_org ?>
                            </div>
                            <div class="col">
                                <small class="text-muted">사이즈</small><br>
                                <?= $info->pdf_file_info->file_size_kb ?>KB
                            </div>
                        </div>
<?php       } ?>
                    </div>
                </div>
<?php   } ?>

<?php   if ($board_config->youtube_yn == 'Y') { ?>
                <!-- 유튜브 링크 -->
                <div class="mb-3">
                    <label for="youtube_link" class="form-label">유튜브 링크</label>
                    <input type="text" class="form-control" id="youtube_link" name="youtube_link" placeholder="유튜브 링크를 입력하세요" value="<?= $info->youtube_link ?>">
                </div>
<?php   } ?>

<?php   if ($board_config->reg_date_yn == 'Y') { ?>
                <!-- 등록일 -->
                <div class="mb-3">
                    <label for="reg_date" class="form-label">등록일</label>
                    <input type="text" class="form-control w-25" id="reg_date" name="reg_date" value="<?= $info->reg_date_txt ?>">
                </div>
<?php   } ?>

<?php   if ($board_config->hit_edit_yn == 'Y') { ?>
                <!-- 조회수 -->
                <div class="mb-3">
                    <label for="hit_cnt" class="form-label">조회수</label>
                    <input type="number" class="form-control w-25" id="hit_cnt" name="hit_cnt" value="<?= $info->hit_cnt ?>">
                </div>
<?php   } ?>
            </div>
            <div class="card-footer text-end">
                <div class="d-flex gap-2 justify-content-end">
<?php if ($info->board_idx > 0) { ?>
                    <a href="/csl/board/<?= $info->board_id ?>/view/<?= $info->board_idx ?>" class="btn btn-secondary">취소</a>
<?php } else { ?>
                    <a href="/csl/board/<?= $info->board_id ?>/list" class="btn btn-secondary">취소</a>
<?php } ?>
                    <button type="button" class="btn btn-primary" onclick="boardUpdate()">저장</button>
                </div>
            </div>
        </div>

    </div>
</main>

</form>

<script>
    // 메뉴강조
    $(window).on('load', function() {
        $('#a-board-top').addClass('active-level-1').attr({'data-bs-toggle': 'collapse', 'aria-expanded': 'true'});
        $('#collapse-board-top').addClass('show').addClass('submenu');
        $('#a-board-<?= $info->board_id ?>').addClass('active-level-2');

        $('input[name="notice_yn"][value="<?=$info->notice_yn ?>"]').prop('checked', true);
        $('#category').val('<?= $info->category ?>');
<?php if ($info->main_image_info != null) { ?>
        $('#main_image_view').show();
<?php } ?>
<?php if ($info->pdf_file_info != null) { ?>
        $('#pdf_file_view').show();
<?php } ?>
<?php if ($board_config->reg_date_yn == 'Y') { ?>
        $('#reg_date').inputmask('datetime', {inputFormat:'yyyy-mm-dd HH:MM:ss'});
<?php } ?>

        initSummernote('#contents', { focus: false });
    });

    function boardUpdate() {
        ajax1('/csl/board/<?= $info->board_id ?>/update', 'frm', 'boardUpdateAfter');
    }

    function boardUpdateAfter(proc_result) {
        var result = proc_result.result;
        var message = proc_result.message;
        var return_url = proc_result.return_url;
        if (result == true) {
            location.href = return_url;
        } else {
            alert(message);
        }
    }

    function uploadMainImageAfter(proc_result) {
        var result = proc_result.result;
        var message = proc_result.message;
        var info = proc_result.info;
        if (result == true) {
            $('#main_image_hidden').val(info.file_id);
            var html = '<div class="row g-2 align-items-center">';
            html += '<div class="col"><img src="/file/view/' + info.file_id + '" class="img-thumbnail" style="width: 300px; height: auto;"></div>';
            html += '<div class="col"><small class="text-muted">원본파일명</small><br>' + info.file_name_org + '</div>';
            html += '<div class="col"><small class="text-muted">가로해상도</small><br>' + info.image_width_txt + 'px</div>';
            html += '<div class="col"><small class="text-muted">세로해상도</small><br>' + info.image_height_txt + 'px</div>';
            html += '<div class="col"><small class="text-muted">사이즈</small><br>' + info.file_size_kb + 'KB</div>';
            html += '</div>';
            $('#main_image_view').html(html);
            $('#main_image_view').show();
        } else {
            alert(message);
        }
    }

    function uploadPdfFileAfter(proc_result) {
        var result = proc_result.result;
        var message = proc_result.message;
        var info = proc_result.info;
        if (result == true) {
            $('#pdf_file_hidden').val(info.file_id);
            var html = '<div class="row g-2 align-items-center">';
            html += '<div class="col"><a href="/file/download/' + info.file_id + '">파일 받기</a></div>';
            html += '<div class="col"><small class="text-muted">원본파일명</small><br>' + info.file_name_org + '</div>';
            html += '<div class="col"><small class="text-muted">사이즈</small><br>' + info.file_size_kb + 'KB</div>';
            html += '</div>';
            $('#pdf_file_view').html(html);
            $('#pdf_file_view').show();
        } else {
            alert(message);
        }
    }
</script>
