<form id="update_form_<?=$info->board_comment_idx ?>">
    <div class="tbl-label"><?= $info->member_info->member_nickname ?></div>
    <div class="tbl-value">
        <textarea id="comment_<?=$info->board_comment_idx ?>" name="comment_<?=$info->board_comment_idx ?>" class="form-control" rows="3"><?= $info->comment ?></textarea>
        <input type="hidden" id="secret_yn_<?=$info->board_comment_idx ?>" name="secret_yn_<?=$info->board_comment_idx ?>" value="<?= $info->secret_yn ?? 'N' ?>">
    </div>
    <div class="tbl-action">
        <button type="button" class="btn btn-sm btn-warning" onclick="commentCancel('<?=$info->board_comment_idx ?>')">취소</button>
        <button type="button" class="btn btn-sm btn-info" onclick="commentUpdate('<?=$info->board_comment_idx ?>')">저장</button>
    </div>
</form>
