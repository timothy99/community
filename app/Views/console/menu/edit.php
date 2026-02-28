<form id="frm" name="frm">

<input type="hidden" id="menu_idx" name="menu_idx" value="<?=$info->menu_idx ?>">
<input type="hidden" id="upper_idx" name="upper_idx" value="<?=$info->upper_idx ?>">
<input type="hidden" id="idx1" name="idx1" value="<?=$info->idx1 ?>">
<input type="hidden" id="idx2" name="idx2" value="<?=$info->idx2 ?>">
<input type="hidden" id="menu_position" name="menu_position" value="<?=$info->menu_position ?>">

<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
        <h3>메뉴구성</h3>

        <div class="card mb-4">
            <div class="card-header bg-success bg-opacity-75 text-white">기본정보</div>
            <div class="card-body">
                <!-- 메뉴명 -->
                <div class="mb-3">
                    <label for="menu_name" class="form-label">메뉴명 <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="menu_name" name="menu_name" placeholder="메뉴명을 입력하세요" value="<?= $info->menu_name ?>">
                </div>

                <!-- 정렬순서 -->
                <div class="mb-3">
                    <label for="order_no" class="form-label">정렬순서 <span class="text-danger">*</span></label>
                    <input type="number" class="form-control w-25" id="order_no" name="order_no" placeholder="숫자를 입력하세요" value="<?= $info->order_no ?>">
                </div>

                <!-- URL 링크 -->
                <div class="mb-3">
                    <label for="url_link" class="form-label">링크 URL <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="url_link" name="url_link" placeholder="http로 시작하는 주소 전체. 내부 링크는 /부터 입력도 가능합니다." value="<?= $info->url_link ?>">
                </div>
            </div>
            <div class="card-footer text-end">
                <div class="d-flex gap-2 justify-content-end">
                    <a href="/csl/menu/list" class="btn btn-secondary">취소</a>
                    <button type="button" class="btn btn-primary" onclick="menuUpdate()">저장</button>
                </div>
            </div>
        </div>

    </div>
</main>

</form>

<script>
    // 메뉴강조
    $(window).on('load', function() {
        $('#li-menu').addClass('active-level-1');
    });

    function menuUpdate() {
        ajax1('/csl/menu/update', 'frm', 'menuUpdateAfter');
    }

    function menuUpdateAfter(proc_result) {
        var result = proc_result.result;
        var message = proc_result.message;
        var return_url = proc_result.return_url;
        if (result == true) {
            location.href = return_url;
        } else {
            alert(message);
        }
    }
</script>