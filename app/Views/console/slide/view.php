<form id="frm" name="frm">

<input type="hidden" id="slide_idx" name="slide_idx" value="<?= $info->slide_idx ?>">

<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
        <h3>슬라이드</h3>

        <div class="card mb-4">
            <div class="card-header bg-success bg-opacity-75 text-white">기본정보</div>
            <div class="card-body">
                <!-- 정렬순서 -->
                <div class="mb-3">
                    <label class="form-label">정렬순서</label>
                    <input type="text" class="form-control bg-light" value="<?= $info->order_no ?>" readonly>
                </div>

                <!-- 제목 -->
                <div class="mb-3">
                    <label class="form-label">제목</label>
                    <input type="text" class="form-control bg-light" value="<?= $info->title ?>" readonly>
                </div>

                <!-- 소제목 -->
                <div class="mb-3">
                    <label class="form-label">소제목</label>
                    <input type="text" class="form-control bg-light" value="<?= $info->sub_title ?>" readonly>
                </div>

                <!-- 내용 (alt) -->
                <div class="mb-3">
                    <label class="form-label">내용 <span class="text-muted small">(이미지 alt 텍스트)</span></label>
                    <textarea class="form-control bg-light" rows="3" readonly><?= $info->contents ?></textarea>
                </div>

                <!-- URL 링크 -->
                <div class="mb-3">
                    <label class="form-label">링크 URL</label>
                    <input type="text" class="form-control bg-light" value="<?= $info->url_link ?>" readonly>
                </div>

                <!-- 게시기간 -->
                <div class="mb-3">
                    <label class="form-label">게시기간</label>
                    <div class="row g-2 align-items-center">
                        <div class="col-auto">
                            <input type="text" class="form-control bg-light" value="<?= $info->start_date_txt ?>" readonly>
                        </div>
                        <div class="col-auto text-muted">~</div>
                        <div class="col-auto">
                            <input type="text" class="form-control bg-light" value="<?= $info->end_date_txt ?>" readonly>
                        </div>
                    </div>
                </div>

                <!-- 노출여부 -->
                <div class="mb-3">
                    <label class="form-label">노출여부</label>
                    <div>
                        <span class="badge <?= $info->display_yn_badge ?>">
                            <?= code_replace('display_yn', $info->display_yn) ?>
                        </span>
                    </div>
                </div>

                <!-- 슬라이드 이미지 -->
                <div class="mb-3">
                    <label class="form-label">슬라이드 이미지</label>
                    <div class="mt-2">
<?php   if ($info->slide_file_info != null) { ?>
                        <div class="row g-2 align-items-center">
                            <div class="col">
                                <img src="/csl/file/view/<?= $info->slide_file ?>" class="img-thumbnail" style="width: 300px; height: auto;">
                            </div>
                            <div class="col">
                                <small class="text-muted">원본파일명</small><br>
                                <?= $info->slide_file_info->file_name_org ?>
                            </div>
                            <div class="col">
                                <small class="text-muted">가로해상도</small><br>
                                <?= $info->slide_file_info->image_width_txt ?>px
                            </div>
                            <div class="col">
                                <small class="text-muted">세로해상도</small><br>
                                <?= $info->slide_file_info->image_height_txt ?>px
                            </div>
                            <div class="col">
                                <small class="text-muted">사이즈</small><br>
                                <?= $info->slide_file_info->file_size_kb ?> KB
                            </div>
                        </div>
<?php   } else { ?>
                        <p class="text-muted">등록된 이미지가 없습니다.</p>
<?php   } ?>
                    </div>
                </div>

            </div>
            <div class="card-footer text-end">
                <div class="d-flex gap-2 justify-content-end">
                    <a href="/csl/slide/list" class="btn btn-secondary">목록</a>
                    <a href="/csl/slide/edit/<?= $info->slide_idx ?>" class="btn btn-primary">수정</a>
                    <button type="button" class="btn btn-danger" onclick="slideDelete()">삭제</button>
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