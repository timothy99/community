<form id="frm" name="frm">

<input type="hidden" id="board_idx" name="board_idx" value="<?= $info->board_idx ?>">
<input type="hidden" id="board_id" name="board_id" value="<?= $info->board_id ?>">

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
            <div class="card-footer text-end">
                <div class="d-flex gap-2 justify-content-end">
<?php   if ($authority->delete_authority == "Y") { ?>
                    <button type="button" class="btn btn-danger" onclick="boardDelete()">삭제</button>
<?php   } ?>
                    <a href="/board/<?= $info->board_id ?>/list" class="btn btn-secondary">목록</a>
<?php   if ($authority->edit_authority == "Y") { ?>
                    <a href="/board/<?= $info->board_id ?>/edit/<?= $info->board_idx ?>" class="btn btn-primary">수정</a>
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
                        <div class="tbl-label"><?= $val->member_info->member_nickname ?><br><?= $val->ins_date_txt ?></div>
                        <div class="tbl-value"><?= nl2br($val->comment) ?></div>
<?php           if ($val->ins_id == getUserSessionInfo("member_id") || $authority->admin_authority == "Y") { ?>
                        <div class="tbl-action">
                            <button type="button" class="btn btn-sm btn-danger" onclick="commentDelete('<?=$val->board_comment_idx ?>')">삭제</button>
                            <button type="button" class="btn btn-sm btn-success" onclick="commentEdit('<?=$val->board_comment_idx ?>')">수정</button>
                        </div>
<?php           } ?>
                    </div>
<?php       } ?>
                </div>

<?php       if ($authority->write_authority == 'Y') { ?>
                <!-- 댓글 작성 -->
                <div class="mt-4">
                    <h5 class="mb-3">댓글 작성</h5>
                    <div class="mb-3">
                        <textarea id="comment" name="comment" class="form-control" rows="4" placeholder="댓글을 입력하세요"></textarea>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-primary" onclick="commentInsert()">댓글 등록</button>
                    </div>
                </div>
<?php       } ?>
            </div>
        </div>
<?php   } ?>

    </div>
</main>

</form>

<!-- PDF.js 라이브러리 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

<script>
    // 메뉴강조
    $(window).on('load', function() {
        $('#a-board-top').addClass('active-level-1').attr({'data-bs-toggle': 'collapse', 'aria-expanded': 'true'});
        $('#collapse-board-top').addClass('show').addClass('submenu');
        $('#a-board-<?= $info->board_id ?>').addClass('active-level-2');
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
        update_form.append('comment', $('#comment').val());
        ajax1('/comment/insert', update_form, 'commentAfter');
    }

    function commentEdit(board_comment_idx) {
        console.log(board_comment_idx);
        var update_form = new FormData();
        update_form.append('board_comment_idx', board_comment_idx);
        ajax1('/comment/edit/'+board_comment_idx, 'frm', 'commentEditAfter');
    }

    function commentEditAfter(proc_result) {
        var board_comment_idx = proc_result.board_comment_idx;
        var return_html = proc_result.return_html;
        $('#board_comment_idx_'+board_comment_idx).html(return_html);
    }

    function commentUpdate(board_comment_idx) {
        var update_form = new FormData();
        var comment = $('#comment_'+board_comment_idx).val();
        update_form.append('board_comment_idx', board_comment_idx);
        update_form.append('comment', comment);
        ajax1('/comment/update', update_form, 'commentAfter');
    }

    function commentCancel(board_comment_idx) {
        location.reload();
    }

    function commentDelete(board_comment_idx) {
        if(confirm('댓글을 삭제하나요? 삭제하면 복구가 불가능합니다.')) {
            var update_form = new FormData();
            update_form.append('board_comment_idx', board_comment_idx);
            ajax1('/comment/delete', update_form, 'commentAfter');
        }
    }

    function commentAfter() {
        location.reload();
    }

<?php if ($board_config->pdf_yn == 'Y' && $info->pdf_file_info != null) { ?>
    // PDF.js 초기화
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    let pdfDoc = null;
    let pageNum = 1;
    let pageRendering = false;
    let pageNumPending = null;
    let scale = 1.0;

    const canvas = document.getElementById('pdf-canvas');
    const ctx = canvas.getContext('2d');

    // PDF 페이지 렌더링
    function renderPage(num) {
        pageRendering = true;
        pdfDoc.getPage(num).then(function(page) {
            const viewport = page.getViewport({ scale: scale });
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            const renderContext = {
                canvasContext: ctx,
                viewport: viewport
            };

            const renderTask = page.render(renderContext);
            renderTask.promise.then(function() {
                pageRendering = false;
                if (pageNumPending !== null) {
                    renderPage(pageNumPending);
                    pageNumPending = null;
                }
            });
        });

        document.getElementById('page-num').textContent = num;
    }

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