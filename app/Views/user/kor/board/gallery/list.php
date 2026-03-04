<form id="frm" name="frm">

<input type="hidden" id="search_page" name="search_page" value="<?= $data['search_page'] ?>">
<input type="hidden" id="board_id" name="board_id" value="<?= $data['board_id'] ?>">

<!-- Main Content -->
<main id="main-content">
    <div class="container">
        <h3><?= $board_config->title ?></h3>

        <!-- 검색 -->
        <div class="card mb-4">
            <div class="card-header bg-success bg-opacity-75 text-white">검색</div>
            <div class="card-body">
                <div class="row g-2 align-items-end">
                    <div class="col">
                        <label for="search_rows" class="form-label mb-1">줄수</label>
                        <select class="form-select" id="search_rows" name="search_rows" onchange="search()">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="40">40</option>
                            <option value="50">50</option>
                            <option value="70">70</option>
                            <option value="100">100</option>
                        </select>
                    </div>
<?php   if ($board_config->category_yn == 'Y') { ?>
                    <div class="col">
                        <label for="category" class="form-label mb-1">카테고리</label>
                        <select class="form-select" id="category" name="category" onchange="search()">
                            <option value="">전체</option>
<?php       foreach($board_config->category_arr as $no => $val) { ?>
                            <option value="<?=$val ?>"><?=$val ?></option>
<?php       } ?>
                        </select>
                    </div>
<?php   } ?>
                    <div class="col">
                        <label for="search_condition" class="form-label mb-1">조건</label>
                        <select class="form-select" id="search_condition" name="search_condition">
                            <option value="title">제목</option>
                            <option value="contents">내용</option>
                        </select>
                    </div>
                    <div class="col">
                        <label for="search_text" class="form-label mb-1">검색어</label>
                        <input type="text" class="form-control" id="search_text" name="search_text" placeholder="검색어를 입력하세요" value="<?= $data['search_text'] ?>">
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <div class="d-flex gap-2 justify-content-end">
                    <button type="button" class="btn btn-success" onclick="search()">검색</button>
                    <a href="/csl/board/list?board_id=<?= $data['board_id'] ?>" class="btn btn-secondary">초기화</a>
                </div>
            </div>
        </div>
        <!-- 목록 -->
        <div class="card">
            <div class="card-body p-3">
                <!-- 공지사항 -->
<?php   if (count($notice_list) > 0) { ?>
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <span class="badge bg-warning text-dark">공지사항</span>
                    </div>
<?php       foreach($notice_list as $no => $val) { ?>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card h-100 shadow-sm">
                            <a href="/board/<?=$val->board_id ?>/view/<?=$val->board_idx ?>" class="text-decoration-none">
                                <div class="position-relative" style="padding-top: 75%; overflow: hidden;">
<?php           if (!empty($val->main_image_id)) { ?>
                                    <img src="/file/download/<?=$val->main_image_id ?>" class="position-absolute top-0 start-0 w-100 h-100" style="object-fit: cover;" alt="<?=$val->title ?>">
<?php           } else { ?>
                                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                                        <i class="bi bi-image" style="font-size: 3rem; color: #ddd;"></i>
                                    </div>
<?php           } ?>
                                </div>
                                <div class="card-body">
<?php           if ($board_config->category_yn == 'Y' && !empty($val->category)) { ?>
                                    <span class="badge bg-secondary mb-2"><?=$val->category ?></span>
<?php           } ?>
                                    <h6 class="card-title text-dark mb-2" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?=$val->title ?></h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted"><?=$val->ins_date_txt ?></small>
<?php           if ($board_config->hit_yn == 'Y') { ?>
                                        <small class="text-muted">조회 <?=number_format($val->hit_cnt) ?></small>
<?php           } ?>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
<?php       } ?>
                </div>
<?php   } ?>

                <!-- 일반 게시물 -->
<?php   if (count($list) > 0 || count($notice_list) == 0) { ?>
                <div class="row g-3">
<?php       foreach($list as $no => $val) { ?>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card h-100 shadow-sm">
                            <a href="/board/<?=$val->board_id ?>/view/<?=$val->board_idx ?>" class="text-decoration-none">
                                <div class="position-relative" style="padding-top: 75%; overflow: hidden;">
<?php           if (!empty($val->main_image_id)) { ?>
                                    <img src="/file/view/<?=$val->main_image_id ?>" class="position-absolute top-0 start-0 w-100 h-100" style="object-fit: cover;" alt="<?=$val->title ?>">
<?php           } else { ?>
                                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                                        <i class="bi bi-image" style="font-size: 3rem; color: #ddd;"></i>
                                    </div>
<?php           } ?>
                                </div>
                                <div class="card-body">
<?php           if ($board_config->category_yn == 'Y' && !empty($val->category)) { ?>
                                    <span class="badge bg-secondary mb-2"><?=$val->category ?></span>
<?php           } ?>
                                    <h6 class="card-title text-dark mb-2" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?=$val->title ?></h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted"><?=$val->ins_date_txt ?></small>
<?php           if ($board_config->hit_yn == 'Y') { ?>
                                        <small class="text-muted">조회 <?=number_format($val->hit_cnt) ?></small>
<?php           } ?>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
<?php       } ?>
<?php       if (count($list) == 0 && count($notice_list) == 0) { ?>
                    <div class="col-12">
                        <div class="alert alert-info text-center mb-0">데이터가 없습니다.</div>
                    </div>
<?php       } ?>
                </div>
<?php   } ?>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
<?= $paging_info['paging_view'] ?>
<?php   if ($authority->write_authority == "Y") { ?>
                    <a href="/board/<?= $data['board_id'] ?>/write" type="button" class="btn btn-primary">등록</a>
<?php   } ?>
                </div>
            </div>
        </div>
    </div>
</main>

</form>

<script>
    // 메뉴강조
    $(window).on('load', function() {
        $('#a-board-top').addClass('active-level-1').attr({'data-bs-toggle': 'collapse', 'aria-expanded': 'true'});
        $('#collapse-board-top').addClass('show').addClass('submenu');
        $('#a-board-<?= $data['board_id'] ?>').addClass('active-level-2');

        $('#search_rows').val('<?= $data['search_rows'] ?>');
        $('#search_condition').val('<?= $data['search_condition'] ?>');
        $('#category').val('<?= $data['category'] ?>');
    });

    // 검색어 엔터키 이벤트
    $(function() {
        $('#search_text').keydown(function(e) {
            if(e.keyCode == 13) {
                search();
            }
        });
    });

    function search() {
        var board_id = $('#board_id').val();
        var search_text = $('#search_text').val();
        var search_condition = $('#search_condition').val();
        var search_rows = $('#search_rows').val();
        var search_page = $('#search_page').val();
        var category = $('#category').val();
        location.href = '/board/'+board_id+'/list?search_page='+search_page+'&search_text='+search_text+'&search_condition='+search_condition+'&search_rows='+search_rows+'&category='+category;
    }
</script>
