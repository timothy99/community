<form id="frm" name="frm">

<input type="hidden" id="member_id" name="member_id" value="<?= $info->member_id ?>">

<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow">
                <div class="card-body p-4">
                    <h3 class="card-title text-center mb-4">암호 변경</h3>

                    <!-- 아이디 (읽기전용) -->
                    <div class="mb-3">
                        <label for="member_id_view" class="form-label">아이디</label>
                        <input type="text" class="form-control bg-light" id="member_id_view" value="<?= $info->member_id ?>" readonly>
                    </div>

                    <!-- 이름 (읽기전용) -->
                    <div class="mb-3">
                        <label for="member_name" class="form-label">이름</label>
                        <input type="text" class="form-control bg-light" id="member_name" value="<?= $info->member_name ?>" readonly>
                    </div>

                    <!-- 새 암호 -->
                    <div class="mb-3">
                        <label for="member_password" class="form-label">새 암호 <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="member_password" name="member_password" placeholder="8자리 이상 입력하세요" required>
                        <small class="text-muted">암호는 8자리 이상이어야 합니다.</small>
                    </div>

                    <!-- 암호 확인 -->
                    <div class="mb-3">
                        <label for="member_password_confirm" class="form-label">암호 확인 <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="member_password_confirm" name="member_password_confirm" placeholder="암호를 다시 입력하세요" required>
                    </div>

                    <!-- 구분선 -->
                    <hr class="my-4">

                    <!-- 저장 버튼 -->
                    <div class="d-grid mb-3">
                        <button type="button" class="btn btn-primary btn-lg" onclick="passwordChange()">암호 변경</button>
                    </div>

                    <!-- 취소 -->
                    <div class="text-center">
                        <a href="/member/mypage" class="text-decoration-none">취소</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</form>

<script>
    function passwordChange() {
        var member_password = $('#member_password').val();
        var member_password_confirm = $('#member_password_confirm').val();
        
        if (member_password == '') {
            alert('암호를 입력해주세요.');
            $('#member_password').focus();
            return false;
        }
        
        if (member_password.length < 8) {
            alert('암호는 8자리 이상 입력해야 합니다.');
            $('#member_password').focus();
            return false;
        }
        
        if (member_password_confirm == '') {
            alert('암호 확인을 입력해주세요.');
            $('#member_password_confirm').focus();
            return false;
        }
        
        if (member_password != member_password_confirm) {
            alert('입력된 암호가 다릅니다. 다시 확인해주세요.');
            $('#member_password_confirm').focus();
            return false;
        }
        
        ajax1('/member/password/change/update', 'frm', 'passwordChangeAfter');
    }

    function passwordChangeAfter(proc_result) {
        var result = proc_result.result;
        var message = proc_result.message;
        var return_url = proc_result.return_url;
        if (result == true) {
            alert(message);
            location.href = return_url;
        } else {
            alert(message);
        }
    }
</script>
