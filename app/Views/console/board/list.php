<form id="frm" name="frm">

<input type="hidden" id="search_page" name="search_page" value="<?= $data['search_page'] ?>">
<input type="hidden" id="board_id" name="board_id" value="<?= $data['board_id'] ?>">

<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
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
                <div class="table-responsive">
                    <table class="table table-bordered table-hover bg-white align-middle text-center mb-0 text-nowrap">
                        <thead class="table-primary">
                            <tr>
                                <th><input type="checkbox" id="chk_all"></th>
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
                                <td><input type="checkbox" class="chk_item" name="chk[]" value="<?=$val->board_idx ?>"></td>
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
                                <td><input type="checkbox" class="chk_item" name="chk[]" value="<?=$val->board_idx ?>"></td>
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
                <div class="d-flex flex-column flex-md-row align-items-center gap-2">
                    <div class="flex-grow-1">
<?= $paging_info['paging_view'] ?>
                    </div>
                    <div class="d-flex gap-2 justify-content-end align-self-end">
                        <a href="/csl/board/<?= $data['board_id'] ?>/write" type="button" class="btn btn-primary">등록</a>
                        <button type="button" class="btn btn-danger" onclick="batchDelete()">일괄삭제</button>
                    </div>
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

        // 전체선택 체크박스 이벤트
        $("#chk_all").on("change", function() {
            $(".chk_item").prop("checked", $(this).is(":checked"));
        });

        // 개별 체크박스 이벤트 하나라도 체크가 해제되면 전체선택 체크박스 해제, 모두 체크되면 전체선택 체크박스 선택
        $("tbody").on("change", ".chk_item", function() {
            if (!$(this).is(":checked")) {
                $("#chk_all").prop("checked", false);
            } else if ($(".chk_item:not(:checked)").length === 0) {
                $("#chk_all").prop("checked", true);
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

    function batchDelete() {
        var board_id = $('#board_id').val();
        var chk = $("input[name='chk[]']:checked");
        if (chk.length == 0) {
            alert('삭제할 게시물을 선택하세요.');
            return false;
        }

        if (!confirm('선택한 게시물을 삭제하시겠습니까?')) {
            return false;
        }

        var chk_val = [];
        chk.each(function() {
            chk_val.push($(this).val());
        });

        ajax1('/csl/board/'+board_id+'/batch/delete', {chk: chk_val}, 'batchDeleteAfter');
    }

    function batchDeleteAfter(data) {
        if (data.result == true) {
            alert('선택한 게시물이 삭제되었습니다.');
            location.reload();
        } else {
            alert(data.message);
        }
    }
</script>
