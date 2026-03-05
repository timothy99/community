<form id="frm" name="frm">

<input type="hidden" id="member_id" name="member_id" value="<?= $info->member_id ?>">

<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
        <h3>회원관리 - 암호 변경</h3>

        <div class="card mb-4">
            <div class="card-header bg-success bg-opacity-75 text-white">암호 변경</div>
            <div class="card-body">
                <!-- 아이디 (읽기전용) -->
                <div class="mb-3">
                    <label for="member_id_view" class="form-label">아이디</label>
                    <input type="text" class="form-control bg-light w-25" id="member_id_view" value="<?= $info->member_id ?>" readonly>
                </div>

                <!-- 이름 (읽기전용) -->
                <div class="mb-3">
                    <label for="member_name" class="form-label">이름</label>
                    <input type="text" class="form-control bg-light w-25" id="member_name" value="<?= $info->member_name ?>" readonly>
                </div>

                <!-- 새 암호 -->
                <div class="mb-3">
                    <label for="member_password" class="form-label">새 암호 <span class="text-danger">*</span></label>
                    <input type="password" class="form-control w-50" id="member_password" name="member_password" placeholder="8자리 이상 입력하세요">
                    <small class="text-muted">암호는 8자리 이상이어야 합니다.</small>
                </div>

                <!-- 암호 확인 -->
                <div class="mb-3">
                    <label for="member_password_confirm" class="form-label">암호 확인 <span class="text-danger">*</span></label>
                    <input type="password" class="form-control w-50" id="member_password_confirm" name="member_password_confirm" placeholder="암호를 다시 입력하세요">
                </div>

            </div>
            <div class="card-footer text-end">
                <div class="d-flex gap-2 justify-content-end">
                    <a href="/csl/member/view/<?= $info->member_id ?>" class="btn btn-secondary">취소</a>
                    <button type="button" class="btn btn-primary" onclick="passwordUpdate()">저장</button>
                </div>
            </div>
        </div>

    </div>
</main>

</form>

<script>
    // 메뉴강조
    $(window).on('load', function() {
        $('#li-member').addClass('active-level-1');
    });

    function passwordUpdate() {
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
        
        ajax1('/csl/member/password/update', 'frm', 'passwordUpdateAfter');
    }

    function passwordUpdateAfter(proc_result) {
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
