
<header class="border-bottom mb-4">
    <nav class="navbar navbar-expand-md py-3">
        <div class="container-fluid px-0">
            <!-- 로고 (항상 좌측) -->
            <a href="/" class="navbar-brand d-flex align-items-center link-body-emphasis text-decoration-none">
<?php    if ($config_info->company_logo) { ?>
                <img src="/file/view/<?= $config_info->company_logo ?>" alt="Logo" class="me-2 rounded">
<?php    } else { ?>
                <span class="fs-4"><?= $config_info->title ?></span>
<?php    } ?>
            </a>

            <!-- 햄버거 버튼 (모바일에서만 표시) -->
            <button class="navbar-toggler d-md-none border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu" aria-label="메뉴 열기">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- PC 메뉴 (md 이상에서만 표시) -->
            <div class="d-none d-md-flex align-items-center ms-auto">
                <ul class="navbar-nav align-items-center">
<?php   foreach ($menu_list as $no => $val) { ?>
                    <li class="nav-item <?= $val->dropdown ?>">
                        <a class="nav-link <?= $val->dropdown_toggle ?>" href="<?= $val->url_link ?>" target="<?= $val->url_target ?>" role="button" data-bs-toggle="<?= $val->data_bs_toggle ?>" aria-expanded="false">
                            <?= $val->menu_name ?>
                        </a>
<?php       if (count($val->sub_list) > 0) { ?>
                        <ul class="<?= $val->dropdown_menu ?>">
<?php           foreach ($val->sub_list as $no2 => $val2) { ?>
                            <li class="<?= $val->dropdown_submenu ?>">
                                <a class="<?= $val->dropdown_item ?>" href="<?= $val2->url_link ?>" target="<?= $val2->url_target ?>"><?= $val2->menu_name ?></a>
                            </li>
<?php           } ?>
                        </ul>
<?php       } ?>
                    </li>
<?php   } ?>

                    <li class="nav-item"><span class="nav-link">|</span></li>

<?php   if (loginCheck()) { ?>
                    <li class="nav-item ms-2">
                        <a class="btn btn-sm btn-outline-success" href="/member/mypage">내정보</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-sm btn-outline-danger" href="/member/logout">로그아웃</a>
                    </li>
<?php   } else { ?>
                    <li class="nav-item ms-2">
                        <a class="btn btn-sm btn-outline-primary" href="/member/login">로그인</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-sm btn-outline-secondary" href="/member/register">회원가입</a>
                    </li>
<?php   } ?>
<?php   if ($config_info->language_yn == 'Y') { ?>
                    <li class="nav-item"><span class="nav-link">|</span></li>
                    <li class="nav-item ms-2">
                        <select class="form-select form-select-sm" id="select-language" name="select-language" onchange="change_language(this.value)">
<?php       foreach ($language_list as $no => $val) { ?>
                            <option value="<?= $val->language_code ?>"><?= $val->language_org ?></option>
<?php       } ?>
                        </select>
                    </li>
<?php   } ?>
                </ul>
            </div>
        </div>
    </nav>
</header>

<!-- 모바일 오프캔버스 메뉴 (우측 슬라이딩) -->
<div class="offcanvas offcanvas-end d-md-none" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
    <div class="offcanvas-header border-bottom">
        <h6 class="offcanvas-title" id="mobileMenuLabel">메뉴</h6>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="닫기"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="navbar-nav">
<?php   foreach ($menu_list as $no => $val) { ?>
            <li class="nav-item <?= $val->dropdown ?>">
                <a class="nav-link <?= $val->dropdown_toggle ?>" href="<?= $val->url_link ?>" target="<?= $val->url_target ?>" role="button" data-bs-toggle="<?= $val->data_bs_toggle ?>" aria-expanded="false">
                    <?= $val->menu_name ?>
                </a>
<?php       if (count($val->sub_list) > 0) { ?>
                <ul class="<?= $val->dropdown_menu ?> position-static shadow-none border-0 ps-3 py-0">
<?php           foreach ($val->sub_list as $no2 => $val2) { ?>
                    <li class="<?= $val->dropdown_submenu ?>">
                        <a class="<?= $val->dropdown_item ?>" href="<?= $val2->url_link ?>" target="<?= $val2->url_target ?>"><?= $val2->menu_name ?></a>
                    </li>
<?php           } ?>
                </ul>
<?php       } ?>
            </li>
<?php   } ?>
        </ul>

        <hr>

        <div class="d-grid gap-2">
<?php   if (loginCheck()) { ?>
            <a class="btn btn-sm btn-outline-success" href="/member/mypage">내정보</a>
            <a class="btn btn-sm btn-outline-danger" href="/member/logout">로그아웃</a>
<?php   } else { ?>
            <a class="btn btn-sm btn-outline-primary" href="/member/login">로그인</a>
            <a class="btn btn-sm btn-outline-secondary" href="/member/register">회원가입</a>
<?php   } ?>
        </div>

<?php   if ($config_info->language_yn == 'Y') { ?>
        <hr>
        <select class="form-select form-select-sm" id="select-language-mobile" name="select-language-mobile" onchange="change_language(this.value)">
<?php       foreach ($language_list as $no => $val) { ?>
            <option value="<?= $val->language_code ?>"><?= $val->language_org ?></option>
<?php       } ?>
        </select>
<?php   } ?>
    </div>
</div>

<style>
    /* 오프캔버스 내 드롭다운: Popper.js transform 및 여백 제거 */
    #mobileMenu .dropdown-menu {
        transform: none !important;
        margin-top: 0 !important;
        top: auto !important;
        left: auto !important;
    }
</style>

<script>
    $(window).on('load', function() {
        var lang = document.cookie.replace(/(?:(?:^|.*;\s*)language\s*=\s*([^;]*).*$)|^.*$/, "$1");
        $('#select-language').val(lang);
        $('#select-language-mobile').val(lang);
    });

    function change_language(lang) {
        document.cookie = "language=" + lang + "; path=/; max-age=" + (60 * 60 * 24 * 30);
        location.reload();
    }
</script>
