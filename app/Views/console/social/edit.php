<?php
/**
 * @var object $info
 * @var array $list
 */
?>

<form id="frm" name="frm">

<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
        <h3>SNS 설정</h3>

        <div class="card mb-4">
            <div class="card-header bg-success bg-opacity-75 text-white">기본정보</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">SNS 사용</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="social_login_yn" id="social_login_yn_y" value="Y">
                            <label class="form-check-label" for="social_login_yn_y">사용</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="social_login_yn" id="social_login_yn_n" value="N">
                            <label class="form-check-label" for="social_login_yn_n">사용안함</label>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">SNS 로그인 허용</label>
                    <div class="d-flex flex-wrap gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="sns_kakao_use_yn" id="sns_kakao_use_yn" value="Y">
                            <label class="form-check-label" for="sns_kakao_use_yn">카카오</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="sns_naver_use_yn" id="sns_naver_use_yn" value="Y">
                            <label class="form-check-label" for="sns_naver_use_yn">네이버</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="sns_google_use_yn" id="sns_google_use_yn" value="Y">
                            <label class="form-check-label" for="sns_google_use_yn">구글</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="sns_apple_use_yn" id="sns_apple_use_yn" value="Y">
                            <label class="form-check-label" for="sns_apple_use_yn">애플</label>
                        </div>
                    </div>
                    <small class="text-muted">SNS 사용이 켜져 있어도 위에서 체크한 공급자만 로그인 화면에 노출됩니다.</small>
                </div>

                <hr>

                <div class="row g-3">
                    <div class="col-12"><strong>카카오 설정</strong></div>
                    <div class="col-md-6">
                        <label for="sns_kakao_client_id" class="form-label">카카오 Client ID (REST API Key)</label>
                        <input type="text" class="form-control" id="sns_kakao_client_id" name="sns_kakao_client_id" value="<?= $info->sns_kakao_client_id ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="sns_kakao_client_secret" class="form-label">카카오 Client Secret</label>
                        <input type="text" class="form-control" id="sns_kakao_client_secret" name="sns_kakao_client_secret" value="<?= $info->sns_kakao_client_secret ?>">
                    </div>

                    <div class="col-12 mt-3"><strong>네이버 설정</strong></div>
                    <div class="col-md-6">
                        <label for="sns_naver_client_id" class="form-label">네이버 Client ID</label>
                        <input type="text" class="form-control" id="sns_naver_client_id" name="sns_naver_client_id" value="<?= $info->sns_naver_client_id ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="sns_naver_client_secret" class="form-label">네이버 Client Secret</label>
                        <input type="text" class="form-control" id="sns_naver_client_secret" name="sns_naver_client_secret" value="<?= $info->sns_naver_client_secret ?>">
                    </div>

                    <div class="col-12 mt-3"><strong>구글 설정</strong></div>
                    <div class="col-md-6">
                        <label for="sns_google_client_id" class="form-label">구글 Client ID</label>
                        <input type="text" class="form-control" id="sns_google_client_id" name="sns_google_client_id" value="<?= $info->sns_google_client_id ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="sns_google_client_secret" class="form-label">구글 Client Secret</label>
                        <input type="text" class="form-control" id="sns_google_client_secret" name="sns_google_client_secret" value="<?= $info->sns_google_client_secret ?>">
                    </div>

                    <div class="col-12 mt-3"><strong>애플 설정</strong></div>
                    <div class="col-md-6">
                        <label for="sns_apple_client_id" class="form-label">애플 Client ID (Service ID)</label>
                        <input type="text" class="form-control" id="sns_apple_client_id" name="sns_apple_client_id" value="<?= $info->sns_apple_client_id ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="sns_apple_team_id" class="form-label">애플 Team ID</label>
                        <input type="text" class="form-control" id="sns_apple_team_id" name="sns_apple_team_id" value="<?= $info->sns_apple_team_id ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="sns_apple_key_id" class="form-label">애플 Key ID</label>
                        <input type="text" class="form-control" id="sns_apple_key_id" name="sns_apple_key_id" value="<?= $info->sns_apple_key_id ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="sns_apple_private_key" class="form-label">애플 Private Key</label>
                        <textarea class="form-control" id="sns_apple_private_key" name="sns_apple_private_key" rows="4"><?= $info->sns_apple_private_key ?></textarea>
                    </div>
                </div>

            </div>
            <div class="card-footer text-end">
                <div class="d-flex gap-2 justify-content-end">
                    <button type="button" class="btn btn-primary" onclick="socialUpdate()">수정</button>
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
        $('#a-social-edit').addClass('active-level-2');
        $('input[name="social_login_yn"][value="<?= $info->social_login_yn ?>"]').prop('checked', true);
        $('input[name="sns_kakao_use_yn"]').prop('checked', "<?= $info->sns_kakao_use_yn ?>" === 'Y');
        $('input[name="sns_naver_use_yn"]').prop('checked', "<?= $info->sns_naver_use_yn ?>" === 'Y');
        $('input[name="sns_google_use_yn"]').prop('checked', "<?= $info->sns_google_use_yn ?>" === 'Y');
        $('input[name="sns_apple_use_yn"]').prop('checked', "<?= $info->sns_apple_use_yn ?>" === 'Y');
    });

    function socialUpdate() {
        if (confirm('수정하시겠습니까?')) {
            ajax1('/csl/social/update', 'frm', 'socialUpdateAfter');
        }
    }

    function socialUpdateAfter(proc_result) {
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