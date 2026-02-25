<form id="frm" name="frm">

<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow">
                <div class="card-body p-4">
                    <h3 class="card-title text-center mb-4">회원가입</h3>

                    <!-- 아이디 입력 -->
                    <div class="mb-3">
                        <label for="member_id" class="form-label">아이디 <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="member_id" name="member_id" placeholder="아이디를 입력하세요" required>
                            <button class="btn btn-secondary" type="button" onclick="checkDuplicateId()">중복확인</button>
                        </div>
                    </div>

                    <!-- 암호 입력 -->
                    <div class="mb-3">
                        <label for="member_password" class="form-label">암호 <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="member_password" name="member_password" placeholder="암호를 입력하세요" required>
                    </div>

                    <!-- 암호 확인 -->
                    <div class="mb-3">
                        <label for="member_password_confirm" class="form-label">암호 확인 <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="member_password_confirm" name="member_password_confirm" placeholder="암호를 다시 입력하세요" required>
                    </div>

                    <!-- 이름 입력 -->
                    <div class="mb-3">
                        <label for="member_name" class="form-label">이름 <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="member_name" name="member_name" placeholder="이름을 입력하세요" required>
                    </div>

                    <!-- 별명 입력 -->
                    <div class="mb-3">
                        <label for="member_nickname" class="form-label">별명 <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="member_nickname" name="member_nickname" placeholder="별명을 입력하세요" required>
                    </div>

                    <!-- 이메일 입력 -->
                    <div class="mb-3">
                        <label for="email" class="form-label">이메일</label>
                        <input type="text" class="form-control" id="email" name="email" placeholder="이메일을 입력하세요">
                    </div>

                    <!-- 수신 동의 -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="email_yn" name="email_yn" value="Y" checked>
                            <label class="form-check-label" for="email_yn">
                                뉴스레터 수신 동의
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="sms_yn" name="sms_yn" value="Y" checked>
                            <label class="form-check-label" for="sms_yn">
                                SMS 수신 동의
                            </label>
                        </div>
                    </div>

                    <!-- 휴대전화 번호 -->
                    <div class="mb-3">
                        <label for="phone" class="form-label">휴대전화</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="형식에 맞게 입력해주세요 (예: 010-1234-5678)" maxlength="13">
                    </div>

                    <!-- 우편번호 -->
                    <div class="mb-3">
                        <label for="post_code" class="form-label">우편번호</label>
                        <div class="input-group">
                            <input type="text" class="form-control bg-light" id="post_code" name="post_code" placeholder="우편번호" maxlength="5" readonly>
                            <button class="btn btn-secondary" type="button" onclick="postcodeShow('postcode_div', 'postcode_wrap')">우편번호 찾기</button>
                        </div>
                    </div>

                    <!-- 우편번호 -->
                    <div class="mb-3" style="display:none" id="postcode_div">
                        <div id="postcode_wrap" style="display:block;border:1px solid;width:100%;height:300px;margin:5px 0;position:relative">
                            <img src="//t1.daumcdn.net/postcode/resource/images/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" onclick="postcodeClose('postcode_div')" alt="접기 버튼">
                        </div>
                    </div>

                    <!-- 주소1 -->
                    <div class="mb-3">
                        <label for="addr1" class="form-label">주소</label>
                        <input type="text" class="form-control bg-light" id="addr1" name="addr1" placeholder="주소" readonly>
                    </div>

                    <!-- 주소2 -->
                    <div class="mb-3">
                        <label for="addr2" class="form-label">상세주소</label>
                        <input type="text" class="form-control" id="addr2" name="addr2" placeholder="상세주소를 입력하세요">
                    </div>

                    <!-- 구분선 -->
                    <hr class="my-4">

                    <!-- 회원가입 버튼 -->
                    <div class="d-grid mb-3">
                        <button type="button" class="btn btn-primary btn-lg" onclick="register()">회원가입</button>
                    </div>

                    <!-- 로그인 페이지로 이동 -->
                    <div class="text-center">
                        <span class="text-muted">이미 계정이 있으신가요?</span>
                        <a href="/member/login" class="text-decoration-none">로그인</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</form>

<script>
    $(window).on('load', function() {
        $('#email').inputmask({ alias: 'email'});
        $('#phone').inputmask('9{1,3}-9{1,4}-9{1,4}');
    });

    // 우편번호 검색된 결과값으로 페이지에 맞는 데이터 넣기
    function postcodeAfter(data) {
        // 우편번호와 주소 정보를 해당 필드에 넣는다.
        document.getElementById('post_code').value = data.zonecode;
        document.getElementById('addr1').value = data.addr1;
        document.getElementById('addr2').value = data.addr2;
        // 커서를 상세주소 필드로 이동한다.
        document.getElementById('addr2').focus();
    }

    function checkDuplicateId() {
        ajax1('/member/register/duplicate', 'frm', 'duplicateAfter');
    }

    function duplicateAfter(proc_result) {
        var result = proc_result.result;
        var message = proc_result.message;
        alert(message);
        if(result == true) {
            $('#member_id').focus();
        }
    }

    function register() {
        ajax1('/member/register/process', 'frm', 'registerAfter');
    }

    function registerAfter(proc_result) {
        var result = proc_result.result;
        var message = proc_result.message;
        var return_url = proc_result.return_url;
        alert(message);
        if(result == true) {
            location.href = return_url;
        }
    }

</script>