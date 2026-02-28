<form id="frm" name="frm">

<input type="hidden" id="search_page" name="search_page" value="<?= $data['search_page'] ?>">

<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
        <h3>회원관리</h3>

        <!-- 검색 -->
        <div class="card mb-4">
            <div class="card-header bg-success bg-opacity-75 text-white">검색</div>
            <div class="card-body">
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
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
                    <div class="col-md-4">
                        <label for="search_condition" class="form-label mb-1">조건</label>
                        <select class="form-select" id="search_condition" name="search_condition">
                            <option value="member_name">이름</option>
                            <option value="member_id">아이디</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="search_text" class="form-label mb-1">검색어</label>
                        <input type="text" class="form-control" id="search_text" name="search_text" placeholder="검색어를 입력하세요" value="<?= $data['search_text'] ?>">
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <div class="d-flex gap-2 justify-content-end">
                    <button type="button" class="btn btn-success" onclick="search()">검색</button>
                    <a href="/csl/member/list" class="btn btn-secondary">초기화</a>
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
                                <th>아이디</th>
                                <th>이름</th>
                                <th>회원등급</th>
                                <th>연락처</th>
                                <th>이메일</th>
                                <th>가입일</th>
                            </tr>
                        </thead>
                        <tbody>
<?php   foreach($list as $no => $val) { ?>
                            <tr>
                                <td><?=$val->list_no ?></td>
                                <td><a href="/csl/member/view/<?=$val->member_id ?>?<?=$http_query ?>"><?=$val->member_id ?></a></td>
                                <td><?=$val->member_name ?></td>
                                <td><?=$val->auth_group ?></td>
                                <td><?=$val->phone ?></td>
                                <td><?=$val->email ?></td>
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
                    <a href="/csl/member/write" type="button" class="btn btn-primary">등록</a>
                </div>
            </div>
        </div>
    </div>
</main>

</form>

<script>
    // 메뉴강조
    $(window).on('load', function() {
        // $('#li-member').addClass('active-level-1').attr({'data-bs-toggle': 'collapse', 'aria-expanded': 'true'});
        $('#li-member').addClass('active-level-1');

        $("#search_condition").val("<?= $data['search_condition'] ?>").prop("selected", true);
        $("#search_rows").val("<?= $data['search_rows'] ?>").prop("selected", true);
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
        var search_text = $('#search_text').val();
        var search_condition = $('#search_condition').val();
        var search_rows = $('#search_rows').val();
        var search_page = $('#search_page').val();
        location.href = '/csl/member/list?search_page='+search_page+'&search_text='+search_text+'&search_condition='+search_condition+'&search_rows='+search_rows;
    }
</script>