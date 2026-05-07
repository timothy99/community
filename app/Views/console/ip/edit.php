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
        <h3>슬라이드</h3>

        <div class="card mb-4">
            <div class="card-header bg-success bg-opacity-75 text-white">기본정보</div>
            <div class="card-body">
                <!-- IP -->
                <div class="mb-3">
                    <label for="ip" class="form-label">IP</label>
                    <input type="text" class="form-control" id="ip" name="ip" placeholder="IP를 입력하세요" value="<?= $info->ip ?>">
                </div>

                <!-- 환경 모드 -->
                <div class="mb-3">
                    <label for="environment_mode" class="form-label">환경 모드</label>
                    <select class="form-select" id="environment_mode" name="environment_mode">
                        <option value="production">production</option>
                        <option value="development">development</option>
                    </select>
                </div>

                <!-- 메모 -->
                <div class="mb-3">
                    <label for="memo" class="form-label">메모</label>
                    <input type="text" class="form-control" id="memo" name="memo" placeholder="메모를 입력하세요" value="<?= $info->memo ?>">
                </div>
            </div>
            <div class="card-footer text-end">
                <div class="d-flex gap-2 justify-content-end">
                    <a href="/csl/ip/view/<?= $info->ip_idx ?>" class="btn btn-secondary">취소</a>
                    <button type="button" class="btn btn-primary" onclick="ipUpdate()">저장</button>
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

        $("#environment_mode").val("<?=$info->environment_mode ?>");
    });

    function ipUpdate() {
        ajax1('/csl/ip/update', 'frm', 'ipUpdateAfter');
    }

    function ipUpdateAfter(proc_result) {
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