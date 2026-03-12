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
        <h3><?= $board_config->title ?></h3>

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
                    <label for="title" class="form-label">제목</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="제목을 입력하세요" value="<?= $info->title ?>">
                </div>

                <!-- 내용 -->
                <div class="mb-3">
                    <label for="contents" class="form-label">내용</label>
                    <textarea class="form-control" id="contents" name="contents" rows="10" placeholder="내용을 입력하세요"><?= $info->contents ?></textarea>
                </div>

                <!-- 첨부파일 -->
                <div class="mb-3">
                    <label for="main_file" class="form-label">첨부파일</label>
                    <input type="file" class="form-control" id="main_file" name="main_file" onchange="uploadFile(this.id, 'board', 'uploadFileAfter')">
                    <div class="mb-2 mt-2 ml-2 mr-2 p-3 border rounded" id="main_file_list" style="display:none;">
<?php   foreach ($info->file_list as $no => $val) { ?>
                        <div class="row g-2 align-items-center mb-2" data-file-id="<?= $val->file_id ?>" style="padding: 8px; border-radius: 4px;">
                            <div class="col-auto" style="width: 30px; text-align: center;">
                                <i class="fas fa-grip-vertical text-muted" style="font-size: 16px;" title="드래그하여 순서 변경"></i>
                            </div>
                            <div class="col-auto" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
<?php       if ($val->file_info->category == 'image') { ?>
                                <img src="/file/view/<?= $val->file_id ?>" class="img-thumbnail" style="max-height: 100px; width: auto; max-width: 100%;">
<?php       } else { ?>
                                <i class="<?= $val->file_info->icon_class ?>" style="font-size: 80px;"></i>
<?php       } ?>
                            </div>
                            <div class="col">
                                <small class="text-muted">원본파일명</small><br>
                                <a href="/file/download/<?= $val->file_id ?>">
                                    <?= $val->file_info->file_name_org ?>
                                </a>
                            </div>
                            <div class="col">
                                <small class="text-muted">가로해상도</small><br>
                                <?= $val->file_info->image_width_txt ?>px
                            </div>
                            <div class="col">
                                <small class="text-muted">세로해상도</small><br>
                                <?= $val->file_info->image_height_txt ?>px
                            </div>
                            <div class="col">
                                <small class="text-muted">사이즈</small><br>
                                <?= $val->file_info->file_size_kb ?>KB
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeFile('<?= $val->file_id ?>')">삭제</button>
                            </div>
                        </div>
<?php   } ?>
                    </div>
                </div>

<?php   if ($board_config->main_image_yn == 'Y') { ?>
                <!-- 대표 이미지 -->
                <div class="mb-3">
                    <label for="main_image" class="form-label">대표 이미지</label>
                    <input type="file" class="form-control" id="main_image" name="main_image" onchange="uploadFile(this.id, 'image', 'uploadMainImageAfter')">
                    <div class="mb-2 mt-2 ml-2 mr-2 p-3 border rounded" id="main_image_view" style="display:none;">
<?php       if ($info->main_image_info != null) { ?>
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
                            <div class="col-auto">
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeMainImage('<?= $info->main_image_id ?>')">삭제</button>
                            </div>
                        </div>
<?php       } ?>
                    </div>
                    <small class="text-muted">갤러리 형태에 사용하는 대표 이미지입니다. 목록에 이미지가 표시되는 형태가 아니라면 동작하지 않습니다.</small>
                </div>
<?php   } ?>

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
                            <div class="col-auto">
                                <button type="button" class="btn btn-sm btn-danger" onclick="removePdfFile('<?= $info->pdf_file_id ?>')">삭제</button>
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
<?php   if ($info->main_image_info != null) { ?>
        $('#main_image_view').show();
<?php   } ?>
<?php   if ($info->pdf_file_info != null) { ?>
        $('#pdf_file_view').show();
<?php   } ?>
<?php   if ($board_config->reg_date_yn == 'Y') { ?>
        $('#reg_date').inputmask('datetime', {inputFormat:'yyyy-mm-dd HH:MM:ss'});
<?php   } ?>
<?php   if (count($info->file_list) > 0) { ?>
        $('#main_file_list').show();
<?php   } ?>

        initSummernote('#contents', { focus: false });
        
        // 파일 목록 드래그 앤 드롭 기능 초기화
        $('#main_file_list').sortable({
            axis: 'y',
            cursor: 'move',
            opacity: 0.7,
            tolerance: 'pointer',
            update: function(event, ui) {
                updateFileIdxsOrder();
            },
            start: function(event, ui) {
                ui.item.addClass('dragging');
            },
            stop: function(event, ui) {
                ui.item.removeClass('dragging');
            }
        });
        
        // 드래그 가능 시각적 피드백
        $('#main_file_list .row[data-file-id]').css({
            'cursor': 'move',
            'transition': 'background-color 0.2s'
        }).hover(
            function() { $(this).css('background-color', '#f8f9fa'); },
            function() { $(this).css('background-color', ''); }
        );
        
        // 기존 파일 목록의 순서를 file_idxs에 저장
        if ($('#main_file_list [data-file-id]').length > 0) {
            updateFileIdxsOrder();
        }
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
            html += '<div class="col"><small class="text-muted">원본파일명</small><br><a href="/file/download/' + info.file_id + '">' + info.file_name_org + '</a></div>';
            html += '<div class="col"><small class="text-muted">사이즈</small><br>' + info.file_size_kb + 'KB</div>';
            html += '<div class="col-auto"><button type="button" class="btn btn-sm btn-danger" onclick="removePdfFile(\'' + info.file_id + '\')">삭제</button></div>';
            html += '</div>';
            $('#pdf_file_view').html(html);
            $('#pdf_file_view').show();
        } else {
            alert(message);
        }
    }

    function uploadFileAfter(proc_result) {
        var result = proc_result.result;
        var message = proc_result.message;
        var info = proc_result.info;
        if (result == true) {
            // 파일 ID를 hidden input에 추가 (다중 파일 관리)
            var fileIdsInput = $('#file_idxs');
            if (fileIdsInput.length === 0) {
                $('<input>').attr({
                    type: 'hidden',
                    id: 'file_idxs',
                    name: 'file_idxs',
                    value: info.file_id
                }).appendTo('#frm');
            } else {
                var currentIds = fileIdsInput.val();
                fileIdsInput.val(currentIds ? currentIds + '||' + info.file_id : info.file_id);
            }

            var html = '<div class="row g-2 align-items-center mb-2" data-file-id="' + info.file_id + '" style="padding: 8px; border-radius: 4px;">';
            html += '<div class="col-auto" style="width: 30px; text-align: center;"><i class="fas fa-grip-vertical text-muted" style="font-size: 16px;" title="드래그하여 순서 변경"></i></div>';
            
            if (info.category === 'image') {
                // 이미지 파일인 경우
                html += '<div class="col-auto" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; overflow: hidden;"><img src="/file/view/' + info.file_id + '" class="img-thumbnail" style="max-height: 100px; width: auto; max-width: 100%;"></div>';
                html += '<div class="col"><small class="text-muted">원본파일명</small><br><a href="/file/download/' + info.file_id + '">' + info.file_name_org + '</a></div>';
                html += '<div class="col"><small class="text-muted">가로해상도</small><br>' + info.image_width_txt + 'px</div>';
                html += '<div class="col"><small class="text-muted">세로해상도</small><br>' + info.image_height_txt + 'px</div>';
                html += '<div class="col"><small class="text-muted">사이즈</small><br>' + info.file_size_kb + 'KB</div>';
            } else {
                // 일반 파일인 경우 - 아이콘 표시
                var iconClass = getFileIcon(info.file_ext);
                html += '<div class="col-auto" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center;"><i class="' + iconClass + '" style="font-size: 80px;"></i></div>';
                html += '<div class="col"><small class="text-muted">원본파일명</small><br><a href="/file/download/' + info.file_id + '">' + info.file_name_org + '</a></div>';
                html += '<div class="col"><small class="text-muted">가로해상도</small><br>-</div>';
                html += '<div class="col"><small class="text-muted">세로해상도</small><br>-</div>';
                html += '<div class="col"><small class="text-muted">사이즈</small><br>' + info.file_size_kb + 'KB</div>';
            }
            
            html += '<div class="col-auto">';
            html += '<button type="button" class="btn btn-sm btn-danger" onclick="removeFile(\'' + info.file_id + '\')">삭제</button>';
            html += '</div>';
            html += '</div>';
            
            var $newRow = $(html);
            $newRow.css({
                'cursor': 'move',
                'transition': 'background-color 0.2s'
            }).hover(
                function() { $(this).css('background-color', '#f8f9fa'); },
                function() { $(this).css('background-color', ''); }
            );
            
            $('#main_file_list').append($newRow);
            $('#main_file_list').show();
        } else {
            alert(message);
        }
    }

    function getFileIcon(fileExt) {
        // Font Awesome 아이콘 클래스 반환
        var iconMap = {
            'pdf': 'fas fa-file-pdf text-danger',
            'doc': 'fas fa-file-word text-primary',
            'docx': 'fas fa-file-word text-primary',
            'xls': 'fas fa-file-excel text-success',
            'xlsx': 'fas fa-file-excel text-success',
            'ppt': 'fas fa-file-powerpoint text-warning',
            'pptx': 'fas fa-file-powerpoint text-warning',
            'zip': 'fas fa-file-archive text-secondary',
            'rar': 'fas fa-file-archive text-secondary',
            'txt': 'fas fa-file-alt text-muted',
            'csv': 'fas fa-file-csv text-success'
        };
        
        return iconMap[fileExt] || 'fas fa-file text-secondary';
    }

    function removeFile(fileId) {
        if (confirm('파일을 삭제하시겠습니까?')) {
            // HTML에서 제거
            $('[data-file-id="' + fileId + '"]').remove();
            
            // hidden input에서 file_id 제거
            var fileIdsInput = $('#file_idxs');
            if (fileIdsInput.length > 0) {
                var currentIds = fileIdsInput.val().split('||');
                var newIds = currentIds.filter(function(id) { return id !== fileId; });
                fileIdsInput.val(newIds.join('||'));
            }
        }
    }

    function updateFileIdxsOrder() {
        // DOM 순서대로 file_idxs 업데이트
        var fileIds = [];
        $('#main_file_list [data-file-id]').each(function() {
            fileIds.push($(this).data('file-id'));
        });
        
        if (fileIds.length > 0) {
            var fileIdsInput = $('#file_idxs');
            if (fileIdsInput.length === 0) {
                $('<input>').attr({
                    type: 'hidden',
                    id: 'file_idxs',
                    name: 'file_idxs',
                    value: fileIds.join('||')
                }).appendTo('#frm');
            } else {
                fileIdsInput.val(fileIds.join('||'));
            }
        }
    }

    function removeMainImage(fileId) {
        if (confirm('대표 이미지를 삭제하시겠습니까?')) {
            $('#main_image_hidden').val('');
            $('#main_image_view').hide();
        }
    }

    function removePdfFile(fileId) {
        if (confirm('PDF 파일을 삭제하시겠습니까?')) {
            $('#pdf_file_hidden').val('');
            $('#pdf_file_view').hide();
        }
    }
</script>
