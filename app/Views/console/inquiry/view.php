<form id="frm" name="frm">

<input type="hidden" id="inquiry_idx" name="inquiry_idx" value="<?= $info->inquiry_idx ?>">

<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
        <h3>문의 관리</h3>

        <div class="card mb-4">
            <div class="card-header bg-success bg-opacity-75 text-white">문의 정보</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap">
                        <colgroup>
                            <col style="width: 15%;">
                            <col style="width: 80%;">
                        </colgroup>
                        <tbody>
                            <tr>
                                <th class="align-middle bg-light">이름</th>
                                <td><?= $info->name ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">전화번호</th>
                                <td><?= $info->phone ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">이메일</th>
                                <td><?= $info->email ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">문의 내용</th>
                                <td><?= nl2br(htmlspecialchars($info->contents)) ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">등록일</th>
                                <td><?= $info->ins_date_txt ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-end">
                <div class="d-flex gap-2 justify-content-end">
                    <a href="/csl/inquiry/list" class="btn btn-secondary">목록</a>
                </div>
            </div>
        </div>
    </div>
</main>

</form>

<script>
    // 메뉴강조
    $(window).on('load', function() {
        $('#li-inquiry').addClass('active-level-1');
    });
</script>
