<?php
/**
 * @var object $info
 * @var array $language_list
 * @var object $config_info
 * @var object $upper_menu_info
 */
?>

<form id="frm" name="frm">

<input type="hidden" id="product_category_idx" name="product_category_idx" value="<?=$info->product_category_idx ?>">
<input type="hidden" id="upper_idx" name="upper_idx" value="<?=$info->upper_idx ?>">
<input type="hidden" id="idx1" name="idx1" value="<?=$info->idx1 ?>">
<input type="hidden" id="idx2" name="idx2" value="<?=$info->idx2 ?>">
<input type="hidden" id="idx3" name="idx3" value="<?=$info->idx3 ?>">
<input type="hidden" id="category_position" name="category_position" value="<?=$info->category_position ?>">

<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
        <h3>메뉴구성</h3>

        <div class="card mb-4">
            <div class="card-header bg-success bg-opacity-75 text-white">기본정보</div>
            <div class="card-body">
                <!-- 카테고리명 -->
                <div class="mb-3">
                    <label for="category_name" class="form-label">카테고리명</label>
                    <input type="text" class="form-control" id="category_name" name="category_name" placeholder="카테고리명을 입력하세요" value="<?= $info->category_name ?>">
                </div>
<?php   if ($config_info->language_yn == 'Y') { ?>
                <!-- 언어 -->
                <div class="mb-3">
                    <label for="language" class="form-label">언어</label>
                    <select class="form-select w-25" id="language" name="language">
<?php       foreach ($language_list as $no => $val) { ?>
                        <option value="<?= $val->language_code ?>"><?= $val->language_name ?></option>
<?php       } ?>
                    </select>
                </div>
<?php   } ?>
                <!-- 정렬순서 -->
                <div class="mb-3">
                    <label for="order_no" class="form-label">정렬순서</label>
                    <input type="number" class="form-control w-25" id="order_no" name="order_no" placeholder="숫자를 입력하세요" value="<?= $info->order_no ?>">
                </div>

            </div>
            <div class="card-footer text-end">
                <div class="d-flex gap-2 justify-content-end">
                    <a href="/csl/category/list" class="btn btn-secondary">취소</a>
                    <button type="button" class="btn btn-primary" onclick="categoryUpdate()">저장</button>
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
        $('#language').val('<?= $info->language ?>');
    });

    function categoryUpdate() {
        ajax1('/csl/category/update', 'frm', 'categoryUpdateAfter');
    }

    function categoryUpdateAfter(proc_result) {
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