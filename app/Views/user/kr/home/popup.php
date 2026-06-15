<?php   if (!empty($popup_list)) { ?>
<div id="hd_pop_dim" class="hd_pop_dim"></div>
<div id="hd_pop_wrap" class="hd_pop_wrap">
    <div class="hd_pop_topbar" id="hd_pop_topbar">
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
<?php       foreach ($popup_list as $no => $val) { ?>
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
<?php       } ?>
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
<?php   } ?>

<script>
    var mPopupIdx     = 0;
    var pcPopupOffset = 0;
    var PC_VISIBLE    = 3;
    var PC_WIDTH      = 452;
    var PC_GAP        = 10;
    var popupAutoTimer = null;
    var POPUP_AUTO_INTERVAL = 5000;

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

        // 자동 슬라이드 시작
        if (items.length > 1) {
            popupStartAuto();
        }
    });

    function popupStartAuto() {
        popupStopAuto();
        popupAutoTimer = setInterval(function() {
            var items = popupGetItems();
            var total = items.length;
            if (total < 2) return;

            // 모바일 자동 슬라이드
            popupGoTo(mPopupIdx + 1);

            // PC 자동 슬라이드 (끝에 도달하면 처음으로)
            var maxOffset = Math.max(0, total - PC_VISIBLE);
            if (maxOffset > 0) {
                pcPopupOffset = pcPopupOffset >= maxOffset ? 0 : pcPopupOffset + 1;
                popupPcUpdate();
            }
        }, POPUP_AUTO_INTERVAL);
    }

    function popupStopAuto() {
        if (popupAutoTimer) {
            clearInterval(popupAutoTimer);
            popupAutoTimer = null;
        }
    }

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
        popupStopAuto();
    }

    /* ── PC 슬라이더 ── */
    function popupPcUpdate() {
        // 모바일에서는 transform 초기화 후 종료
        if (window.innerWidth <= 767.98) {
            var trackM = document.getElementById('hd_pop_container');
            if (trackM) trackM.style.transform = '';
            return;
        }

        var items = popupGetItems();
        var total = items.length;
        var maxOffset = Math.max(0, total - PC_VISIBLE);
        pcPopupOffset = Math.min(Math.max(pcPopupOffset, 0), maxOffset);

        var track = document.getElementById('hd_pop_container');
        if (track) {
            track.style.transform = 'translateX(-' + (pcPopupOffset * (PC_WIDTH + PC_GAP)) + 'px)';
        }

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
        popupStopAuto();
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
