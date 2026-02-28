
<header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
        <img src="/file/view/<?= $config_info->company_logo ?>" alt="Logo" class="me-2 rounded">
        <span class="fs-4"><?= $config_info->title ?></span>
    </a>
    <ul class="nav nav-pills">
<?php   foreach ($menu_list as $no => $val) { ?>
        <li class="nav-item <?= $val->dropdown ?>">
            <a class="nav-link <?= $val->dropdown_toggle ?>" href="<?= $val->url_link ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <?= $val->menu_name ?>
            </a>
<?php       if (count($val->sub_list) > 0) { ?>
            <ul class="dropdown-menu">
<?php           foreach ($val->sub_list as $no2 => $val2) { ?>
                <li class="dropdown-submenu">
                    <a class="dropdown-item" href="<?= $val2->url_link ?>"><?= $val2->menu_name ?></a>
                </li>
<?php           } ?>
            </ul>
<?php       } ?>
        </li>
<?php   } ?>
    </ul>
</header>
