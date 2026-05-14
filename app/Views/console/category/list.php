<?php
/**
 * @var array $list
 * @var array $paging_info
 * @var object $config_info
 */
?>

<form id="frm" name="frm">

<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
        <h3>카테고리 관리</h3>
        <!-- 목록 -->
        <div class="card">
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover bg-white align-middle text-center mb-0 text-nowrap">
                        <thead class="table-primary">
                            <tr>
<?php   if ($config_info->language_yn == 'Y') { ?>
                                <th>언어</th>
<?php   } ?>
                                <th>분류명1</th>
                                <th>분류명2</th>
                                <th>분류명3</th>
                                <th>순서</th>
                                <th>작업</th>
                            </tr>
                        </thead>
                        <tbody>
<?php   foreach($list as $no => $val) { ?>
                        <tr>
<?php   if ($config_info->language_yn == 'Y') { ?>
                            <td class="text-center"><?= code_replace('language', $val->language) ?></td>
<?php   } ?>
                            <td><?= $val->category_name ?></td>
                            <td></td>
                            <td></td>
                            <td class="text-center"><?= $val->order_no ?></td>
                            <td>
                                <a href="/csl/category/write/<?= $val->product_category_idx ?>" type="button" class="btn btn-sm btn-info" id="info" name="info" value="<?=$val->product_category_idx ?>">중위추가</a>
                                <a href="/csl/category/edit/<?= $val->product_category_idx ?>" type="button" class="btn btn-sm btn-success" id="edit" name="edit" value="<?=$val->product_category_idx ?>">수정</a>
                                <button type="button" class="btn btn-sm btn-danger" id="delete" name="delete" value="<?=$val->product_category_idx ?>" onclick="categoryDelete(this.value)">삭제</button>
                            </td>
                        </tr>
<?php       foreach($val->list as $no2 => $val2) { // 2차 메뉴 ?>
                        <tr>
<?php   if ($config_info->language_yn == 'Y') { ?>
                            <td class="text-center"><?= code_replace('language', $val2->language) ?></td>
<?php   } ?>
                            <td></td>
                            <td><?= $val2->category_name ?></td>
                            <td></td>
                            <td class="text-center"><?= $val2->order_no ?></td>
                            <td>
                                <a href="/csl/category/write/<?= $val2->product_category_idx ?>" type="button" class="btn btn-sm btn-warning" id="info" name="info" value="<?=$val2->product_category_idx ?>">하위추가</a>
                                <a href="/csl/category/edit/<?= $val2->product_category_idx ?>" type="button" class="btn btn-sm btn-success" id="edit" name="edit" value="<?=$val2->product_category_idx ?>">수정</a>
                                <button type="button" class="btn btn-sm btn-danger" id="delete" name="delete" value="<?=$val2->product_category_idx ?>" onclick="categoryDelete(this.value)">삭제</button>
                            </td>
                        </tr>
<?php           foreach($val2->list as $no3 => $val3) { // 3차 메뉴 ?>
                        <tr>
<?php   if ($config_info->language_yn == 'Y') { ?>
                            <td class="text-center"><?= code_replace('language', $val3->language) ?></td>
<?php   } ?>
                            <td></td>
                            <td></td>
                            <td><?= $val3->category_name ?></td>
                            <td class="text-center"><?= $val3->order_no ?></td>
                            <td>
                                <a href="/csl/category/edit/<?= $val3->product_category_idx ?>" type="button" class="btn btn-sm btn-success" id="edit" name="edit" value="<?=$val3->product_category_idx ?>">수정</a>
                                <button type="button" class="btn btn-sm btn-danger" id="delete" name="delete" value="<?=$val3->product_category_idx ?>" onclick="categoryDelete(this.value)">삭제</button>
                            </td>
                        </tr>
<?php           } ?>
<?php       } ?>
<?php   } ?>
<?php   if (count($list) == 0) { ?>
                            <tr>
                                <td colspan="9" class="text-center">데이터가 없습니다.</td>
                            </tr>
<?php   } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-end align-items-center">
                    <a href="/csl/category/write/0" type="button" class="btn btn-primary">최상위 등록</a>
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
        $('#li-category').addClass('active-level-2');
    });

    function categoryDelete(product_category_idx) {
        if (confirm('카테고리를 삭제하시겠습니까? 한번 삭제하신 이후에는 복구가 불가능합니다.')) {
            var data = {};
            data.product_category_idx = product_category_idx;
            ajax1('/csl/category/delete', data, 'categoryDeleteAfter');
        }
    }

    function categoryDeleteAfter(proc_result) {
        var result = proc_result.result;
        var message = proc_result.message;
        var return_url = proc_result.return_url;
        if (result == true) {
            location.href = return_url;
        } else {
            alert(message);
        }
    }
</script>