<?php    include_once 'popup.php'; ?>

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
<?php   foreach ($gallery_list as $no => $val) { ?>
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
<?php   } ?>
</div>
