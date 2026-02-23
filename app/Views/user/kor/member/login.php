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
                            <a href="/member/find/pwd" class="text-decoration-none">암호 찾기</a>
                        </div>

                        <!-- 구분선 -->
                        <hr class="my-4">

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
