<?php
/**
 * @var object $config_info
 */
?>

<form id="frm" name="frm">

<input type="hidden" id="file_idxs" name="file_idxs" value="">

<!-- Main Content -->
<main id="main-content">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">문의하기</h4>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted mb-4">
                            문의사항을 남겨주시면 확인 후 빠른 시일 내에 답변드리겠습니다.
                        </p>

                        <!-- 이름 입력 -->
                        <div class="mb-3">
                            <label for="name" class="form-label">이름 <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="이름을 입력하세요" required>
                        </div>

                        <!-- 전화번호 입력 -->
                        <div class="mb-3">
                            <label for="phone" class="form-label">전화번호 <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="010-1234-5678" maxlength="13" required>
                            <small class="form-text text-muted">예: 010-1234-5678</small>
                        </div>

                        <!-- 이메일 입력 -->
                        <div class="mb-3">
                            <label for="email" class="form-label">답변 받을 이메일 <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="example@email.com" required>
<?php   if ($config_info->smtp_yn == 'Y') { ?>
                            <input type="checkbox" id="send_to_me_yn" name="send_to_me_yn" value="Y">
                            <label for="send_to_me_yn" class="form-check-label">지금의 문의 내용을 내 메일로도 받겠습니다.</label>
<?php   } ?>
                        </div>

                        <!-- 내용 입력 -->
                        <div class="mb-3">
                            <label for="contents" class="form-label">문의내용 <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="contents" name="contents" rows="8" placeholder="문의하실 내용을 입력하세요" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="main_file" class="form-label">첨부파일</label>
                            <input type="file" class="form-control" id="main_file" name="main_file" onchange="uploadFile(this.id, 'general', 'uploadInquiryFileAfter')">
                            <div class="mb-2 mt-2 ml-2 mr-2 p-3 border rounded" id="main_file_list" style="display:none;"></div>
                            <small class="form-text text-muted">파일은 업로드 즉시 첨부 목록에 추가됩니다.</small>
                        </div>

                        <!-- 안내 메시지 -->
                        <div class="alert alert-info" role="alert">
                            <i class="fas fa-info-circle"></i>
                            문의하신 내용은 검토 후 등록하신 이메일로 답변드립니다.
                        </div>

                        <!-- 버튼 영역 -->
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="/" class="btn btn-secondary">취소</a>
                            <button type="button" class="btn btn-primary" onclick="submitInquiry()">문의 등록</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

</form>

<script>
    $(window).on('load', function() {
        // 이메일과 전화번호에 inputmask 적용
        $('#email').inputmask({ alias: 'email'});
        $('#phone').inputmask('9{1,3}-9{1,4}-9{1,4}');
    });

    function submitInquiry() {
        // 필수 입력값 확인
        if ($('#name').val().trim() == '') {
            alert('이름을 입력해주세요.');
            $('#name').focus();
            return false;
        }

        if ($('#phone').val().trim() == '') {
            alert('전화번호를 입력해주세요.');
            $('#phone').focus();
            return false;
        }

        if ($('#email').val().trim() == '') {
            alert('이메일을 입력해주세요.');
            $('#email').focus();
            return false;
        }

        if ($('#contents').val().trim() == '') {
            alert('문의내용을 입력해주세요.');
            $('#contents').focus();
            return false;
        }

        // Ajax 전송
        ajax1('/inquiry/update', 'frm', 'submitInquiryAfter');
    }

    function submitInquiryAfter(proc_result) {
        var result = proc_result.result;
        var message = proc_result.message;
        var return_url = proc_result.return_url;
        
        alert(message);
        
        if (result == true) {
            location.href = return_url;
        }
    }

    function uploadInquiryFileAfter(proc_result) {
        var result = proc_result.result;
        var message = proc_result.message;
        var info = proc_result.info;

        if (result == true) {
            appendInquiryFile(info);
            $('#main_file').val('');
        } else {
            alert(message);
        }
    }

    function appendInquiryFile(info) {
        var fileIdsInput = $('#file_idxs');
        var currentIds = fileIdsInput.val();
        fileIdsInput.val(currentIds ? currentIds + '||' + info.file_id : info.file_id);

        var html = '<div class="row g-2 align-items-center mb-2" data-file-id="' + info.file_id + '" style="padding: 8px; border-radius: 4px;">';

        if (info.category === 'image') {
            html += '<div class="col-auto" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; overflow: hidden;"><img src="/file/view/' + info.file_id + '" class="img-thumbnail" style="max-height: 100px; width: auto; max-width: 100%;"></div>';
            html += '<div class="col"><small class="text-muted">원본파일명</small><br><a href="/file/download/' + info.file_id + '">' + info.file_name_org + '</a></div>';
            html += '<div class="col"><small class="text-muted">가로해상도</small><br>' + info.image_width_txt + 'px</div>';
            html += '<div class="col"><small class="text-muted">세로해상도</small><br>' + info.image_height_txt + 'px</div>';
        } else {
            html += '<div class="col-auto" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center;"><i class="' + getFileIcon(info.file_ext) + '" style="font-size: 80px;"></i></div>';
            html += '<div class="col"><small class="text-muted">원본파일명</small><br><a href="/file/download/' + info.file_id + '">' + info.file_name_org + '</a></div>';
            html += '<div class="col"><small class="text-muted">가로해상도</small><br>-</div>';
            html += '<div class="col"><small class="text-muted">세로해상도</small><br>-</div>';
        }

        html += '<div class="col"><small class="text-muted">사이즈</small><br>' + info.file_size_kb + 'KB</div>';
        html += '<div class="col-auto"><button type="button" class="btn btn-sm btn-danger" onclick="removeInquiryFile(\'' + info.file_id + '\')">삭제</button></div>';
        html += '</div>';

        $('#main_file_list').append(html).show();
    }

    function getFileIcon(fileExt) {
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

    function removeInquiryFile(fileId) {
        if (confirm('파일을 삭제하시겠습니까?')) {
            $('[data-file-id="' + fileId + '"]').remove();

            var fileIdsInput = $('#file_idxs');
            var currentIds = fileIdsInput.val();
            if (currentIds !== '') {
                var newIds = currentIds.split('||').filter(function(id) {
                    return id !== fileId;
                });
                fileIdsInput.val(newIds.join('||'));
            }

            if ($('#main_file_list [data-file-id]').length === 0) {
                $('#main_file_list').hide();
            }
        }
    }
</script>
