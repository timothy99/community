<form id="frm" name="frm">

<input type="hidden" id="slide_idx" name="slide_idx" value="<?= $info->slide_idx ?>">

<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
        <h3>슬라이드</h3>

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
                            <tr>
                                <th class="align-middle bg-light">정렬순서</th>
                                <td><?= $info->order_no ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">제목</th>
                                <td><?= $info->title ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">소제목</th>
                                <td><?= $info->sub_title ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">내용</th>
                                <td><?= nl2br($info->contents) ?></td>
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
                            <tr>
                                <th class="align-middle bg-light">게시기간</th>
                                <td><?= $info->start_date_txt ?> ~ <?= $info->end_date_txt ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">노출여부</th>
                                <td>
                                    <span class="badge <?= $info->display_yn_badge ?>">
                                        <?= code_replace('display_yn', $info->display_yn) ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">슬라이드 이미지</th>
                                <td>
<?php   if ($info->slide_file_info != null) { ?>
                                    <div class="mb-3">
                                        <img src="/csl/file/view/<?= $info->slide_file ?>" class="img-thumbnail img-fluid" style="max-width: 100%; height: auto;">
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-auto">
                                            <small class="text-muted d-block">원본파일명</small>
                                            <strong><?= $info->slide_file_info->file_name_org ?></strong>
                                        </div>
                                        <div class="col-auto">
                                            <small class="text-muted d-block">해상도</small>
                                            <strong><?= $info->slide_file_info->image_width_txt ?> × <?= $info->slide_file_info->image_height_txt ?> px</strong>
                                        </div>
                                        <div class="col-auto">
                                            <small class="text-muted d-block">파일 크기</small>
                                            <strong><?= $info->slide_file_info->file_size_kb ?> KB</strong>
                                        </div>
                                    </div>
<?php   } else { ?>
                                    <p class="text-muted mb-0">등록된 이미지가 없습니다.</p>
<?php   } ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-end">
                <div class="d-flex gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger" onclick="slideDelete()">삭제</button>
                    <a href="/csl/slide/list" class="btn btn-secondary">목록</a>
                    <a href="/csl/slide/edit/<?= $info->slide_idx ?>" class="btn btn-primary">수정</a>
                </div>
            </div>
        </div>
    </div>
</main>

</form>

<script>
    // 메뉴강조
    $(window).on('load', function() {
        // $('#li-slide').addClass('active-level-1').attr({'data-bs-toggle': 'collapse', 'aria-expanded': 'true'});
        $('#li-slide').addClass('active-level-1');
    });

    function slideDelete() {
        if (confirm('정말 삭제하시겠습니까?')) {
            ajax1('/csl/slide/delete', 'frm', 'slideDeleteAfter');
        }
    }

    function slideDeleteAfter(proc_result) {
        var result = proc_result.result;
        var message = proc_result.message;
        if (result == true) {
            location.href = '/csl/slide/list';
        } else {
            alert(message);
        }
    }
</script>