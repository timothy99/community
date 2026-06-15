<form id="frm" name="frm">

<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow">
                <div class="card-body p-4">
                    <h3 class="card-title text-center mb-4">인증번호 입력</h3>
                    <!-- 인증번호 입력 -->
                    <div class="mb-3">
                        <label for="auth_number" class="form-label">인증번호</label>
                        <input type="text" class="form-control" id="auth_number" name="auth_number" placeholder="이메일로 전송된 인증번호를 입력하세요" required>
                    </div>

                    <!-- 인증 버튼 -->
                    <div class="d-grid mb-3">
                        <button type="button" class="btn btn-primary btn-lg" onclick="authenticate()">인증</button>
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
    });

    function authenticate() {
        ajax1('/member/authenticate/confirm', 'frm', 'authenticateAfter');
    }

    function authenticateAfter(proc_result) {
        var result = proc_result.result;
        var message = proc_result.message;
        var return_url = proc_result.return_url;

        alert(message);
        if (result == true) {
            location.href = return_url;
        }
    }
</script>
