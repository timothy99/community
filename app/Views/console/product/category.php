<?php
/**
 * @var array $list
 */
?>
    <option value="0">전체</option>
<?php       foreach ($list as $no => $val) { ?>
    <option value="<?= $val->product_category_idx ?>"><?= $val->category_name ?></option>
<?php       } ?>
