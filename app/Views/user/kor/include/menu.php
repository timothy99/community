
<header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
        <img src="https://dummyimage.com/40x40/007bff/fff&text=H" alt="Logo" width="40" height="40" class="me-2 rounded">
        <span class="fs-4">Simple header2</span>
    </a>
    <ul class="nav nav-pills">
<?php   foreach ($menu_list as $no => $val) { ?>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Features
            </a>
<?php       if (count($val->list) > 0) { ?>
            <ul class="dropdown-menu">
<?php           foreach ($val->list as $no2 => $val2) { ?>
                <li class="dropdown-submenu">
                    <a class="dropdown-item dropdown-toggle" href="#">Web Design</a>
<?php               if (count($val2->list) > 0) { ?>
                    <ul class="dropdown-menu">
<?php                   foreach ($val2->list as $no3 => $val3) { ?>
                        <li><a class="dropdown-item" href="#">Responsive Design</a></li>
<?php                   } ?>
                    </ul>
<?php               } ?>
                </li>
<?php           } ?>
            </ul>
<?php       } ?>
        </li>
<?php   } ?>
    </ul>
</header>
