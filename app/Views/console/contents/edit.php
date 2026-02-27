<form id="frm" name="frm">

<input type="hidden" id="contents_idx" name="contents_idx" value="<?= $info->contents_idx ?>">

<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
        <h3>콘텐츠</h3>

        <div class="card mb-4">
            <div class="card-header bg-success bg-opacity-75 text-white">wp</div>
            <div class="card-body">
                <!-- 제목 -->
                <div class="mb-3">
                    <label for="title" class="form-label">제목 <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="제목을 입력하세요" value="<?= $info->title ?>">
                </div>

                <!-- 아이디 -->
                <div class="mb-3">
                    <label for="contents_id" class="form-label">아이디</label>
                    <input type="text" class="form-control" id="contents_id" name="contents_id" placeholder="아이디를 입력하세요" value="<?= $info->contents_id ?>">
                </div>

                <!-- 메타용 제목 -->
                <div class="mb-3">
                    <label for="meta_title" class="form-label">메타 제목</label>
                    <input type="text" class="form-control" id="meta_title" name="meta_title" placeholder="메타 제목을 입력하세요" value="<?= $info->meta_title ?>">
                </div>

                <!-- 내용 -->
                <div class="mb-3">
                    <label for="contents" class="form-label">내용</label>
                    <textarea class="form-control" id="contents" name="contents" rows="10" placeholder="내용을 입력하세요"><?= $info->contents ?></textarea>
                </div>
            </div>
            <div class="card-footer text-end">
                <div class="d-flex gap-2 justify-content-end">
                    <a href="/csl/contents/view/<?= $info->contents_idx ?>" class="btn btn-secondary">취소</a>
                    <button type="button" class="btn btn-primary" onclick="contentsUpdate()">저장</button>
                </div>
            </div>
        </div>
    </div>
</main>

</form>

<script>
    // 메뉴강조
    $(window).on('load', function() {
        $('#li-contents').addClass('active-level-1');
    });

    function contentsUpdate() {
        ajax1('/csl/contents/update', 'frm', 'contentsUpdateAfter');
    }

    function contentsUpdateAfter(proc_result) {
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