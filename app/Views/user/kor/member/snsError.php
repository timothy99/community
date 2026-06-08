<?php
/**
 * SNS 연결 오류 페이지
 * 
 * @var string $provider_name SNS 제공자 이름
 */
?>
<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="col-12 col-md-6 col-lg-5">
            <div class="card shadow border-0">
                <div class="card-body p-5 text-center">

                    <!-- 아이콘 -->
                    <div class="mb-4">
                        <span style="font-size: 64px;">🔗</span>
                    </div>

                    <h4 class="fw-bold mb-3">연결된 계정이 없습니다</h4>

                    <p class="text-muted mb-4">
                        <strong><?= esc($provider_name) ?></strong> 계정과 연결된 회원 정보가 없습니다.<br>
                        아이디/암호로 로그인한 후 마이페이지에서<br>
                        <strong><?= esc($provider_name) ?></strong> 계정을 연결해주세요.
                    </p>

                    <div class="d-grid gap-2">
                        <a href="/member/login" class="btn btn-primary btn-lg">로그인하러 가기</a>
                        <a href="/member/register" class="btn btn-outline-secondary">회원가입</a>
                    </div>

                    <div class="mt-4">
                        <small class="text-muted">
                            회원가입 후 마이페이지 &gt; SNS 연결에서 <?= esc($provider_name) ?> 계정을 연결하면<br>
                            다음부터 SNS 버튼으로 바로 로그인할 수 있습니다.
                        </small>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
