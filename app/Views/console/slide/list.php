
<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
        <h1>메인 콘텐츠</h1>
        <p>여기에 페이지 내용이 들어갑니다.</p>
    </div>
</main>

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