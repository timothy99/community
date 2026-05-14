<?php
/**
 * @var object $board_config
 * @var object $info
 * @var array $language_list
 * @var array $product_category_list1
 * @var array $product_category_list2
 * @var array $product_category_list3
 */
?>

<form id="frm" name="frm">

<input type="hidden" id="product_idx" name="product_idx" value="<?= $info->product_idx ?>">
<input type="hidden" id="main_image_hidden" name="main_image_hidden" value="<?= $info->main_image_id ?>">
<input type="hidden" id="contents_code" name="contents_code" value='<?=base64_encode($info->contents) ?>'>
<input type="hidden" id="summer_code" name="summer_code">
<input type="hidden" id="upper_idx" name="upper_idx" value="0">

<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
        <h3>제품 등록/수정</h3>

        <div class="card mb-4">
            <div class="card-header bg-success bg-opacity-75 text-white">기본정보</div>
            <div class="card-body">
                <!-- 카테고리 -->
                <div class="row mb-3">

                    <div class="col-md-3 form-group form-inline">
                        <label for="language" class="form-label">언어</label>
                        <select class="form-select" id="language" name="language" onchange="getCategory1(this.value)">
                            <option value="">전체</option>
<?php       foreach ($language_list as $no => $val) { ?>
                            <option value="<?= $val->language_code ?>"><?= $val->language_name ?></option>
<?php       } ?>
                        </select>
                    </div>

                    <div class="col-md-3 form-group form-inline">
                        <label for="product_category_idx1" class="form-label">분류1</label>
                        <select class="form-select" id="product_category_idx1" name="product_category_idx1" onchange="getCategory2(this.value)">
                            <option value="0">전체</option>
<?php       foreach ($product_category_list1 as $no => $val) { ?>
                            <option value="<?= $val->product_category_idx ?>"><?= $val->category_name ?></option>
<?php       } ?>
                        </select>
                    </div>

                    <div class="col-md-3 form-group form-inline">
                        <label for="product_category_idx2" class="form-label">분류2</label>
                        <select class="form-select" id="product_category_idx2" name="product_category_idx2" onchange="getCategory3(this.value)">
                            <option value="0">전체</option>
<?php       foreach ($product_category_list2 as $no => $val) { ?>
                            <option value="<?= $val->product_category_idx ?>"><?= $val->category_name ?></option>
<?php       } ?>
                        </select>
                    </div>

                    <div class="col-md-3 form-group form-inline">
                        <label for="product_category_idx3" class="form-label">분류3</label>
                        <select class="form-select" id="product_category_idx3" name="product_category_idx3">
                            <option value="0">전체</option>
<?php       foreach ($product_category_list3 as $no => $val) { ?>
                            <option value="<?= $val->product_category_idx ?>"><?= $val->category_name ?></option>
<?php       } ?>
                        </select>
                    </div>
                </div>

                <!-- 제목 -->
                <div class="mb-3">
                    <label for="title" class="form-label">제목</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="제목을 입력하세요" value="<?= $info->title ?>">
                </div>

                <!-- 노출여부 -->
                <div class="mb-3 form-group form-inline">
                    <label for="display_yn" class="form-label">노출여부</label>
                    <select class="form-select w-auto" id="display_yn" name="display_yn">
                        <option value="Y">노출</option>
                        <option value="N">미노출</option>
                    </select>
                </div>

                <!-- 옵션 -->
                <div class="mb-3">
                    <label class="form-label">옵션</label>
                    <button type="button" class="btn btn-outline-secondary btn-sm ms-2" onclick="addOption()">추가</button>
                    <div id="option_list" name="option_list[]" class="mt-2">
<?php   foreach ($info->option_list as $no => $val) { ?>
                        <div class="row g-2 align-items-center mb-2" id="option_row_<?= $no ?>">
                            <div class="col-auto">
                                <input type="text" class="form-control form-control-sm" name="option_name[]" placeholder="옵션명 (예: 용량)" style="width:160px;" value="<?= $val->option_name ?>">
                            </div>
                            <div class="col-auto">
                                <input type="text" class="form-control form-control-sm" name="option_value[]" placeholder="옵션값 (예: 5ml)" style="width:160px;" value="<?= $val->option_value ?>">
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeOption(<?= $no ?>)">삭제</button>
                            </div>
                        </div>
<?php   } ?>
                    </div>
                </div>

                <!-- 내용 -->
                <div class="mb-3">
                    <label for="contents" class="form-label">내용</label>
                    <textarea class="form-control" id="contents" name="contents" rows="10" placeholder="내용을 입력하세요"></textarea>
                </div>

                <!-- 첨부파일 -->
                <div class="mb-3">
                    <label for="main_file" class="form-label">첨부파일</label>
                    <input type="file" class="form-control" id="main_file" name="main_file" onchange="uploadFile(this.id, 'image', 'uploadFileAfter')">
                    <div class="mb-2 mt-2 ml-2 mr-2 p-3 border rounded" id="main_file_list" style="display:none;">
<?php   foreach ($info->image_list as $no => $val) { ?>
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

                <!-- 대표 이미지 -->
                <div class="mb-3">
                    <label for="main_image" class="form-label">대표 이미지</label>
                    <input type="file" class="form-control" id="main_image" name="main_image" onchange="uploadFile(this.id, 'image', 'uploadMainImageAfter')">
                    <div class="mb-2 mt-2 ml-2 mr-2 p-3 border rounded" id="main_image_view" style="display:none;">
<?php   if ($info->main_image_file_info != null) { ?>
                        <div class="row g-2 align-items-center">
                            <div class="col">
                                <img src="/file/view/<?= $info->main_image_id ?>" class="img-thumbnail" style="width: 300px; height: auto;">
                            </div>
                            <div class="col">
                                <small class="text-muted">원본파일명</small><br>
                                <?= $info->main_image_file_info->file_name_org ?>
                            </div>
                            <div class="col">
                                <small class="text-muted">가로해상도</small><br>
                                <?= $info->main_image_file_info->image_width_txt ?>px
                            </div>
                            <div class="col">
                                <small class="text-muted">세로해상도</small><br>
                                <?= $info->main_image_file_info->image_height_txt ?>px
                            </div>
                            <div class="col">
                                <small class="text-muted">사이즈</small><br>
                                <?= $info->main_image_file_info->file_size_kb ?>KB
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeMainImage('<?= $info->main_image_id ?>')">삭제</button>
                            </div>
                        </div>
<?php   } ?>
                    </div>
                    <small class="text-muted">갤러리 형태에 사용하는 대표 이미지입니다. 목록에 이미지가 표시되는 형태가 아니라면 동작하지 않습니다.</small>
                </div>

                <!-- 등록일 -->
                <div class="mb-3">
                    <label for="reg_date" class="form-label">입력일</label>
                    <input type="text" class="form-control w-25" id="reg_date" name="reg_date" value="<?= $info->reg_date_txt ?>">
                </div>

                <!-- 조회수 -->
                <div class="mb-3">
                    <label for="hit_cnt" class="form-label">조회수</label>
                    <input type="number" class="form-control w-25" id="hit_cnt" name="hit_cnt" value="<?= $info->hit_cnt ?>">
                </div>
            </div>
            <div class="card-footer text-end">
                <div class="d-flex gap-2 justify-content-end">
<?php if ($info->product_idx > 0) { ?>
                    <a href="/csl/product/view/<?= $info->product_idx ?>" class="btn btn-secondary">취소</a>
<?php } else { ?>
                    <a href="/csl/product/list" class="btn btn-secondary">취소</a>
<?php } ?>
                    <button type="button" class="btn btn-primary" onclick="productUpdate()">저장</button>
                </div>
            </div>
        </div>

    </div>
</main>

</form>

<script>
    // 메뉴강조
    $(window).on('load', function() {
        $('#a-product-top').addClass('active-level-1').attr({'data-bs-toggle': 'collapse', 'aria-expanded': 'true'});
        $('#collapse-product-top').addClass('show').addClass('submenu');
        $('#li-product').addClass('active-level-2');

        $('#reg_date').inputmask('datetime', {inputFormat:'yyyy-mm-dd HH:MM:ss'});
        $('#main_image_view').show();
        $('#main_file_list').show();

        $('#language').val('<?= $info->language ?>');
        $('#display_yn').val('<?= $info->display_yn ?>');
        $('#product_category_idx1').val('<?= $info->product_category_idx1 ?>');
        $('#product_category_idx2').val('<?= $info->product_category_idx2 ?>');
        $('#product_category_idx3').val('<?= $info->product_category_idx3 ?>');

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

    function productUpdate() {
        ajax1('/csl/product/update', 'frm', 'productUpdateAfter');
    }

    function productUpdateAfter(proc_result) {
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

    let optionIndex = 0;
    function addOption() {
        const idx = optionIndex++;
        const row = `
        <div class="row g-2 align-items-center mb-2" id="option_row_${idx}">
            <div class="col-auto">
                <input type="text" class="form-control form-control-sm" name="option_name[]" placeholder="옵션명 (예: 용량)" style="width:160px;">
            </div>
            <div class="col-auto">
                <input type="text" class="form-control form-control-sm" name="option_value[]" placeholder="옵션값 (예: 5ml)" style="width:160px;">
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeOption(${idx})">삭제</button>
            </div>
        </div>`;
        $('#option_list').append(row);
    }

    function removeOption(idx) {
        $(`#option_row_${idx}`).remove();
    }

    function getCategory1(language_code) {
        $('#upper_idx').val(0);
        $('#language').val(language_code);
        ajax1('/csl/product/category', 'frm', 'getCategory1After');
    }

    function getCategory1After(data) {
        $('#product_category_idx1').html(data.html);
        $('#product_category_idx2').html('<option value="0">전체</option>');
        $('#product_category_idx3').html('<option value="0">전체</option>');
    }

    function getCategory2(upper_idx) {
        $('#upper_idx').val(upper_idx);
        ajax1('/csl/product/category', 'frm', 'getCategory2After');
    }

    function getCategory2After(data) {
        $('#product_category_idx2').html(data.html);
        $('#product_category_idx3').html('<option value="0">전체</option>');
    }

    function getCategory3(upper_idx) {
        $('#upper_idx').val(upper_idx);
        ajax1('/csl/product/category', 'frm', 'getCategory3After');
    }

    function getCategory3After(data) {
        $('#product_category_idx3').html(data.html);
    }
</script>
