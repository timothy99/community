<style>
/* 썸머노트 에디터 기본 폰트 설정 */
.note-editable {
    font-family: 'Noto Sans', sans-serif !important;
}
.note-editable p,
.note-editable div,
.note-editable span {
    font-family: 'Noto Sans', sans-serif;
}
</style>

<form id="frm" name="frm">

<input type="hidden" id="board_config_idx" name="board_config_idx" value="<?= $info->board_config_idx ?>">
<input type="hidden" id="form_style_code" name="form_style_code" value='<?=base64_encode($info->form_style) ?>'>
<input type="hidden" id="summer_code" name="summer_code">

<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
        <h3>게시판 설정</h3>

        <!-- 기본정보 -->
        <div class="card mb-4">
            <div class="card-header bg-primary bg-opacity-75 text-white">기본정보</div>
            <div class="card-body">
                <!-- 게시판 아이디 -->
                <div class="mb-3">
                    <label for="board_id" class="form-label">게시판 아이디 <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="board_id" name="board_id" placeholder="게시판 아이디를 입력하세요 (예: notice, qna)" value="<?= $info->board_id ?>" maxlength="20">
                    <small class="text-muted">영문 소문자, 숫자만 사용 가능합니다.</small>
                </div>

                <!-- 제목 -->
                <div class="mb-3">
                    <label for="title" class="form-label">게시판 제목 <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="게시판 제목을 입력하세요" value="<?= $info->title ?>" maxlength="1000">
                </div>

                <!-- 타입(스킨) -->
                <div class="mb-3">
                    <label for="type" class="form-label">타입(스킨)</label>
                    <select class="form-select" id="type" name="type">
                        <option value="">선택하세요</option>
                        <option value="board">board</option>
                        <option value="gallery">gallery</option>
                        <option value="faq">faq</option>
                    </select>
                    <small class="text-muted">게시판 스킨을 선택하세요.</small>
                </div>

                <!-- 화면에 보여줄 줄 수 -->
                <div class="mb-3">
                    <label for="base_rows" class="form-label">화면에 보여줄 줄 수 <span class="text-danger">*</span></label>
                    <input type="number" class="form-control w-25" id="base_rows" name="base_rows" placeholder="10" value="<?= $info->base_rows ?>" min="1">
                    <small class="text-muted">한 페이지에 표시할 게시글 수입니다.</small>
                </div>
            </div>
        </div>

        <!-- 카테고리 설정 -->
        <div class="card mb-4">
            <div class="card-header bg-info bg-opacity-75 text-white">카테고리 설정</div>
            <div class="card-body">
                <!-- 카테고리 사용여부 -->
                <div class="mb-3">
                    <label class="form-label">카테고리 사용여부 <span class="text-danger">*</span></label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="category_yn" id="category_yn_y" value="Y">
                            <label class="form-check-label" for="category_yn_y">사용</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="category_yn" id="category_yn_n" value="N">
                            <label class="form-check-label" for="category_yn_n">미사용</label>
                        </div>
                    </div>
                </div>

                <!-- 카테고리 -->
                <div class="mb-3">
                    <label for="category" class="form-label">카테고리</label>
                    <input type="text" class="form-control" id="category" name="category" placeholder="카테고리를 사용할 경우 파이드 2개(||)로 구분하여 입력합니다. (예: 공지사항||이벤트||뉴스)" value="<?= $info->category ?>" maxlength="300">
                    <small class="text-muted">카테고리를 사용할 경우 파이드 2개(||)로 구분하여 입력합니다.</small>
                </div>
            </div>
        </div>

        <!-- 사용자 화면 설정 -->
        <div class="card mb-4">
            <div class="card-header bg-teal text-white">사용자 화면 설정</div>
            <div class="card-body">

                <!-- 사용자 글쓰기 권한 -->
                <div class="mb-3">
                    <label class="form-label">사용자 글쓰기 가능 여부 <span class="text-danger">*</span></label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="user_write" id="user_write_y" value="Y">
                            <label class="form-check-label" for="user_write_y">가능</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="user_write" id="user_write_n" value="N">
                            <label class="form-check-label" for="user_write_n">불가능</label>
                        </div>
                    </div>
                    <small class="text-muted">일반 사용자의 글쓰기 권한을 설정합니다.</small>
                </div>

                <!-- 사용자 댓글쓰기 권한 -->
                <div class="mb-3">
                    <label class="form-label">사용자 댓글쓰기 가능 여부 <span class="text-danger">*</span></label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="comment_write" id="comment_write_y" value="Y">
                            <label class="form-check-label" for="comment_write_y">가능</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="comment_write" id="comment_write_n" value="N">
                            <label class="form-check-label" for="comment_write_n">불가능</label>
                        </div>
                    </div>
                    <small class="text-muted">일반 사용자의 댓글쓰기 권한을 설정합니다.</small>
                </div>

                <!-- 조회수 노출 기능 사용여부 -->
                <div class="mb-3">
                    <label class="form-label">조회수 노출 기능 사용 여부 <span class="text-danger">*</span></label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="hit_yn" id="hit_yn_y" value="Y">
                            <label class="form-check-label" for="hit_yn_y">사용</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="hit_yn" id="hit_yn_n" value="N">
                            <label class="form-check-label" for="hit_yn_n">미사용</label>
                        </div>
                    </div>
                    <small class="text-muted">게시글 조회수 노출 기능 사용 여부를 설정합니다.</small>
                </div>

                <!-- 공감 기능 사용여부 -->
                <div class="mb-3">
                    <label class="form-label">공감 기능 사용 여부 <span class="text-danger">*</span></label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="heart_yn" id="heart_yn_y" value="Y">
                            <label class="form-check-label" for="heart_yn_y">사용</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="heart_yn" id="heart_yn_n" value="N">
                            <label class="form-check-label" for="heart_yn_n">미사용</label>
                        </div>
                    </div>
                    <small class="text-muted">게시글 공감 기능 사용 여부를 설정합니다.</small>
                </div>

                <!-- PDF 보기 기능 사용여부 -->
                <div class="mb-3">
                    <label class="form-label">PDF 보기 기능 사용 여부 <span class="text-danger">*</span></label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="pdf_yn" id="pdf_yn_y" value="Y">
                            <label class="form-check-label" for="pdf_yn_y">사용</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="pdf_yn" id="pdf_yn_n" value="N">
                            <label class="form-check-label" for="pdf_yn_n">미사용</label>
                        </div>
                    </div>
                    <small class="text-muted">게시글에서 PDF 보기 기능 사용 여부를 설정합니다.</small>
                </div>

                <!-- 유튜브 기능 사용여부 -->
                <div class="mb-3">
                    <label class="form-label">유튜브 기능 사용 여부 <span class="text-danger">*</span></label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="youtube_yn" id="youtube_yn_y" value="Y">
                            <label class="form-check-label" for="youtube_yn_y">사용</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="youtube_yn" id="youtube_yn_n" value="N">
                            <label class="form-check-label" for="youtube_yn_n">미사용</label>
                        </div>
                    </div>
                    <small class="text-muted">게시글에서 유튜브 영상 삽입 기능 사용 여부를 설정합니다.</small>
                </div>

            </div>
        </div>

        <!-- 관리자 수정 권한 설정 -->
        <div class="card mb-4">
            <div class="card-header bg-success bg-opacity-75 text-white">관리자 수정 권한 설정</div>
            <div class="card-body">
                <!-- 입력일 수정 기능 -->
                <div class="mb-3">
                    <label class="form-label">입력일 수정 기능 사용 여부 <span class="text-danger">*</span></label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="reg_date_yn" id="reg_date_yn_y" value="Y">
                            <label class="form-check-label" for="reg_date_yn_y">사용</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="reg_date_yn" id="reg_date_yn_n" value="N">
                            <label class="form-check-label" for="reg_date_yn_n">미사용</label>
                        </div>
                    </div>
                    <small class="text-muted">관리자에서 게시글 등록일을 수동으로 수정할 수 있는 기능입니다.</small>
                </div>

                <!-- 조회수 수정 기능 -->
                <div class="mb-3">
                    <label class="form-label">조회수 수정 기능 사용 여부 <span class="text-danger">*</span></label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="hit_edit_yn" id="hit_edit_yn_y" value="Y">
                            <label class="form-check-label" for="hit_edit_yn_y">사용</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="hit_edit_yn" id="hit_edit_yn_n" value="N">
                            <label class="form-check-label" for="hit_edit_yn_n">미사용</label>
                        </div>
                    </div>
                    <small class="text-muted">관리자에서 게시글 조회수를 수동으로 수정할 수 있는 기능입니다.</small>
                </div>
            </div>
        </div>

        <!-- 권한별 접속 설정 -->
        <div class="card mb-4">
            <div class="card-header bg-danger bg-opacity-75 text-white">권한별 접속 설정</div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="list_authority" class="form-label">목록</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="list_authority1" name="list_authority[]" value="전체">
                            <label class="form-check-label" for="list_authority1">전체</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="list_authority2" name="list_authority[]" value="로그인">
                            <label class="form-check-label" for="list_authority2">로그인</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="list_authority3" name="list_authority[]" value="관리자">
                            <label class="form-check-label" for="list_authority3">관리자</label>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="view_authority" class="form-label">상세</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="view_authority1" name="view_authority[]" value="전체">
                            <label class="form-check-label" for="view_authority1">전체</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="view_authority2" name="view_authority[]" value="로그인">
                            <label class="form-check-label" for="view_authority2">로그인</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="view_authority3" name="view_authority[]" value="관리자">
                            <label class="form-check-label" for="view_authority3">관리자</label>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="write_authority" class="form-label">쓰기</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="write_authority1" name="write_authority[]" value="전체">
                            <label class="form-check-label" for="write_authority1">전체</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="write_authority2" name="write_authority[]" value="로그인">
                            <label class="form-check-label" for="write_authority2">로그인</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="write_authority3" name="write_authority[]" value="관리자">
                            <label class="form-check-label" for="write_authority3">관리자</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 파일 업로드 설정 -->
        <div class="card mb-4">
            <div class="card-header bg-warning bg-opacity-75 text-white">파일 업로드 설정</div>
            <div class="card-body">
                <!-- 최대 첨부파일 수 -->
                <div class="mb-3">
                    <label for="file_cnt" class="form-label">최대 첨부파일 수 <span class="text-danger">*</span></label>
                    <input type="number" class="form-control w-25" id="file_cnt" name="file_cnt" placeholder="0" value="<?= $info->file_cnt ?>" min="0">
                    <small class="text-muted">0을 입력하면 파일 업로드를 사용하지 않습니다.</small>
                </div>

                <!-- 최대 파일 업로드 용량 -->
                <div class="mb-3">
                    <label for="file_upload_size_limit" class="form-label">최대 파일 업로드 용량 (MB)</label>
                    <input type="number" class="form-control w-25" id="file_upload_size_limit" name="file_upload_size_limit" placeholder="10" value="<?= $info->file_upload_size_limit ?>" min="0">
                    <small class="text-muted">개별 파일의 최대 용량입니다. (서버 설정에 영향을 받습니다)</small>
                </div>

                <!-- 총 파일 업로드 용량 -->
                <div class="mb-3">
                    <label for="file_upload_size_total" class="form-label">총 파일 업로드 용량 (MB)</label>
                    <input type="number" class="form-control w-25" id="file_upload_size_total" name="file_upload_size_total" placeholder="50" value="<?= $info->file_upload_size_total ?>" min="0">
                    <small class="text-muted">모든 첨부파일의 총 용량입니다. (서버 설정에 영향을 받습니다)</small>
                </div>
            </div>
        </div>

        <!-- 포인트 설정 -->
        <div class="card mb-4">
            <div class="card-header bg-secondary bg-opacity-75 text-white">포인트 설정</div>
            <div class="card-body">
                <!-- 글 작성 포인트 -->
                <div class="mb-3">
                    <label for="write_point" class="form-label">글 작성시 지급 포인트</label>
                    <input type="number" class="form-control w-25" id="write_point" name="write_point" placeholder="0" value="<?= $info->write_point ?>" min="0">
                    <small class="text-muted">게시글 작성시 지급할 포인트입니다.</small>
                </div>

                <!-- 댓글 작성 포인트 -->
                <div class="mb-3">
                    <label for="comment_point" class="form-label">댓글 작성시 지급 포인트</label>
                    <input type="number" class="form-control w-25" id="comment_point" name="comment_point" placeholder="0" value="<?= $info->comment_point ?>" min="0">
                    <small class="text-muted">댓글 작성시 지급할 포인트입니다.</small>
                </div>
            </div>
        </div>

        <!-- 폼 양식 설정 -->
        <div class="card mb-4">
            <div class="card-header bg-dark bg-opacity-75 text-white">폼 양식 설정</div>
            <div class="card-body">
                <!-- 폼 양식 사용여부 -->
                <div class="mb-3">
                    <label class="form-label">폼 양식 사용 여부 <span class="text-danger">*</span></label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="form_style_yn" id="form_style_yn_y" value="Y">
                            <label class="form-check-label" for="form_style_yn_y">사용</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="form_style_yn" id="form_style_yn_n" value="N">
                            <label class="form-check-label" for="form_style_yn_n">미사용</label>
                        </div>
                    </div>
                </div>

                <!-- 폼 양식 -->
                <div class="mb-3">
                    <label for="form_style" class="form-label">폼 양식</label>
                    <textarea class="form-control font-monospace" id="form_style" name="form_style"><?= $info->form_style ?></textarea>
                    <small class="text-muted">게시판 폼에 적용할 양식을 입력합니다.</small>
                </div>
            </div>
        </div>

        <!-- 버튼 영역 -->
        <div class="card">
            <div class="card-footer text-end">
                <div class="d-flex gap-2 justify-content-end">
                    <a href="/csl/settings/board/list" class="btn btn-secondary">취소</a>
                    <button type="button" class="btn btn-primary" onclick="boardConfigUpdate()">저장</button>
                </div>
            </div>
        </div>

    </div>
</main>

</form>

<script>
    // 메뉴강조 및 초기화
    $(window).on('load', function() {
        $('#li-settings-board-list').addClass('active-level-1');

        // 썸머노트 초기화 (포커스 자동 이동 비활성화)
        initSummernote('#form_style', { focus: false });

        // 라디오 버튼 초기값 설정
        $('input[name="category_yn"][value="<?=$info->category_yn ?>"]').prop('checked', true);
        $('input[name="user_write"][value="<?=$info->user_write ?>"]').prop('checked', true);
        $('input[name="comment_write"][value="<?=$info->comment_write ?>"]').prop('checked', true);
        $('input[name="reg_date_yn"][value="<?=$info->reg_date_yn ?>"]').prop('checked', true);
        $('input[name="form_style_yn"][value="<?=$info->form_style_yn ?>"]').prop('checked', true);
        $('input[name="hit_edit_yn"][value="<?=$info->hit_edit_yn ?>"]').prop('checked', true);
        $('input[name="hit_yn"][value="<?=$info->hit_yn ?>"]').prop('checked', true);
        $('input[name="heart_yn"][value="<?=$info->heart_yn ?>"]').prop('checked', true);
        $('input[name="pdf_yn"][value="<?=$info->pdf_yn ?>"]').prop('checked', true);
        $('input[name="youtube_yn"][value="<?=$info->youtube_yn ?>"]').prop('checked', true);
        $('#type').val('<?=$info->type ?>');

<?php   foreach ($authority_list as $val){ ?>
        var auth_groups = "<?=$val->auth_group?>".split(', ');
        var authority_role = "<?=$val->authority_role?>";

        // authority_role에 따라 해당하는 체크박스들을 체크
        auth_groups.forEach(function(group) {
            if (group.trim() !== '') {
                $('input[name="' + authority_role + '_authority[]"][value="' + group.trim() + '"]').prop('checked', true);
            }
        });
<?php   } ?>
    });

    // 게시판 설정 업데이트
    function boardConfigUpdate() {
        $("#summer_code").val($("#form_style").summernote("code"));
        ajax1('/csl/settings/board/update', 'frm', 'boardConfigUpdateAfter');
    }

    // 업데이트 후 처리
    function boardConfigUpdateAfter(proc_result) {
        var result = proc_result.result;
        var message = proc_result.message;
        var return_url = proc_result.return_url;

        alert(message);
        if (result == true) {
            location.href = return_url;
        }
    }
</script>