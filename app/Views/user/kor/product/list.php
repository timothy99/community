<?php
/**
 * @var array $data
 * @var array $list
 * @var array $paging_info
 * @var array $product_category_list1
 * @var array $product_category_list2
 * @var array $product_category_list3
 */
?>

<form id="frm" name="frm">

<input type="hidden" id="search_page" name="search_page" value="<?= $data['search_page'] ?>">
<input type="hidden" id="upper_idx" name="upper_idx">

<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
        <h3>제품</h3>

        <!-- 검색 -->
        <div class="card mb-4">
            <div class="card-header bg-success bg-opacity-75 text-white">검색</div>
            <div class="card-body">
                <div class="row g-2 align-items-end">
                    <div class="col-auto">
                        <label for="search_rows" class="form-label mb-1">줄수</label>
                        <select class="form-select" id="search_rows" name="search_rows" onchange="search()">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>

                    <div class="col">
                        <label for="product_category_idx1" class="form-label mb-1">분류1</label>
                        <select class="form-select" id="product_category_idx1" name="product_category_idx1" onchange="search()">
                            <option value="0">전체</option>
<?php       foreach ($product_category_list1 as $no => $val) { ?>
                            <option value="<?= $val->product_category_idx ?>"><?= $val->category_name ?></option>
<?php       } ?>
                        </select>
                    </div>

                    <div class="col">
                        <label for="product_category_idx2" class="form-label mb-1">분류2</label>
                        <select class="form-select" id="product_category_idx2" name="product_category_idx2" onchange="search()">
                            <option value="0">전체</option>
<?php       foreach ($product_category_list2 as $no => $val) { ?>
                            <option value="<?= $val->product_category_idx ?>"><?= $val->category_name ?></option>
<?php       } ?>
                        </select>
                    </div>

                    <div class="col">
                        <label for="product_category_idx3" class="form-label mb-1">분류3</label>
                        <select class="form-select" id="product_category_idx3" name="product_category_idx3" onchange="search()">
                            <option value="0">전체</option>
<?php       foreach ($product_category_list3 as $no => $val) { ?>
                            <option value="<?= $val->product_category_idx ?>"><?= $val->category_name ?></option>
<?php       } ?>
                        </select>
                    </div>

                    <div class="col">
                        <label for="search_condition" class="form-label mb-1">조건</label>
                        <select class="form-select" id="search_condition" name="search_condition">
                            <option value="title">제품명</option>
                            <option value="contents">설명</option>
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
                    <a href="/product/list" class="btn btn-secondary">초기화</a>
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
                                <th scope="col">번호</th>
                                <th scope="col">분류1</th>
                                <th scope="col">분류2</th>
                                <th scope="col">분류3</th>
                                <th scope="col">제품명</th>
                                <th scope="col">등록일</th>
                            </tr>
                        </thead>
                        <tbody>
<?php   foreach ($list as $no => $val) { ?>
                            <tr>
                                <td><?= $val->list_no ?></td>
                                <td><?= $val->product_category_info1->category_name ?></td>
                                <td><?= $val->product_category_info2->category_name ?></td>
                                <td><?= $val->product_category_info3->category_name ?></td>
                                <td class="text-start">
                                    <a href="/product/view/<?= $val->product_idx ?>" class="link-body-emphasis">
                                        <?= $val->title ?>
                                    </a>
                                </td>
                                <td><?= $val->ins_date_txt ?></td>
                            </tr>
<?php   } ?>
<?php   if (count($list) === 0) { ?>
                            <tr>
                                <td colspan="9">등록된 제품이 없습니다.</td>
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
                </div>
            </div>
        </div>
    </div>
</main>

</form>

<script>
    $(window).on('load', function() {
        $('#a-product-top').addClass('active-level-1').attr({'data-bs-toggle': 'collapse', 'aria-expanded': 'true'});
        $('#collapse-product-top').addClass('show').addClass('submenu');
        $('#li-product').addClass('active-level-2');

        $('#search_rows').val('<?= $data['search_rows'] ?>');
        $('#search_condition').val('<?= $data['search_condition'] ?>');
        $('#search_language').val('<?= $data['search_language'] ?>');
        $('#product_category_idx1').val('<?= $data['product_category_idx1'] ?>');
        $('#product_category_idx2').val('<?= $data['product_category_idx2'] ?>');
        $('#product_category_idx3').val('<?= $data['product_category_idx3'] ?>');
    });

    $('#search_text').keydown(function(e) {
        if (e.keyCode == 13) search();
    });

    function search() {
        var search_text = $('#search_text').val();
        var search_condition = $('#search_condition').val();
        var search_rows = $('#search_rows').val();
        var search_page = $('#search_page').val();
        var search_language = $('#search_language').val();
        var product_category_idx1 = $('#product_category_idx1').val();
        var product_category_idx2 = $('#product_category_idx2').val();
        var product_category_idx3 = $('#product_category_idx3').val();
        location.href = '/product/list?search_page='+search_page+'&search_text='+search_text+'&search_condition='+search_condition+'&search_rows='+search_rows+'&search_language='+search_language+'&product_category_idx1='+product_category_idx1+'&product_category_idx2='+product_category_idx2+'&product_category_idx3='+product_category_idx3;
    }
</script>