<form id="frm" name="frm">

<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
        <h3>메뉴구성</h3>
        <!-- 목록 -->
        <div class="card">
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover bg-white align-middle text-center mb-0 text-nowrap">
                        <thead class="table-primary">
                            <tr>
                                <th>메뉴명1</th>
                                <th>메뉴명2</th>
                                <th>순서1</th>
                                <th>순서2</th>
                                <th>링크</th>
                                <th>작업</th>
                            </tr>
                        </thead>
                        <tbody>
<?php   foreach($list as $no => $val) { ?>
                        <tr>
                            <td><?=$val->menu_name ?></td>
                            <td></td>
                            <td class="text-center"><?=$val->order_no ?></td>
                            <td class="text-center"></td>
                            <td><?=$val->url_link ?></td>
                            <td>
                                <a href="/csl/menu/write/<?=$val->menu_idx ?>" type="button" class="btn btn-sm btn-warning" id="info" name="info" value="<?=$val->menu_idx ?>">하위추가</a>
                                <a href="/csl/menu/edit/<?=$val->menu_idx ?>" type="button" class="btn btn-sm btn-success" id="edit" name="edit" value="<?=$val->menu_idx ?>">수정</a>
                                <button type="button" class="btn btn-sm btn-danger" id="delete" name="delete" value="<?=$val->menu_idx ?>" onclick="menuDelete(this.value)">삭제</button>
                            </td>
                        </tr>
<?php       foreach($val->list as $no2 => $val2) { // 2차 메뉴 ?>
                        <tr>
                            <td></td>
                            <td><?=$val2->menu_name ?></td>
                            <td class="text-center"></td>
                            <td class="text-center"><?=$val2->order_no ?></td>
                            <td><?=$val2->url_link ?></td>
                            <td>
                                <a href="/csl/menu/write/<?=$val2->menu_idx ?>" type="button" class="btn btn-sm btn-warning" id="info" name="info" value="<?=$val2->menu_idx ?>">하위추가</a>
                                <a href="/csl/menu/edit/<?=$val2->menu_idx ?>" type="button" class="btn btn-sm btn-success" id="edit" name="edit" value="<?=$val2->menu_idx ?>">수정</a>
                                <button type="button" class="btn btn-sm btn-danger" id="delete" name="delete" value="<?=$val2->menu_idx ?>" onclick="menuDelete(this.value)">삭제</button>
                            </td>
                        </tr>
<?php       } ?>
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
                    <a href="/csl/menu/write/0" type="button" class="btn btn-primary">최상위 등록</a>
                </div>
            </div>
        </div>
    </div>
</main>

</form>

<script>
    // 메뉴강조
    $(window).on('load', function() {
        $('#li-menu').addClass('active-level-1');
    });

    function menuDelete(menu_idx) {
        if(confirm("삭제하면 하위 메뉴의 데이터도 모두 삭제됩니다. 진행하시겠습니까?")) {
            var update_form = new FormData();
            update_form.append("menu_idx", menu_idx);
            ajax1("/csl/menu/delete", update_form, "menuDeleteAfter");
        }
    }

    function menuDeleteAfter(proc_result) {
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