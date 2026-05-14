<?php
/**
 * @var object $info
 */
?>

<form id="frm" name="frm">

<input type="hidden" id="product_idx" name="product_idx" value="<?= $info->product_idx ?>">

<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
        <h3>제품 상세</h3>

        <div class="card mb-4">
            <div class="card-header bg-success bg-opacity-75 text-white">기본정보</div>
            <div class="card-body p-3">
                <div class="border-top">
                    <!-- 분류 -->
                    <div class="row g-0 border-bottom">
                        <div class="tbl-label">분류</div>
                        <div class="tbl-value">
<?php   if ($info->product_category_info1 != null) { ?>
                            <?= $info->product_category_info1->category_name ?>
<?php   } ?>
<?php   if ($info->product_category_info2 != null) { ?>
                            &gt; <?= $info->product_category_info2->category_name ?>
<?php   } ?>
<?php   if ($info->product_category_info3 != null) { ?>
                            &gt; <?= $info->product_category_info3->category_name ?>
<?php   } ?>
<?php   if ($info->product_category_info1 == null && $info->product_category_info2 == null && $info->product_category_info3 == null) { ?>
                            <span class="text-muted">-</span>
<?php   } ?>
                        </div>
                    </div>

                    <!-- 제목 -->
                    <div class="row g-0 border-bottom">
                        <div class="tbl-label">제목</div>
                        <div class="tbl-value"><?= $info->title ?></div>
                    </div>

                    <!-- 옵션 -->
<?php   if (count($info->option_list) > 0) { ?>
                    <div class="row g-0 border-bottom">
                        <div class="tbl-label">옵션</div>
                        <div class="tbl-value">
                            <table class="table table-sm table-bordered mb-0" style="max-width: 500px;">
                                <thead class="table-light">
                                    <tr>
                                        <th>옵션명</th>
                                        <th>옵션값</th>
                                    </tr>
                                </thead>
                                <tbody>
<?php       foreach ($info->option_list as $val) { ?>
                                    <tr>
                                        <td><?= $val->option_name ?></td>
                                        <td><?= $val->option_value ?></td>
                                    </tr>
<?php       } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
<?php   } ?>

                    <!-- 내용 -->
                    <div class="row g-0 border-bottom">
                        <div class="tbl-label">내용</div>
                        <div class="tbl-value"><?= $info->contents ?></div>
                    </div>

                    <!-- 첨부파일 -->
<?php   if (count($info->image_list) > 0) { ?>
                    <div class="row g-0 border-bottom">
                        <div class="tbl-label">첨부파일</div>
                        <div class="tbl-value">
<?php       foreach ($info->image_list as $val) { ?>
                            <div class="mb-2 mt-2 p-3 border rounded">
                                <div class="row g-3 align-items-center">
                                    <div class="col-auto" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
<?php           if ($val->file_info->category == 'image') { ?>
                                        <img src="/file/view/<?= $val->file_id ?>" class="img-thumbnail" style="max-height: 100px; width: auto; max-width: 100%;">
<?php           } else { ?>
                                        <i class="<?= $val->file_info->icon_class ?>" style="font-size: 80px;"></i>
<?php           } ?>
                                    </div>
                                    <div class="col">
                                        <small class="text-muted">원본파일명</small><br>
                                        <a href="/file/download/<?= $val->file_id ?>"><?= $val->file_info->file_name_org ?></a>
                                    </div>
                                    <div class="col">
                                        <small class="text-muted">가로해상도</small><br>
                                        <?= $val->file_info->image_width_txt ?>px
                                    </div>
                                    <div class="col">
                                        <small class="text-muted">세로해상도</small><br>
                                        <?= $val->file_info->image_height_txt ?>px
                                    </div>
                                    <div class="col">
                                        <small class="text-muted">사이즈</small><br>
                                        <?= $val->file_info->file_size_kb ?>KB
                                    </div>
                                </div>
                            </div>
<?php       } ?>
                        </div>
                    </div>
<?php   } ?>

                    <!-- 대표 이미지 -->
                    <div class="row g-0 border-bottom">
                        <div class="tbl-label">대표 이미지</div>
                        <div class="tbl-value">
<?php   if (isset($info->main_image_file_info) && $info->main_image_file_info != null) { ?>
                            <div class="mb-3">
                                <img src="/file/view/<?= $info->main_image_id ?>" class="img-thumbnail img-fluid" style="max-width: 300px; height: auto;">
                            </div>
                            <div class="row g-3">
                                <div class="col-auto">
                                    <small class="text-muted d-block">원본파일명</small>
                                    <strong>
                                        <a href="/file/download/<?= $info->main_image_id ?>">
                                            <?= $info->main_image_file_info->file_name_org ?>
                                        </a>
                                    </strong>
                                </div>
                                <div class="col-auto">
                                    <small class="text-muted d-block">해상도</small>
                                    <strong><?= $info->main_image_file_info->image_width_txt ?> × <?= $info->main_image_file_info->image_height_txt ?> px</strong>
                                </div>
                                <div class="col-auto">
                                    <small class="text-muted d-block">파일 크기</small>
                                    <strong><?= $info->main_image_file_info->file_size_kb ?> KB</strong>
                                </div>
                            </div>
<?php   } else { ?>
                            <p class="text-muted mb-0">등록된 이미지가 없습니다.</p>
<?php   } ?>
                        </div>
                    </div>

                    <!-- 입력일 -->
                    <div class="row g-0 border-bottom">
                        <div class="tbl-label">입력일</div>
                        <div class="tbl-value"><?= convertTextToDate($info->reg_date, 1, 1) ?></div>
                    </div>

                    <!-- 조회수 -->
                    <div class="row g-0 border-bottom">
                        <div class="tbl-label">조회수</div>
                        <div class="tbl-value"><?= number_format($info->hit_cnt) ?></div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <div class="d-flex gap-2 justify-content-end">
                    <a href="/product/list" class="btn btn-secondary">목록</a>
                </div>
            </div>
        </div>

    </div>
</main>

</form>

<script>
    // 메뉴강조
    $(window).on('load', function() {
        $('#a-product-top').addClass('active-level-1').attr({'data-bs-toggle': 'collapse', 'aria-expanded': 'true'});
        $('#collapse-product-top').addClass('show').addClass('submenu');
        $('#li-product').addClass('active-level-2');
    });

    function productDelete() {
        if (confirm('정말 삭제하시겠습니까?')) {
            ajax1('/csl/product/delete', 'frm', 'productDeleteAfter');
        }
    }

    function productDeleteAfter(proc_result) {
        var result = proc_result.result;
        var message = proc_result.message;
        if (result == true) {
            location.href = '/csl/product/list';
        } else {
            alert(message);
        }
    }
</script>
