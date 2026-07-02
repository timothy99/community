<?php
/**
 * @var object $board_config
 * @var object $info
 * @var array $comment_list
 * @var object $authority
 * @var string $http_query
 */

$renderComment = static function ($comment) {
    return preg_match('/<[^>]+>/', $comment) ? $comment : nl2br($comment);
};
?>

<form id="frm" name="frm">

<input type="hidden" id="board_idx" name="board_idx" value="<?= $info->board_idx ?>">
<input type="hidden" id="board_id" name="board_id" value="<?= $info->board_id ?>">
<input type="hidden" id="http_query" name="http_query" value="<?= $http_query ?>">
<input type="hidden" id="comment_file_idxs" name="comment_file_idxs" value="">

<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
        <h3><?= $board_config->title ?></h3>

        <div class="card mb-4">
            <div class="card-header bg-primary bg-opacity-75 text-white">기본정보</div>
            <div class="card-body p-3">
                <div class="border-top">
                    <div class="row g-0 border-bottom">
                        <div class="tbl-label">공지여부</div>
                        <div class="tbl-value"><?= code_replace('notice_yn', $info->notice_yn) ?></div>
                    </div>
<?php if ($board_config->category_yn == 'Y') { ?>
                    <div class="row g-0 border-bottom">
                        <div class="tbl-label">카테고리</div>
                        <div class="tbl-value"><?= $info->category ?></div>
                    </div>
<?php } ?>
                    <div class="row g-0 border-bottom">
                        <div class="tbl-label">제목</div>
                        <div class="tbl-value"><?= $info->title ?></div>
                    </div>
                    <div class="row g-0 border-bottom">
                        <div class="tbl-label">내용</div>
                        <div class="tbl-value"><?= nl2br($info->contents) ?></div>
                    </div>

<?php   if (count($info->file_list) > 0) { ?>
                    <div class="row g-0 border-bottom">
                        <div class="tbl-label">첨부파일</div>
                        <div class="tbl-value">
<?php       foreach ($info->file_list as $val) { ?>
                                    <div class="mb-2 mt-2 ml-2 mr-2 p-3 border rounded">
                                        <div class="row g-3 align-items-center">
                                            <div class="col-auto" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
<?php       if ($val->file_info->category == 'image') { ?>
                                                <img src="/file/view/<?= $val->file_id ?>" class="img-thumbnail" style="max-height: 150px; width: auto; max-width: 100%;">
<?php       } else { ?>
                                                <i class="<?= $val->file_info->icon_class ?>" style="font-size: 80px;"></i>
<?php       } ?>
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

<?php   if ($board_config->main_image_yn == 'Y') { ?>
                    <div class="row g-0 border-bottom">
                        <div class="tbl-label">대표 이미지</div>
                        <div class="tbl-value">
<?php       if ($info->main_image_info != null) { ?>
                                    <div class="mb-3">
                                        <img src="/file/view/<?= $info->main_image_id ?>" class="img-thumbnail img-fluid" style="max-width: 100%; height: auto;">
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-auto">
                                            <small class="text-muted d-block">원본파일명</small>
                                            <strong>
                                                <a href="/file/download/<?= $info->main_image_id ?>">
                                                    <?= $info->main_image_info->file_name_org ?>
                                                </a>
                                            </strong>
                                        </div>
                                        <div class="col-auto">
                                            <small class="text-muted d-block">해상도</small>
                                            <strong><?= $info->main_image_info->image_width_txt ?> × <?= $info->main_image_info->image_height_txt ?> px</strong>
                                        </div>
                                        <div class="col-auto">
                                            <small class="text-muted d-block">파일 크기</small>
                                            <strong><?= $info->main_image_info->file_size_kb ?> KB</strong>
                                        </div>
                                    </div>
<?php       } else { ?>
                                    <p class="text-muted mb-0">등록된 이미지가 없습니다.</p>
<?php       } ?>
                        </div>
                    </div>
<?php   } ?>

<?php   if ($board_config->url_link_yn == 'Y') { ?>
                    <div class="row g-0 border-bottom">
                        <div class="tbl-label">링크 URL</div>
                        <div class="tbl-value">
<?php       if (!empty($info->url_link)) { ?>
                            <a href="<?= $info->url_link ?>" target="_blank"><?= $info->url_link ?></a>
<?php       } else { ?>
                            <span class="text-muted">-</span>
<?php       } ?>
                        </div>
                    </div>
<?php   } ?>

<?php   if ($board_config->pdf_yn == 'Y') { ?>
                    <div class="row g-0 border-bottom">
                        <div class="tbl-label">PDF 파일</div>
                        <div class="tbl-value">
<?php       if ($info->pdf_file_info != null) { ?>
                            <div class="mb-3">
                                <div class="border rounded p-3" style="background-color: #f8f9fa;">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" id="prev-page">이전</button>
                                            <span class="mx-2">
                                                <span id="page-num">1</span> / <span id="page-count">-</span>
                                            </span>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" id="next-page">다음</button>
                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" id="zoom-out">-</button>
                                            <span class="mx-2" id="zoom-level">100%</span>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" id="zoom-in">+</button>
                                        </div>
                                    </div>
                                    <div style="overflow: auto; max-height: 800px; background-color: #525659; text-align: center;">
                                        <canvas id="pdf-canvas" style="max-width: 100%; height: auto;"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-auto">
                                    <small class="text-muted d-block">원본파일명</small>
                                    <strong>
                                        <a href="/file/download/<?= $info->pdf_file_id ?>">
                                            <?= $info->pdf_file_info->file_name_org ?>
                                        </a>
                                    </strong>
                                </div>
                                <div class="col-auto">
                                    <small class="text-muted d-block">파일 크기</small>
                                    <strong><?= $info->pdf_file_info->file_size_kb ?> KB</strong>
                                </div>
                            </div>
<?php       } else { ?>
                            <p class="text-muted mb-0">등록된 파일이 없습니다.</p>
<?php       } ?>
                        </div>
                    </div>
<?php   } ?>

<?php   if ($board_config->youtube_yn == 'Y') { ?>
                    <div class="row g-0 border-bottom">
                        <div class="tbl-label">유튜브 링크</div>
                        <div class="tbl-value">
<?php      if ($info->youtube_id) { ?>
                            <div class="mb-3">
                                <div class="ratio ratio-16x9">
                                    <iframe src="https://www.youtube.com/embed/<?= $info->youtube_id ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            </div>
                            <div>
                                <small class="text-muted">링크:</small>
                                <a href="<?= $info->youtube_link ?>" target="_blank"><?= $info->youtube_link ?></a>
                            </div>
<?php       } else { ?>
                            <a href="<?= $info->youtube_link ?>" target="_blank"><?= $info->youtube_link ?></a>
<?php       } ?>
                        </div>
                    </div>
<?php   } ?>

<?php   if ($board_config->hit_yn == 'Y') { ?>
                    <div class="row g-0 border-bottom">
                        <div class="tbl-label">조회수</div>
                        <div class="tbl-value"><?= number_format($info->hit_cnt) ?></div>
                    </div>
<?php   } ?>
<?php   if ($board_config->heart_yn == 'Y') { ?>
                    <div class="row g-0 border-bottom">
                        <div class="tbl-label">공감수</div>
                        <div class="tbl-value"><span id="heart-cnt"><?= number_format($info->heart_cnt) ?></span></div>
                    </div>
<?php   } ?>
                    <div class="row g-0 border-bottom">
                        <div class="tbl-label">등록자</div>
                        <div class="tbl-value"><?= $info->ins_id ?></div>
                    </div>
                    <div class="row g-0 border-bottom">
                        <div class="tbl-label">입력일</div>
                        <div class="tbl-value"><?= $info->ins_date_txt ?></div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex gap-2 justify-content-end">
<?php   if ($board_config->heart_yn == 'Y') { ?>
                    <button type="button" id="heart-button" class="btn <?= $info->my_heart_yn == 'Y' ? 'btn-warning' : 'btn-outline-warning' ?>" onclick="boardHeartToggle()">
                        공감
                    </button>
<?php   } ?>
<?php   if ($authority->delete_authority == "Y") { ?>
                    <button type="button" class="btn btn-danger" onclick="boardDelete()">삭제</button>
<?php   } ?>
                    <a href="/board/<?= $info->board_id ?>/list<?= !empty($http_query) ? '?'.$http_query : '' ?>" class="btn btn-secondary">목록</a>
<?php   if ($authority->edit_authority == "Y") { ?>
                    <a href="/board/<?= $info->board_id ?>/edit/<?= $info->board_no ?><?= !empty($http_query) ? '?'.$http_query : '' ?>" class="btn btn-primary">수정</a>
<?php   } ?>
                </div>
            </div>
        </div>

<?php   if ($board_config->comment_write == 'Y') { ?>
        <div class="card mb-4">
            <div class="card-header bg-info bg-opacity-75 text-white">댓글 (<?= count($comment_list) ?>개)</div>
            <div class="card-body p-3">
                <div class="border-top">
<?php       foreach($comment_list as $no => $val) { ?>
                    <div class="row g-0 border-bottom tbl-row--with-action" id="board_comment_idx_<?= $val->board_comment_idx ?>">
                        <div class="tbl-label">
                            <?= $val->member_info->member_nickname ?><br><?= $val->ins_date_txt ?>
                        </div>
                        <div class="tbl-value">
<?php           if ($val->secret_yn == 'Y' && $authority->admin_authority != 'Y' && $val->ins_id != getUserSessionInfo('member_id')) { ?>
                            <div class="comment-display"><span class="badge bg-secondary"><i class="fas fa-lock"></i> 비밀댓글</span></div>
<?php           } else if ($val->secret_yn == 'Y') { ?>
                            <div class="comment-display"><span class="badge bg-secondary"><i class="fas fa-lock"></i></span><?= $renderComment($val->comment) ?></div>
<?php           } else { ?>
                            <div class="comment-display"><?= $renderComment($val->comment) ?></div>
<?php           } ?>
    <?php           if (!empty($val->file_list) && !($val->secret_yn == 'Y' && $authority->admin_authority != 'Y' && $val->ins_id != getUserSessionInfo('member_id'))) { ?>
                                <div class="comment-display mt-2">
    <?php               foreach ($val->file_list as $file) { ?>
                                    <div class="mb-1">
                                        <a href="/file/download/<?= $file->file_id ?>"><i class="<?= $file->file_info->icon_class ?>"></i> <?= $file->file_info->file_name_org ?></a>
                                        <small class="text-muted">(<?= $file->file_info->file_size_kb ?>KB)</small>
                                    </div>
    <?php               } ?>
                                </div>
    <?php           } ?>
                            <div class="comment-edit-host d-none mt-3"></div>
                        </div>
<?php           if ($val->ins_id == getUserSessionInfo("member_id") || $authority->admin_authority == "Y") { ?>
                        <div class="tbl-action">
                            <button type="button" class="btn btn-sm btn-danger comment-row-action" onclick="commentDelete('<?=$val->board_comment_idx ?>')">삭제</button>
                            <button type="button" class="btn btn-sm btn-success comment-row-action" onclick="commentEdit('<?=$val->board_comment_idx ?>')">수정</button>
                        </div>
<?php           } ?>
                    </div>
<?php       } ?>
                </div>

                <div class="mt-4" id="comment-editor-root"<?= $authority->write_authority == 'Y' ? '' : ' style="display:none;"' ?>>
                    <div id="comment-editor-panel">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="mb-0" id="comment-editor-title">댓글 작성</h5>
                            <span class="badge bg-warning text-dark" id="comment-editor-mode" style="display:none;">수정 중</span>
                        </div>
                        <div class="mb-3">
                            <textarea id="comment" name="comment" class="form-control" rows="4" placeholder="댓글을 입력하세요"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="comment_file" class="form-label">첨부파일</label>
                            <input type="file" class="form-control" id="comment_file" name="comment_file" onchange="uploadFile(this.id, 'board', 'commentUploadFileAfter')">
                            <div class="mb-2 mt-2 p-3 border rounded" id="comment_file_list" style="display:none;"></div>
                        </div>
<?php           if ($board_config->secret_comment_yn == 'Y') { ?>
                        <div class="mb-3" id="comment-secret-wrap">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="secret_yn" name="secret_yn" value="Y">
                                <label class="form-check-label" for="secret_yn"><i class="fas fa-lock"></i> 비밀 댓글</label>
                            </div>
                        </div>
<?php           } ?>
                        <div class="text-end d-flex gap-2 justify-content-end">
                            <button type="button" class="btn btn-secondary" id="comment-cancel-btn" onclick="commentCancel()" style="display:none;">취소</button>
                            <button type="button" class="btn btn-primary" id="comment-submit-btn" onclick="submitCommentEditor()">댓글 등록</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php   } ?>

    </div>
</main>

</form>

<!-- PDF.js 라이브러리 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

<script>
    const commentEditorState = {
        mode: 'create',
        board_comment_idx: null
    };
    const canCreateComment = <?= $authority->write_authority == 'Y' ? 'true' : 'false' ?>;

    // 메뉴강조
    $(window).on('load', function() {
        $('#a-board-top').addClass('active-level-1').attr({'data-bs-toggle': 'collapse', 'aria-expanded': 'true'});
        $('#collapse-board-top').addClass('show').addClass('submenu');
        $('#a-board-<?= $info->board_id ?>').addClass('active-level-2');

        if ($('#comment').length > 0) {
            initSummernote('#comment', { focus: false, height: 220 });
            resetCommentEditor();
        }
    });

    function boardDelete() {
        if (confirm('정말 삭제하시겠습니까?')) {
            ajax1('/board/<?= $info->board_id ?>/delete', 'frm', 'boardDeleteAfter');
        }
    }

    function boardDeleteAfter(proc_result) {
        var result = proc_result.result;
        var message = proc_result.message;
        var return_url = proc_result.return_url;
        if (result == true) {
            location.href = return_url;
        } else {
            alert(message);
        }
    }

    function commentInsert() {
        var update_form = new FormData();
        update_form.append('board_id', $('#board_id').val());
        update_form.append('board_idx', $('#board_idx').val());
        update_form.append('comment', getCommentEditorCode());
        update_form.append('secret_yn', getCommentSecretYn());
        update_form.append('file_idxs', getCommentFileIds());
        ajax1('/comment/insert', update_form, 'commentAfter');
    }

    function commentEdit(board_comment_idx) {
        if (commentEditorState.mode === 'edit') {
            alert('현재 댓글 수정이 진행 중입니다. 먼저 저장하거나 취소해주세요.');
            return;
        }

        ajax1('/comment/edit/'+board_comment_idx, 'frm', 'commentEditAfter');
    }

    function commentEditAfter(proc_result) {
        if (proc_result.result !== true) {
            alert(proc_result.message);
            return;
        }

        activateCommentEditMode(proc_result.board_comment_idx, proc_result.comment || '', proc_result.secret_yn || 'N', proc_result.file_list || []);
    }

    function commentUpdate(board_comment_idx) {
        var update_form = new FormData();
        update_form.append('board_comment_idx', board_comment_idx);
        update_form.append('comment', getCommentEditorCode());
        update_form.append('secret_yn', getCommentSecretYn());
        update_form.append('file_idxs', getCommentFileIds());
        ajax1('/comment/update', update_form, 'commentAfter');
    }

    function commentCancel() {
        resetCommentEditor();
    }

    function commentDelete(board_comment_idx) {
        if (commentEditorState.mode === 'edit') {
            alert('현재 댓글 수정이 진행 중입니다. 먼저 저장하거나 취소해주세요.');
            return;
        }

        if (confirm('댓글을 삭제하나요? 삭제하면 복구가 불가능합니다.')) {
            var update_form = new FormData();
            update_form.append('board_comment_idx', board_comment_idx);
            ajax1('/comment/delete', update_form, 'commentAfter');
        }
    }

    function submitCommentEditor() {
        if (commentEditorState.mode === 'edit') {
            commentUpdate(commentEditorState.board_comment_idx);
            return;
        }

        commentInsert();
    }

    function commentAfter() {
        location.reload();
    }

    function getCommentEditorCode() {
        if ($('#comment').length === 0) {
            return '';
        }

        return $('#comment').summernote('code');
    }

    function setCommentEditorCode(comment) {
        if ($('#comment').length === 0) {
            return;
        }

        $('#comment').summernote('code', normalizeCommentForEditor(comment));
    }

    function normalizeCommentForEditor(comment) {
        if (!comment) {
            return '';
        }

        if (/<[^>]+>/.test(comment)) {
            return comment;
        }

        return comment.replace(/\r\n|\r|\n/g, '<br>');
    }

    function activateCommentEditMode(board_comment_idx, comment, secret_yn, file_list) {
        var $row = $('#board_comment_idx_' + board_comment_idx);
        var $host = $row.find('.comment-edit-host');

        $('.comment-display').show();
        $('.comment-edit-host').addClass('d-none').empty();
        $('.tbl-action').show();

        $('#comment-editor-root').hide();
        $row.find('.comment-display').hide();
        $row.find('.tbl-action').hide();
        $host.removeClass('d-none').append($('#comment-editor-panel'));

        $('#comment-editor-title').text('댓글 수정');
        $('#comment-editor-mode').show();
        $('#comment-cancel-btn').show();
        $('#comment-submit-btn').text('댓글 저장');

        setCommentEditorCode(comment);
        setCommentSecretYn(secret_yn);
        setCommentFiles(file_list || []);
        setCommentActionLock(true);

        commentEditorState.mode = 'edit';
        commentEditorState.board_comment_idx = board_comment_idx;
    }

    function resetCommentEditor() {
        $('#comment-editor-root').append($('#comment-editor-panel'));
        $('.comment-display').show();
        $('.comment-edit-host').addClass('d-none').empty();
        $('.tbl-action').show();
        $('#comment-editor-title').text('댓글 작성');
        $('#comment-editor-mode').hide();
        $('#comment-cancel-btn').hide();
        $('#comment-submit-btn').text('댓글 등록');
        setCommentEditorCode('');
        setCommentSecretYn('N');
        clearCommentFiles();
        setCommentActionLock(false);

        if (canCreateComment) {
            $('#comment-editor-root').show();
        } else {
            $('#comment-editor-root').hide();
        }

        commentEditorState.mode = 'create';
        commentEditorState.board_comment_idx = null;
    }

    function getCommentSecretYn() {
        if ($('#secret_yn').length === 0) {
            return 'N';
        }

        return $('#secret_yn').is(':checked') ? 'Y' : 'N';
    }

    function setCommentSecretYn(secret_yn) {
        if ($('#secret_yn').length === 0) {
            return;
        }

        $('#secret_yn').prop('checked', secret_yn === 'Y');
    }

    function setCommentActionLock(isLocked) {
        $('.comment-row-action').prop('disabled', isLocked);
    }

    function commentUploadFileAfter(proc_result) {
        var result = proc_result.result;
        var message = proc_result.message;
        var info = proc_result.info;

        if (result !== true) {
            alert(message);
            return;
        }

        addCommentFileItem(info.file_id, info.file_name_org, info.file_size_kb, getFileIcon(info.file_ext));
    }

    function addCommentFileItem(fileId, fileNameOrg, fileSizeKb, iconClass) {
        var fileIds = getCommentFileIdsArray();
        if (fileIds.indexOf(fileId) === -1) {
            fileIds.push(fileId);
        }
        setCommentFileIds(fileIds);

        if ($('#comment_file_item_' + fileId).length > 0) {
            $('#comment_file_list').show();
            return;
        }

        var html = '';
        html += '<div class="d-flex justify-content-between align-items-center mb-2" id="comment_file_item_' + fileId + '">';
        html += '<div><i class="' + iconClass + '"></i> <a href="/file/download/' + fileId + '">' + escapeHtml(fileNameOrg) + '</a> <small class="text-muted">(' + fileSizeKb + 'KB)</small></div>';
        html += '<button type="button" class="btn btn-sm btn-danger" onclick="removeCommentFile(\'' + fileId + '\')">삭제</button>';
        html += '</div>';

        $('#comment_file_list').append(html).show();
    }

    function removeCommentFile(fileId) {
        $('#comment_file_item_' + fileId).remove();

        var fileIds = getCommentFileIdsArray().filter(function(id) {
            return id !== fileId;
        });
        setCommentFileIds(fileIds);

        if ($('#comment_file_list').children().length === 0) {
            $('#comment_file_list').hide();
        }
    }

    function clearCommentFiles() {
        setCommentFileIds([]);
        $('#comment_file_list').empty().hide();
        $('#comment_file').val('');
    }

    function setCommentFiles(fileList) {
        clearCommentFiles();

        fileList.forEach(function(file) {
            var iconClass = 'fas fa-file text-secondary';
            if (file.file_info && file.file_info.icon_class) {
                iconClass = file.file_info.icon_class;
            }

            var fileNameOrg = file.file_info ? file.file_info.file_name_org : file.file_id;
            var fileSizeKb = file.file_info ? file.file_info.file_size_kb : '-';
            addCommentFileItem(file.file_id, fileNameOrg, fileSizeKb, iconClass);
        });
    }

    function getCommentFileIds() {
        return $('#comment_file_idxs').val() || '';
    }

    function getCommentFileIdsArray() {
        var fileIds = getCommentFileIds();
        if (!fileIds) {
            return [];
        }

        return fileIds.split('||').filter(function(id) {
            return id !== '';
        });
    }

    function setCommentFileIds(fileIds) {
        $('#comment_file_idxs').val(fileIds.join('||'));
    }

    function getFileIcon(fileExt) {
        var iconMap = {
            'pdf': 'fas fa-file-pdf text-danger',
            'doc': 'fas fa-file-word text-primary',
            'docx': 'fas fa-file-word text-primary',
            'xls': 'fas fa-file-excel text-success',
            'xlsx': 'fas fa-file-excel text-success',
            'ppt': 'fas fa-file-powerpoint text-warning',
            'pptx': 'fas fa-file-powerpoint text-warning',
            'zip': 'fas fa-file-archive text-secondary',
            'rar': 'fas fa-file-archive text-secondary',
            'txt': 'fas fa-file-alt text-muted',
            'csv': 'fas fa-file-csv text-success'
        };

        return iconMap[fileExt] || 'fas fa-file text-secondary';
    }

    function escapeHtml(value) {
        return $('<div>').text(value || '').html();
    }

<?php if ($board_config->heart_yn == 'Y') { ?>
    function boardHeartToggle() {
        var update_form = new FormData();
        update_form.append('board_idx', $('#board_idx').val());
        ajax1('/board/<?= $info->board_id ?>/heart/toggle', update_form, 'boardHeartToggleAfter');
    }

    function boardHeartToggleAfter(proc_result) {
        var result = proc_result.result;
        var message = proc_result.message;
        if (result == true) {
            if (proc_result.heart_yn == 'Y') {
                $('#heart-button').removeClass('btn-outline-warning').addClass('btn-warning');
            } else {
                $('#heart-button').removeClass('btn-warning').addClass('btn-outline-warning');
            }
            $('#heart-cnt').text(Number(proc_result.heart_cnt).toLocaleString());
        } else {
            alert(message);
        }
    }

<?php } ?>

<?php if ($board_config->pdf_yn == 'Y' && $info->pdf_file_info != null) { ?>

    // 페이지 렌더링 대기
    function queueRenderPage(num) {
        if (pageRendering) {
            pageNumPending = num;
        } else {
            renderPage(num);
        }
    }

    // 이전 페이지
    function onPrevPage() {
        if (pageNum <= 1) {
            return;
        }
        pageNum--;
        queueRenderPage(pageNum);
    }
    document.getElementById('prev-page').addEventListener('click', onPrevPage);

    // 다음 페이지
    function onNextPage() {
        if (pageNum >= pdfDoc.numPages) {
            return;
        }
        pageNum++;
        queueRenderPage(pageNum);
    }
    document.getElementById('next-page').addEventListener('click', onNextPage);

    // 확대
    function onZoomIn() {
        scale += 0.25;
        document.getElementById('zoom-level').textContent = Math.round(scale * 100) + '%';
        queueRenderPage(pageNum);
    }
    document.getElementById('zoom-in').addEventListener('click', onZoomIn);

    // 축소
    function onZoomOut() {
        if (scale <= 0.5) {
            return;
        }
        scale -= 0.25;
        document.getElementById('zoom-level').textContent = Math.round(scale * 100) + '%';
        queueRenderPage(pageNum);
    }
    document.getElementById('zoom-out').addEventListener('click', onZoomOut);

    // PDF 로드
    const pdfUrl = '/file/view/<?= $info->pdf_file_id ?>';
    pdfjsLib.getDocument(pdfUrl).promise.then(function(pdfDoc_) {
        pdfDoc = pdfDoc_;
        document.getElementById('page-count').textContent = pdfDoc.numPages;
        document.getElementById('zoom-level').textContent = Math.round(scale * 100) + '%';
        renderPage(pageNum);
    }).catch(function(error) {
        console.error('PDF 로드 오류:', error);
        alert('PDF 파일을 불러오는데 실패했습니다.');
    });
<?php } ?>
</script>