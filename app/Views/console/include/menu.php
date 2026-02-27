<div id="sidebar" class="d-flex flex-column flex-shrink-0 p-3">
    <div class="sidebar-header justify-content-center">
        <a href="/" class="d-flex align-items-center link-body-emphasis text-decoration-none">
            <span class="fs-5"><?= $config_info->title ?></span>
        </a>
    </div>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item"><a href="/csl/slide/list" class="nav-link link-body-emphasis" id="li-slide">슬라이드</a></li>
        <li class="nav-item"><a href="/csl/popup/list" class="nav-link link-body-emphasis" id="li-popup">팝업</a></li>
        <li class="nav-item"><a href="/csl/board/list" class="nav-link link-body-emphasis" id="li-board">게시판</a></li>
        <li class="nav-item"><a href="/csl/inquiry/list" class="nav-link link-body-emphasis" id="li-inquiry">문의</a></li>

<?php   if (in_array(getUserSessionInfo("auth_group"), ["최고관리자"])) { ?>
        <hr class="my-2">
        <li class="nav-item"><a href="/csl/member/list" class="nav-link link-body-emphasis" id="li-member">회원관리</a></li>
        <li class="nav-item"><a href="/csl/config/view" class="nav-link link-body-emphasis" id="li-config">환경설정</a></li>
        <li class="nav-item"><a href="/csl/menu/list" class="nav-link link-body-emphasis" id="li-menu">메뉴구성</a></li>
        <li class="nav-item"><a href="/csl/content/list" class="nav-link link-body-emphasis" id="li-content">콘텐츠</a></li>
        <li class="nav-item"><a href="/csl/settings/board/list" class="nav-link link-body-emphasis" id="li-board-config">게시판 설정</a></li>
<?php   } ?>





<?php /* 나중을 위한 메뉴 수정 샘플
        <!-- 1단계: 홈 (하위 메뉴 없음) -->
        <li class="nav-item" style="display: none;"><a href="#" class="nav-link link-body-emphasis">홈</a></li>
        
        <!-- 1단계: 대시보드 (하위 메뉴 있음) 샘플을 위한것. 예시용이라 감춤-->
        <li class="nav-item" style="display: none;">
            <a href="#dashboard-collapse" class="nav-link active-level-1" data-bs-toggle="collapse" aria-expanded="true"><span>대시보드</span><span class="nav-arrow">›</span></a>
            <div class="collapse show submenu" id="dashboard-collapse">
                <ul class="nav flex-column">
                    <li class="nav-item"><a href="#" class="nav-link link-body-emphasis">개요</a></li>
                    <li class="nav-item"><a href="#" class="nav-link link-body-emphasis">통계</a></li>
                    <li class="nav-item"><a href="#reports-collapse" class="nav-link active-level-2" data-bs-toggle="collapse" aria-expanded="true"><span>보고서</span><span class="nav-arrow">›</span></a>
                        <div class="collapse show submenu" id="reports-collapse">
                            <ul class="nav flex-column">
                                <li class="nav-item"><a href="#" class="nav-link active-level-3">주간 보고서</a></li>
                                <li class="nav-item"><a href="#" class="nav-link link-body-emphasis">월간 보고서</a></li>
                                <li class="nav-item"><a href="#" class="nav-link link-body-emphasis">연간 보고서</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </li>
*/ ?>
    </ul>
    <hr>
    <div class="dropdown">
        <a href="/member/logout" class="d-flex align-items-center link-body-emphasis text-decoration-none justify-content-center">
            <strong>로그아웃</strong>
        </a>
    </div>
</div>

<script>
    // 사이드바 토글 기능
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show-mobile');
        });
    }

    // 사이드바 외부 클릭 시 닫기 (모바일)
    document.addEventListener('click', function(event) {
        if (window.innerWidth < 992) {
            if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                sidebar.classList.remove('show-mobile');
            }
        }
    });

    // 화면 크기 변경 감지
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 992) {
            sidebar.classList.remove('show-mobile');
        }
    });
</script>