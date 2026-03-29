
<header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
        <img src="/file/view/<?= $config_info->company_logo ?>" alt="Logo" class="me-2 rounded">
        <span class="fs-4"><?= $config_info->title ?></span>
    </a>
    <ul class="nav nav-pills">
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

        <li class="nav-item">
            <span class="nav-link">|</span>
        </li> 

<?php   if (loginCheck()) { ?>
        <li class="nav-item align-self-center ms-2">
            <a class="btn btn-sm btn-outline-success" href="/member/mypage">내정보</a>
        </li>
        <li class="nav-item align-self-center ms-2">
            <a class="btn btn-sm btn-outline-danger" href="/member/logout">로그아웃</a>
        </li>
<?php   } else { ?>
        <li class="nav-item align-self-center ms-2">
            <a class="btn btn-sm btn-outline-primary" href="/member/login">로그인</a>
        </li>
        <li class="nav-item align-self-center ms-2">
            <a class="btn btn-sm btn-outline-secondary" href="/member/register">회원가입</a>
        </li>
<?php   } ?>
        <li class="nav-item">
            <span class="nav-link">|</span>
        </li> 
        <li class="nav-item align-self-center ms-2">
            <select class="form-select form-select-sm" id="select-language" name="select-language" onchange="change_language(this.value)">
                <option value="kor">한국어</option>
                <option value="eng">English</option>
                <option value="chn">中文</option>
            </select>
        </li>
    </ul>
</header>

<script>
    // 메뉴강조
    $(window).on('load', function() {
        $('#select-language').val(document.cookie.replace(/(?:(?:^|.*;\s*)language\s*=\s*([^;]*).*$)|^.*$/, "$1"));
    });

    function change_language(lang) {
        document.cookie = "language=" + lang + "; path=/; max-age=" + (60 * 60 * 24 * 30);
        location.reload();
    }
</script>
