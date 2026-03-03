<form id="frm" name="frm">

<input type="hidden" id="search_page" name="search_page" value="<?= $data['search_page'] ?>">
<input type="hidden" id="board_id" name="board_id" value="<?= $data['board_id'] ?>">

<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
        <h3>게시판 목록</h3>

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
<?php if ($board_config->category_yn == 'Y') { ?>
                    <div class="col">
                        <label for="category" class="form-label mb-1">카테고리</label>
                        <input type="text" class="form-control" id="category" name="category" placeholder="카테고리" value="<?= $data['category'] ?>">
                    </div>
<?php } ?>
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
                <div class="table-responsive">
                    <table class="table table-bordered table-hover bg-white align-middle text-center mb-0 text-nowrap">
                        <thead class="table-primary">
                            <tr>
                                <th>번호</th>
<?php   if ($board_config->category_yn == 'Y') { ?>
                                <th>카테고리</th>
<?php   } ?>
                                <th>제목</th>
<?php   if ($board_config->hit_yn == 'Y') { ?>
                                <th>조회수</th>
<?php   } ?>
                                <th>입력자</th>
                                <th>입력일</th>
                            </tr>
                        </thead>
                        <tbody>
<?php   foreach($notice_list as $no => $val) { ?>
                            <tr>
                                <td>공지</td>
<?php       if ($board_config->category_yn == 'Y') { ?>
                                <td><?=$val->category ?></td>
<?php       } ?>
                                <td class="text-start"><a href="/csl/board/<?=$val->board_id ?>/view/<?=$val->board_idx ?>"><?=$val->title ?></a></td>
<?php       if ($board_config->hit_yn == 'Y') { ?>
                                <td><?=number_format($val->hit_cnt) ?></td>
<?php       } ?>
                                <td><?=$val->ins_id ?></td>
                                <td><?=$val->ins_date_txt ?></td>
                            </tr>
<?php   } ?>
<?php   foreach($list as $no => $val) { ?>
                            <tr>
                                <td><?=$val->list_no ?></td>
<?php       if ($board_config->category_yn == 'Y') { ?>
                                <td><?=$val->category ?></td>
<?php       } ?>
                                <td class="text-start"><a href="/csl/board/<?=$val->board_id ?>/view/<?=$val->board_idx ?>"><?=$val->title ?></a></td>
<?php       if ($board_config->hit_yn == 'Y') { ?>
                                <td><?=number_format($val->hit_cnt) ?></td>
<?php       } ?>
                                <td><?=$val->ins_id ?></td>
                                <td><?=$val->ins_date_txt ?></td>
                            </tr>
<?php   } ?>
<?php   if (count($list) == 0) { ?>
                            <tr>
                                <td colspan="7" class="text-center">데이터가 없습니다.</td>
                            </tr>
<?php   } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
<?= $paging_info['paging_view'] ?>
                    <a href="/csl/board/<?= $data['board_id'] ?>/write" type="button" class="btn btn-primary">등록</a>
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
        location.href = '/csl/board/'+board_id+'/list?search_page='+search_page+'&search_text='+search_text+'&search_condition='+search_condition+'&search_rows='+search_rows+'&category='+category;
    }
</script>
