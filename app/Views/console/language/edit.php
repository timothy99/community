<form id="frm" name="frm">

<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
        <h3>언어설정</h3>

        <div class="card mb-4">
            <div class="card-header bg-success bg-opacity-75 text-white">기본정보</div>
            <div class="card-body">


                <div class="mb-3">
                    <label class="form-label">다국어 설정</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="language_yn" id="language_yn_y" value="Y">
                            <label class="form-check-label" for="language_yn_y">사용</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="language_yn" id="language_yn_n" value="N">
                            <label class="form-check-label" for="language_yn_n">사용안함</label>
                        </div>
                    </div>
                </div>

                <!-- 언어 목록 -->
                <div class="mb-3">
                    <label class="form-label">언어 목록</label>
                    <table class="table table-bordered table-sm align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width:50px;">사용</th>
                                <th>언어명</th>
                                <th>코드</th>
                                <th>원어명</th>
                            </tr>
                        </thead>
                        <tbody>
<?php foreach ($list as $no => $val) { ?>
                            <tr>
                                <td class="text-center">
                                    <input class="form-check-input" type="checkbox" name="language_use[]" value="<?= $val->language_code ?>" <?= ($val->use_yn == 'Y') ? 'checked' : '' ?>>
                                </td>
                                <td><?= $val->language_name ?></td>
                                <td><?= $val->language_code ?></td>
                                <td><?= $val->language_org ?></td>
                            </tr>
<?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-end">
                <div class="d-flex gap-2 justify-content-end">
                    <button type="button" class="btn btn-primary" onclick="languageUpdate()">수정</button>
                </div>
            </div>
        </div>

    </div>
</main>

</form>

<script>
    // 메뉴강조
    $(window).on('load', function() {
        $('#li-language-edit').addClass('active-level-1');
        $('input[name="language_yn"][value="<?= $info->language_yn ?>"]').prop('checked', true);
    });

    function languageUpdate() {
        if (confirm('수정하시겠습니까?')) {
            ajax1('/csl/language/update', 'frm', 'languageUpdateAfter');
        }
    }

    function languageUpdateAfter(proc_result) {
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