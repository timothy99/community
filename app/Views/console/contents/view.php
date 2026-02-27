<form id="frm" name="frm">

<input type="hidden" id="contents_idx" name="contents_idx" value="<?= $info->contents_idx ?>">

<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
        <h3>콘텐츠</h3>

        <div class="card mb-4">
            <div class="card-header bg-success bg-opacity-75 text-white">기본정보</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap">
                        <colgroup>
                            <col style="width: 15%;">
                            <col style="width: 80%;">
                        </colgroup>
                        <tbody>
                            <tr>
                                <th class="align-middle bg-light">제목</th>
                                <td><?= $info->title ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">아이디</th>
                                <td><?= $info->contents_id ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">메타 제목</th>
                                <td><?= $info->meta_title ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">내용</th>
                                <td><?= nl2br($info->contents) ?></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-end">
                <div class="d-flex gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger" onclick="contentsDelete()">삭제</button>
                    <a href="/csl/contents/list" class="btn btn-secondary">목록</a>
                    <a href="/csl/contents/edit/<?= $info->contents_idx ?>" class="btn btn-primary">수정</a>
                </div>
            </div>
        </div>
    </div>
</main>

</form>

<script>
    // 메뉴강조
    $(window).on('load', function() {
        // $('#li-slide').addClass('active-level-1').attr({'data-bs-toggle': 'collapse', 'aria-expanded': 'true'});
        $('#li-contents').addClass('active-level-1');
    });

    function contentsDelete() {
        if (confirm('정말 삭제하시겠습니까?')) {
            ajax1('/csl/contents/delete', 'frm', 'contentsDeleteAfter');
        }
    }

    function contentsDeleteAfter(proc_result) {
        var result = proc_result.result;
        var message = proc_result.message;
        if (result == true) {
            location.href = '/csl/contents/list';
        } else {
            alert(message);
        }
    }
</script>