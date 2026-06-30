<?php
/**
 * @var object $config_info
 */
?>

<form id="frm" name="frm">

<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow">
                <div class="card-body p-4">
                    <h3 class="card-title text-center mb-4">로그인</h3>

                    <form id="loginForm" method="post" action="/member/loginProc">
                        <!-- 아이디 입력 -->
                        <div class="mb-3">
                            <label for="member_id" class="form-label">아이디</label>
                            <input type="text" class="form-control" id="member_id" name="member_id" placeholder="아이디를 입력하세요" required>
                        </div>

                        <!-- 암호 입력 -->
                        <div class="mb-3">
                            <label for="member_password" class="form-label">암호</label>
                            <input type="password" class="form-control" id="member_password" name="member_password" placeholder="암호를 입력하세요" required>
                        </div>

                        <!-- 로그인 버튼 -->
                        <div class="d-grid mb-3">
                            <button type="button" class="btn btn-primary btn-lg" onclick="login()">로그인</button>
                        </div>

                        <!-- 아이디찾기, 암호찾기 -->
                        <div class="d-flex justify-content-center gap-3 mb-3">
                            <a href="/member/find/id" class="text-decoration-none">아이디 찾기</a>
                            <span class="text-muted">|</span>
                            <a href="/member/find/password" class="text-decoration-none">암호 찾기</a>
                        </div>

<?php   if ($config_info->social_login_yn === 'Y') { ?>
                        <!-- 구분선 -->
                        <hr class="my-4">

                        <!-- SNS 로그인 버튼 -->
                        <div class="mb-3">
                            <p class="text-center text-muted mb-2" style="font-size:0.85rem;">SNS 계정으로 간편 로그인</p>
                            <div class="d-flex justify-content-center gap-3">

<?php       if ($config_info->sns_kakao_use_yn === 'Y') { ?>
                                <!-- 카카오 -->
                                <a href="/member/sns/kakao" class="sns-login-btn" title="카카오로 로그인" style="background-color:#FEE500; color:#000;">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 3C6.48 3 2 6.48 2 10.77c0 2.74 1.66 5.15 4.19 6.57l-.97 3.6c-.08.31.24.56.51.39L9.9 18.9A11.5 11.5 0 0 0 12 19.1c5.52 0 10-3.48 10-7.33C22 6.48 17.52 3 12 3z"/>
                                    </svg>
                                    <span>카카오</span>
                                </a>
<?php       } ?>

<?php       if ($config_info->sns_naver_use_yn === 'Y') { ?>
                                <!-- 네이버 -->
                                <a href="/member/sns/naver" class="sns-login-btn" title="네이버로 로그인" style="background-color:#03C75A; color:#fff;">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M16.273 12.845L7.376 3H3v18h4.727v-9.845L16.624 21H21V3h-4.727z"/>
                                    </svg>
                                    <span>네이버</span>
                                </a>
<?php       } ?>

<?php       if ($config_info->sns_google_use_yn === 'Y') { ?>
                                <!-- 구글 -->
                                <a href="/member/sns/google" class="sns-login-btn" title="구글로 로그인" style="background-color:#fff; color:#444; border: 1px solid #ddd;">
                                    <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                                    </svg>
                                    <span>구글</span>
                                </a>
<?php       } ?>

<?php       if ($config_info->sns_apple_use_yn === 'Y') { ?>
                                <!-- 애플 (준비 중) -->
                                <a href="#" class="sns-login-btn" title="애플로 로그인 (준비 중)" style="background-color:#000; color:#fff; opacity:0.4; cursor:not-allowed;" onclick="alert('애플 로그인은 현재 준비 중입니다.'); return false;">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98l-.09.06c-.22.15-2.18 1.27-2.16 3.8.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.37 2.68zM13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                                    </svg>
                                    <span>애플</span>
                                </a>
<?php       } ?>

                            </div>
                        </div>
<?php   } ?>
                        <!-- 회원가입 버튼 -->
                        <div class="d-grid">
                            <a href="/member/register" class="btn btn-secondary">회원가입</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</form>

<script>
    function login() {
        ajax1("/member/signin", "frm", "loginAfter");
    }

    function loginAfter(proc_result) {
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
