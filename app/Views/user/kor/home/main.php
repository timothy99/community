<?php if (!empty($popup_list)): ?>
<div id="hd_pop_dim" class="hd_pop_dim"></div>
<div id="hd_pop_wrap" class="hd_pop_wrap">
    <div class="hd_pop_topbar" id="hd_pop_topbar">
        <span class="hd_pop_counter">
            <span id="hd_pop_curr">1</span> / <span id="hd_pop_total"><?= count($popup_list) ?></span>
        </span>
        <div class="hd_pop_pc_nav">
            <button type="button" class="hd_pop_pc_nav_btn" id="hd_pop_prev_btn" onclick="popupPcNav(-1)">
                <i class="fa fa-chevron-left" aria-hidden="true"></i>
            </button>
            <button type="button" class="hd_pop_pc_nav_btn" id="hd_pop_next_btn" onclick="popupPcNav(1)">
                <i class="fa fa-chevron-right" aria-hidden="true"></i>
            </button>
        </div>
    </div>
    <div class="hd_pop_slider" id="hd_pop_slider">
        <div class="hd_pop_track" id="hd_pop_container">
<?php   foreach ($popup_list as $no => $val): ?>
            <div id="popup_<?= $val->popup_idx ?>"
                    class="hd_pop_item<?= $no === 0 ? ' mobile-active' : '' ?>"
                    data-popup-idx="<?= $val->popup_idx ?>"
                    data-hours="<?= $val->disabled_hours ?>">
                <div class="hd_pop_body">
                    <a href="<?= $val->url_link ?>">
                        <img src="/file/view/<?= $val->popup_file ?>" alt="<?= $val->title ?>">
                    </a>
                </div>
            </div>
<?php   endforeach; ?>
        </div>
    </div>
    <div class="hd_pop_nav_wrap" id="hd_pop_nav_wrap">
        <button type="button" class="hd_pop_nav_btn" onclick="popupMobileNav(-1)">
            <i class="fa fa-chevron-left" aria-hidden="true"></i>
        </button>
        <div class="hd_pop_indicators" id="hd_pop_indicators"></div>
        <button type="button" class="hd_pop_nav_btn" onclick="popupMobileNav(1)">
            <i class="fa fa-chevron-right" aria-hidden="true"></i>
        </button>
    </div>
    <div class="hd_pop_global_footer">
        <button type="button" class="hd_pop_reject_all" onclick="popupBlockAll()">오늘 하루 보지 않기</button>
        <button type="button" class="hd_pop_close_all" onclick="popupCloseAll()">닫기 <i class="fa fa-times" aria-hidden="true"></i></button>
    </div>
</div>
<?php endif; ?>

<!-- Carousel -->
<div id="heroCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
    <div class="carousel-indicators">
<?php    foreach ($slide_list as $no => $val) { ?>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?= $no ?>" class="<?= $no == 0 ? 'active' : '' ?>" <?= $no == 0 ? 'aria-current="true"' : '' ?> aria-label="Slide <?= $no + 1 ?>"></button>
<?php    } ?>
    </div>
    <div class="carousel-inner">
<?php    foreach ($slide_list as $no => $val) { ?>
        <div class="carousel-item <?= $val->active_class ?>">
            <a href="<?= $val->url_link ?>" target="_blank">
                <img src="/file/view/<?= $val->slide_file ?>" class="d-block w-100" alt="<?= $val->contents ?>">
            </a>
            <div class="carousel-caption d-none d-md-block">
                <h5><?= $val->title ?></h5>
                <p><?= $val->sub_title ?></p>
            </div>
        </div>
<?php    } ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">이전</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">다음</span>
    </button>
</div>

<!-- Latest Posts Section -->
<div class="row">
    <!-- 공지사항 -->
    <div class="col-md-6 mb-4">
        <div class="card h-100 rounded-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bullhorn me-2"></i>공지사항
                </h5>
                <a href="/board/notice/list" class="btn btn-sm btn-primary">더보기</a>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
<?php    foreach ($notice_list as $no => $val) { ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                        <a href="/board/<?= $val->board_id ?>/view/<?= $val->board_idx ?>" class="text-decoration-none text-truncate me-2">
                            <?= $val->title ?>
                        </a>
                        <small class="text-muted"><?= $val->ins_date_txt ?></small>
                    </li>
<?php    } ?>
                </ul>
            </div>
        </div>
    </div>

    <!-- FAQ -->
    <div class="col-md-6 mb-4">
        <div class="card h-100 rounded-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-comments me-2"></i>FAQ
                </h5>
                <a href="/board/faq/list" class="btn btn-sm btn-primary">더보기</a>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
<?php    foreach ($faq_list as $no => $val) { ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                        <a href="/board/<?= $val->board_id ?>/view/<?= $val->board_idx ?>" class="text-decoration-none text-truncate me-2">
                            <?= $val->title ?>
                        </a>
                        <small class="text-muted"><?= $val->ins_date_txt ?></small>
                    </li>
<?php    } ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Album Style Posts -->
<div class="row mb-5">
<?php    foreach ($gallery_list as $no => $val) { ?>
    <div class="col-md-4 mb-4">
        <div class="card rounded-0 h-100 d-flex flex-column">
            <a href="/board/<?= $val->board_id ?>/view/<?= $val->board_idx ?>">
                <img src="/file/view/<?= $val->main_image_id ?>" class="card-img-top rounded-0" alt="<?= $val->title ?>">
            </a>
            <div class="card-body d-flex flex-column">
                <p class="card-text flex-grow-1">
                    <a href="/board/<?= $val->board_id ?>/view/<?= $val->board_idx ?>" class="text-decoration-none text-truncate me-2">
                        <?= $val->title ?>
                    </a>
                <p class="card-text mb-0 text-end">
                    <small class="text-muted"><?= $val->ins_date_txt ?></small>
                </p>
            </div>
        </div>
    </div>
<?php    } ?>
</div>

<script>
    var mPopupIdx     = 0;
    var pcPopupOffset = 0;
    var PC_VISIBLE    = 3;
    var PC_WIDTH      = 452;
    var PC_GAP        = 10;

    document.addEventListener("DOMContentLoaded", function() {
        // 캐러셀 초기화
        var carousel = document.querySelector("#heroCarousel");
        if (carousel) {
            new bootstrap.Carousel(carousel, {
                interval: 5000,
                ride: "carousel",
                pause: "hover",
                wrap: true,
                keyboard: true,
                touch: true
            });
        }

        // 팝업 초기화
        var items = popupGetItems();
        if (items.length === 0) return;

        // 모바일: dot 빌드
        popupRebuildDots();
        if (items.length <= 1) {
            var nav = document.getElementById('hd_pop_nav_wrap');
            if (nav) nav.style.display = 'none';
        }

        // PC: 팝업이 PC_VISIBLE 미만이면 슬라이더 너비 축소
        var slider = document.getElementById('hd_pop_slider');
        if (slider && items.length < PC_VISIBLE) {
            var w = items.length * PC_WIDTH + Math.max(0, items.length - 1) * PC_GAP;
            slider.style.width = w + 'px';
        }

        popupPcUpdate();
    });

    /* ── 공통 ── */
    function popupGetItems() {
        return Array.from(document.querySelectorAll('#hd_pop_container .hd_pop_item'));
    }

    /* ── 모바일 dot ── */
    function popupRebuildDots() {
        var items = popupGetItems();
        var container = document.getElementById('hd_pop_indicators');
        if (!container) return;
        container.innerHTML = '';
        items.forEach(function(item, i) {
            var dot = document.createElement('span');
            dot.className = 'hd_pop_dot' + (i === mPopupIdx ? ' active' : '');
            (function(idx) {
                dot.addEventListener('click', function() { popupGoTo(idx); });
            })(i);
            container.appendChild(dot);
        });
    }

    function popupGoTo(index) {
        var items = popupGetItems();
        if (items.length === 0) return;
        items.forEach(function(item) { item.classList.remove('mobile-active'); });
        mPopupIdx = ((index % items.length) + items.length) % items.length;
        items[mPopupIdx].classList.add('mobile-active');
        document.querySelectorAll('#hd_pop_indicators .hd_pop_dot').forEach(function(dot, i) {
            dot.classList.toggle('active', i === mPopupIdx);
        });
    }

    function popupMobileNav(direction) {
        popupGoTo(mPopupIdx + direction);
    }

    /* ── PC 슬라이더 ── */
    function popupPcUpdate() {
        var items = popupGetItems();
        var total = items.length;
        var maxOffset = Math.max(0, total - PC_VISIBLE);
        pcPopupOffset = Math.min(Math.max(pcPopupOffset, 0), maxOffset);

        var track = document.getElementById('hd_pop_container');
        if (track) {
            track.style.transform = 'translateX(-' + (pcPopupOffset * (PC_WIDTH + PC_GAP)) + 'px)';
        }

        // 카운터 업데이트
        var curr = document.getElementById('hd_pop_curr');
        if (curr) curr.textContent = pcPopupOffset + 1;

        // 화살표 비활성화
        var prevBtn = document.getElementById('hd_pop_prev_btn');
        var nextBtn = document.getElementById('hd_pop_next_btn');
        if (prevBtn) prevBtn.disabled = (pcPopupOffset <= 0);
        if (nextBtn) nextBtn.disabled = (pcPopupOffset >= maxOffset);

        // 팝업 PC_VISIBLE 이하: 화살표 숨김
        if (total <= PC_VISIBLE) {
            var pcNav = document.querySelector('.hd_pop_pc_nav');
            if (pcNav) pcNav.style.visibility = 'hidden';
        }
    }

    function popupPcNav(direction) {
        var items = popupGetItems();
        var maxOffset = Math.max(0, items.length - PC_VISIBLE);
        pcPopupOffset = Math.min(Math.max(pcPopupOffset + direction, 0), maxOffset);
        popupPcUpdate();
    }

    /* ── 전체 닫기/차단 ── */
    function popupHideAll() {
        var wrap = document.getElementById('hd_pop_wrap');
        var dim  = document.getElementById('hd_pop_dim');
        if (wrap) wrap.style.display = 'none';
        if (dim)  dim.style.display  = 'none';
    }

    function popupCloseAll() { popupHideAll(); }

    function popupBlockAll() {
        popupGetItems().forEach(function(item) {
            var form = new FormData();
            form.append('popup_idx', item.dataset.popupIdx);
            form.append('disabled_hours', item.dataset.hours);
            ajax1('/main/popup/block', form, null);
        });
        popupHideAll();
    }
</script>
