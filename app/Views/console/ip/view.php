<?php
/**
 * @var object $info
 */
?>

<form id="frm" name="frm">

<input type="hidden" id="ip_idx" name="ip_idx" value="<?= $info->ip_idx ?>">

<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
        <h3>IP 관리</h3>

        <div class="card mb-4">
            <div class="card-header bg-success bg-opacity-75 text-white">IP 정보</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap">
                        <colgroup>
                            <col style="width: 15%;">
                            <col style="width: 80%;">
                        </colgroup>
                        <tbody>
                            <tr>
                                <th class="align-middle bg-light">IP 주소</th>
                                <td><?= $info->ip ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">환경 모드</th>
                                <td><?= $info->environment_mode ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">메모</th>
                                <td><?= $info->memo ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">입력자</th>
                                <td><?= $info->ins_id ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">입력일</th>
                                <td><?= $info->ins_date_txt ?></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-end">
                <div class="d-flex gap-2 justify-content-end">
                    <a href="/csl/ip/edit/<?= $info->ip_idx ?>" class="btn btn-primary">수정</a>
                    <a href="/csl/ip/list" class="btn btn-secondary">목록</a>
                    <a href="javascript:void(0)" class="btn btn-danger" onclick="ipDelete()">삭제</a>
                </div>
            </div>
        </div>

    </div>
</main>

</form>

<script>
    // 메뉴강조
    $(window).on('load', function() {
        $('#li-config').addClass('active-level-1');
        $('#collapse-config').addClass('show').addClass('submenu');
        $('#a-ip-list').addClass('active-level-2');
    });

    function ipDelete() {
        if (confirm('정말 삭제하시겠습니까?')) {
            ajax1('/csl/ip/delete', 'frm', 'ipDeleteAfter');
        }
    }

    function ipDeleteAfter(proc_result) {
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