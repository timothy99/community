<form id="frm" name="frm">

<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow">
                <div class="card-body p-4">
                    <h3 class="card-title text-center mb-4">암호 찾기</h3>
                    <!-- 아이디 입력 -->
                    <div class="mb-3">
                        <label for="member_id" class="form-label">아이디</label>
                        <input type="text" class="form-control" id="member_id" name="member_id" placeholder="아이디를 입력하세요" required>
                    </div>

                    <!-- 이름 입력 -->
                    <div class="mb-3">
                        <label for="member_name" class="form-label">이름</label>
                        <input type="text" class="form-control" id="member_name" name="member_name" placeholder="이름을 입력하세요" required>
                    </div>

                    <!-- 이메일 입력 -->
                    <div class="mb-3">
                        <label for="email" class="form-label">이메일 주소</label>
                        <input type="text" class="form-control" id="email" name="email" placeholder="이메일 주소를 입력하세요" required>
                    </div>

                    <!-- 찾기 버튼 -->
                    <div class="d-grid mb-3">
                        <button type="button" class="btn btn-primary btn-lg" onclick="findPassword()">찾기</button>
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

    function findPassword() {
        ajax1('/member/send/password', 'frm', 'findPasswordAfter');
    }

    function findPasswordAfter(proc_result) {
        var result = proc_result.result;
        var message = proc_result.message;
        var return_url = proc_result.return_url;

        alert(message);
        if (result == true) {
            location.href = return_url;
        }
    }
</script>
