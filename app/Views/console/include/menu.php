<div id="sidebar" class="d-flex flex-column flex-shrink-0 p-3">
    <div class="sidebar-header">
        <a href="/" class="d-flex align-items-center link-body-emphasis text-decoration-none">
            <span class="fs-5">회사이름을 넣어</span>
        </a>
    </div>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <!-- 1단계: 홈 (하위 메뉴 없음) -->
        <li class="nav-item">
            <a href="#" class="nav-link link-body-emphasis">
                홈
            </a>
        </li>
        
        <!-- 1단계: 대시보드 (하위 메뉴 있음) -->
        <li class="nav-item">
            <a href="#dashboard-collapse" class="nav-link active-level-1" data-bs-toggle="collapse" aria-expanded="true">
                <span>대시보드</span>
                <span class="nav-arrow">›</span>
            </a>
            <div class="collapse show submenu" id="dashboard-collapse">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="#" class="nav-link link-body-emphasis">
                            개요
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link link-body-emphasis">
                            통계
                        </a>
                    </li>
                    <!-- 2단계: 보고서 (3단계 하위 메뉴 있음) -->
                    <li class="nav-item">
                        <a href="#reports-collapse" class="nav-link active-level-2" data-bs-toggle="collapse" aria-expanded="true">
                            <span>보고서</span>
                            <span class="nav-arrow">›</span>
                        </a>
                        <div class="collapse show submenu" id="reports-collapse">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a href="#" class="nav-link active-level-3">
                                        주간 보고서
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link link-body-emphasis">
                                        월간 보고서
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link link-body-emphasis">
                                        연간 보고서
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </li>
        
        <!-- 1단계: 관리 (하위 메뉴 있음) -->
        <li class="nav-item">
            <a href="#management-collapse" class="nav-link link-body-emphasis" data-bs-toggle="collapse" aria-expanded="false">
                <span>관리</span>
                <span class="nav-arrow">›</span>
            </a>
            <div class="collapse submenu" id="management-collapse">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="#" class="nav-link link-body-emphasis">
                            사용자 관리
                        </a>
                    </li>
                    <!-- 2단계: 상품 관리 (3단계 하위 메뉴 있음) -->
                    <li class="nav-item">
                        <a href="#products-collapse" class="nav-link link-body-emphasis" data-bs-toggle="collapse" aria-expanded="false">
                            <span>상품 관리</span>
                            <span class="nav-arrow">›</span>
                        </a>
                        <div class="collapse submenu" id="products-collapse">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a href="#" class="nav-link link-body-emphasis">
                                        상품 등록
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link link-body-emphasis">
                                        상품 목록
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link link-body-emphasis">
                                        재고 관리
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link link-body-emphasis">
                            설정
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        
        <!-- 1단계: 주문 (하위 메뉴 있음) -->
        <li class="nav-item">
            <a href="#orders-collapse" class="nav-link link-body-emphasis" data-bs-toggle="collapse" aria-expanded="false">
                <span>주문</span>
                <span class="nav-arrow">›</span>
            </a>
            <div class="collapse submenu" id="orders-collapse">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="#" class="nav-link link-body-emphasis">
                            신규 주문
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link link-body-emphasis">
                            처리 중
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link link-body-emphasis">
                            배송 완료
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        
        <!-- 1단계: 커뮤니티 (하위 메뉴 있음) -->
        <li class="nav-item">
            <a href="#community-collapse" class="nav-link link-body-emphasis" data-bs-toggle="collapse" aria-expanded="false">
                <span>커뮤니티</span>
                <span class="nav-arrow">›</span>
            </a>
            <div class="collapse submenu" id="community-collapse">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="#" class="nav-link link-body-emphasis">
                            공지사항
                        </a>
                    </li>
                    <!-- 2단계: 게시판 (3단계 하위 메뉴 있음) -->
                    <li class="nav-item">
                        <a href="#boards-collapse" class="nav-link link-body-emphasis" data-bs-toggle="collapse" aria-expanded="false">
                            <span>게시판</span>
                            <span class="nav-arrow">›</span>
                        </a>
                        <div class="collapse submenu" id="boards-collapse">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a href="#" class="nav-link link-body-emphasis">
                                        자유게시판
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link link-body-emphasis">
                                        질문답변
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link link-body-emphasis">
                                        자료실
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link link-body-emphasis">
                            이벤트
                        </a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
    <hr>
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <strong>관리자</strong>
        </a>
        <ul class="dropdown-menu text-small shadow">
            <li><a class="dropdown-item" href="#">관리</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">로그아웃</a></li>
        </ul>
    </div>
</div>