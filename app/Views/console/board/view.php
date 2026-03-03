<form id="frm" name="frm">

<input type="hidden" id="board_idx" name="board_idx" value="<?= $info->board_idx ?>">
<input type="hidden" id="board_id" name="board_id" value="<?= $info->board_id ?>">

<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
        <h3>게시판 보기</h3>

        <div class="card mb-4">
            <div class="card-header bg-success bg-opacity-75 text-white">기본정보</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap">
                        <colgroup>
                            <col style="width: 15%;">
                            <col style="width: 80%;">
                        </colgroup>
                        <tbody>
<?php if ($board_config->category_yn == 'Y') { ?>
                            <tr>
                                <th class="align-middle bg-light">카테고리</th>
                                <td><?= $info->category ?></td>
                            </tr>
<?php } ?>
                            <tr>
                                <th class="align-middle bg-light">제목</th>
                                <td><?= $info->title ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">내용</th>
                                <td><?= nl2br($info->contents) ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">대표 이미지</th>
                                <td>
<?php   if ($info->main_image_info != null) { ?>
                                    <div class="mb-3">
                                        <img src="/file/view/<?= $info->main_image_id ?>" class="img-thumbnail img-fluid" style="max-width: 100%; height: auto;">
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-auto">
                                            <small class="text-muted d-block">원본파일명</small>
                                            <strong><?= $info->main_image_info->file_name_org ?></strong>
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
<?php   } else { ?>
                                    <p class="text-muted mb-0">등록된 이미지가 없습니다.</p>
<?php   } ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">링크 URL</th>
                                <td>
<?php   if (!empty($info->url_link)) { ?>
                                    <a href="<?= $info->url_link ?>" target="_blank"><?= $info->url_link ?></a>
<?php   } else { ?>
                                    <span class="text-muted">-</span>
<?php   } ?>
                                </td>
                            </tr>
<?php if ($board_config->pdf_yn == 'Y') { ?>
                            <tr>
                                <th class="align-middle bg-light">PDF 파일</th>
                                <td>
<?php   if ($info->pdf_file_info != null) { ?>
                                    <div class="row g-3">
                                        <div class="col-auto">
                                            <small class="text-muted d-block">원본파일명</small>
                                            <strong><?= $info->pdf_file_info->file_name_org ?></strong>
                                        </div>
                                        <div class="col-auto">
                                            <small class="text-muted d-block">파일 크기</small>
                                            <strong><?= $info->pdf_file_info->file_size_kb ?> KB</strong>
                                        </div>
                                        <div class="col-auto">
                                            <a href="/file/download/<?= $info->pdf_file_id ?>" class="btn btn-sm btn-primary">다운로드</a>
                                        </div>
                                    </div>
<?php   } else { ?>
                                    <p class="text-muted mb-0">등록된 파일이 없습니다.</p>
<?php   } ?>
                                </td>
                            </tr>
<?php } ?>
<?php if ($board_config->youtube_yn == 'Y') { ?>
                            <tr>
                                <th class="align-middle bg-light">유튜브 링크</th>
                                <td>
<?php   if (!empty($info->youtube_link)) { ?>
                                    <a href="<?= $info->youtube_link ?>" target="_blank"><?= $info->youtube_link ?></a>
<?php   } else { ?>
                                    <span class="text-muted">-</span>
<?php   } ?>
                                </td>
                            </tr>
<?php } ?>
<?php if ($board_config->hit_yn == 'Y') { ?>
                            <tr>
                                <th class="align-middle bg-light">조회수</th>
                                <td><?= number_format($info->hit_cnt) ?></td>
                            </tr>
<?php } ?>
                            <tr>
                                <th class="align-middle bg-light">공지여부</th>
                                <td><?= code_replace('notice_yn', $info->notice_yn) ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">등록자</th>
                                <td><?= $info->ins_id ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">입력일</th>
                                <td><?= $info->ins_date_txt ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">수정자</th>
                                <td><?= $info->upd_id ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">수정일</th>
                                <td><?= $info->upd_date_txt ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">등록일</th>
                                <td><?= $info->reg_date_txt ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-end">
                <div class="d-flex gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger" onclick="boardDelete()">삭제</button>
                    <a href="/csl/board/<?= $info->board_id ?>/list" class="btn btn-secondary">목록</a>
                    <a href="/csl/board/<?= $info->board_id ?>/edit/<?= $info->board_idx ?>" class="btn btn-primary">수정</a>
                </div>
            </div>
        </div>
    </div>
</main>

</form>

<script>
    // 메뉴강조
    $(window).on('load', function() {
        $('#a-board-top').addClass('active-level-1').attr({'data-bs-toggle': 'collapse', 'aria-expanded': 'true'});
        $('#collapse-board-top').addClass('show').addClass('submenu');
        $('#a-board-<?= $info->board_id ?>').addClass('active-level-2');
    });

    function boardDelete() {
        if (confirm('정말 삭제하시겠습니까?')) {
            ajax1('/csl/board/<?= $info->board_id ?>/delete', 'frm', 'boardDeleteAfter');
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
</script>