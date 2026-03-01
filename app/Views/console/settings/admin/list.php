<form id="frm" name="frm">

<input type="hidden" id="member_id" name="member_id">
<input type="hidden" id="board_admin_idx" name="board_admin_idx">
<input type="hidden" id="board_id" name="board_id" value="<?= $board_id ?>">

<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
        <h3>관리자 설정</h3>

        <!-- 목록 -->
        <div class="card mb-4">
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover bg-white align-middle text-center mb-0 text-nowrap">
                        <thead class="table-primary">
                            <tr>
                                <th>번호</th>
                                <th>아이디</th>
                                <th>이름</th>
                                <th>전화</th>
                                <th>이메일</th>
                                <th>관리</th>
                            </tr>
                        </thead>
                        <tbody>
<?php   foreach($list as $no => $val) { ?>
                            <tr>
                                <td><?=$val->list_no ?></td>
                                <td><?=$val->member_info->member_id ?></td>
                                <td><?=$val->member_info->member_name ?></td>
                                <td><?=$val->member_info->phone ?></td>
                                <td><?=$val->member_info->email ?></td>
                                <td><button type="button" class="btn btn-sm btn-danger" onclick="adminDelete('<?=$val->board_admin_idx ?>')">삭제</button></td>
                            </tr>
<?php   } ?>
<?php   if (count($list) == 0) { ?>
                            <tr>
                                <td colspan="6" class="text-center">데이터가 없습니다.</td>
                            </tr>
<?php   } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-end align-items-center">
                    <a href="/csl/settings/board/view/<?= $board_id ?>" type="button" class="btn btn-secondary">돌아가기</a>
                </div>
            </div>
        </div>

        <h3>검색</h3>

        <div class="card mb-4">
            <div class="card-header bg-success bg-opacity-75 text-white">검색</div>
            <div class="card-body">
                <div class="row d-flex g-2 align-items-end justify-content-between">
                    <div class="col-md-4">
                        <label for="search_text" class="form-label mb-1">검색어</label>
                        <input type="text" class="form-control" id="search_text" name="search_text" placeholder="검색어를 입력하세요" value="">
                    </div>
                    <div class="col-md-8">
                        <button type="button" class="btn btn-success" onclick="search()">검색</button>
                    </div>
                </div>
                <div id="search_after"></div>
            </div>
        </div>
    </div>
</main>

</form>

<script>
    // 메뉴강조
    $(window).on('load', function() {
        $('#li-settings-board-list').addClass('active-level-1');
    });

    function search() {
        ajax1("/csl/settings/board/gallery/admin/search", "frm", "searchAfter");
    }

    function searchAfter(proc_result) {
        var result = proc_result.result;
        var message = proc_result.message;
        var return_html = proc_result.return_html;

        if (result == false) {
            alert(message);
            return;
        } else {
            $("#search_after").html(return_html);
        }
    }

    function adminAdd(member_id) {
        if (confirm("관리자로 등록하시겠습니까?") == false) {
            return;
        }

        $("#member_id").val(member_id);
        ajax1("/csl/settings/board/gallery/admin/insert", "frm", "adminAddAfter");
    }

    function adminAddAfter(proc_result) {
        var result = proc_result.result;
        var message = proc_result.message;

        alert(message);
        if (result == true) {
            location.reload();
        }
    }

    function adminDelete(board_admin_idx) {
        if (confirm("관리자에서 삭제하시겠습니까?") == false) {
            return;
        }

        $("#board_admin_idx").val(board_admin_idx);
        ajax1("/csl/settings/board/gallery/admin/delete", "frm", "adminAddAfter");
    }
</script>