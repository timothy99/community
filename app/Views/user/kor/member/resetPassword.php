<form id="frm" name="frm">
<input type="hidden" name="reset_key" value="<?=$reset_key ?>">

<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow">
                <div class="card-body p-4">
                    <h3 class="card-title text-center mb-4">암호 변경</h3>
                    <!-- 아이디 입력 -->
                    <div class="mb-3">
                        <label for="member_id" class="form-label">아이디</label>
                        <input type="text" class="form-control" id="member_id" name="member_id" placeholder="아이디를 입력하세요" required>
                    </div>

                    <!-- 이메일 입력 -->
                    <div class="mb-3">
                        <label for="email" class="form-label">이메일</label>
                        <input type="text" class="form-control" id="email" name="email" placeholder="이메일을 입력하세요" required>
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

                    <!-- 변경 버튼 -->
                    <div class="d-grid mb-3">
                        <button type="button" class="btn btn-primary btn-lg" onclick="passwordUpdate()">변경</button>
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

    function passwordUpdate() {
        ajax1('/member/update/password', 'frm', 'passwordUpdateAfter');
    }

    function passwordUpdateAfter(proc_result) {
        var result = proc_result.result;
        var message = proc_result.message;
        var return_url = proc_result.return_url;

        alert(message);
        if (result == true) {
            location.href = return_url;
        }
    }
</script>
