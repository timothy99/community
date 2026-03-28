<form id="frm" name="frm">

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
                            <input type="checkbox" id="send_to_me_yn" name="send_to_me_yn" value="Y">
                            <label for="send_to_me_yn" class="form-check-label">지금의 문의 내용을 내 메일로도 받겠습니다.</label>
                        </div>

                        <!-- 내용 입력 -->
                        <div class="mb-3">
                            <label for="contents" class="form-label">문의내용 <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="contents" name="contents" rows="8" placeholder="문의하실 내용을 입력하세요" required></textarea>
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
</script>
