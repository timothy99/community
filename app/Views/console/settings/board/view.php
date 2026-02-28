<form id="frm" name="frm">

<input type="hidden" id="board_config_idx" name="board_config_idx" value="<?= $info->board_config_idx ?>">

<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
        <h3>게시판 설정</h3>

        <div class="card mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap">
                        <colgroup>
                            <col style="width: 20%;">
                            <col style="width: 80%;">
                        </colgroup>
                        <tbody>
                            <!-- 기본정보 -->
                            <tr>
                                <th colspan="2" class="bg-primary bg-opacity-75 text-white">기본정보</th>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">게시판 아이디</th>
                                <td><?= $info->board_id ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">게시판 제목</th>
                                <td><?= $info->title ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">타입(스킨)</th>
                                <td><?= !empty($info->type) ? $info->type : '<span class="text-muted">-</span>' ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">화면에 보여줄 줄 수</th>
                                <td><?= $info->base_rows ?></td>
                            </tr>

                            <!-- 카테고리 설정 -->
                            <tr>
                                <th colspan="2" class="bg-info bg-opacity-75 text-white">카테고리 설정</th>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">카테고리 사용여부</th>
                                <td><?= code_replace('category_yn', $info->category_yn) ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">카테고리</th>
                                <td><?= $info->category ?></td>
                            </tr>

                            <!-- 사용자 화면 설정 -->
                            <tr>
                                <th colspan="2" class="bg-teal text-white">사용자 화면 설정</th>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">사용자 글쓰기 가능 여부</th>
                                <td><?= code_replace('user_write', $info->user_write) ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">사용자 댓글쓰기 가능 여부</th>
                                <td><?= code_replace('comment_write', $info->comment_write) ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">조회수 노출 기능 사용 여부</th>
                                <td><?= code_replace('hit_yn', $info->hit_yn) ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">공감 기능 사용 여부</th>
                                <td><?= code_replace('heart_yn', $info->heart_yn) ?></td>
                            </tr>

                            <!-- 권한 설정 -->
                            <tr>
                                <th colspan="2" class="bg-success bg-opacity-75 text-white">권한 설정</th>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">입력일 수정 기능 사용 여부</th>
                                <td><?= code_replace('reg_date_yn', $info->reg_date_yn) ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">조회수 수정 기능 사용 여부</th>
                                <td><?= code_replace('hit_edit_yn', $info->hit_edit_yn) ?></td>
                            </tr>

                            <!-- 파일 업로드 설정 -->
                            <tr>
                                <th colspan="2" class="bg-warning bg-opacity-75 text-white">파일 업로드 설정</th>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">최대 첨부파일 수</th>
                                <td><?= $info->file_cnt ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">최대 파일 업로드 용량</th>
                                <td><?= $info->file_upload_size_limit ?> MB</td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">총 파일 업로드 용량</th>
                                <td><?= $info->file_upload_size_total ?> MB</td>
                            </tr>

                            <!-- 포인트 설정 -->
                            <tr>
                                <th colspan="2" class="bg-secondary bg-opacity-75 text-white">포인트 설정</th>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">글 작성시 지급 포인트</th>
                                <td><?= $info->write_point ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">댓글 작성시 지급 포인트</th>
                                <td><?= $info->comment_point ?></td>
                            </tr>

                            <!-- 폼 양식 설정 -->
                            <tr>
                                <th colspan="2" class="bg-dark bg-opacity-75 text-white">폼 양식 설정</th>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">폼 양식 사용 여부</th>
                                <td><?= code_replace('form_style_yn', $info->form_style_yn) ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">폼 양식</th>
                                <td>
                                    <div class="border rounded p-3 bg-light">
                                        <?= $info->form_style ?>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-end">
                <div class="d-flex gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger" onclick="boardConfigDelete()">삭제</button>
                    <a href="/csl/settings/board/list" class="btn btn-secondary">목록</a>
                    <a href="/csl/settings/board/edit/<?= $info->board_id ?>" class="btn btn-primary">수정</a>
                </div>
            </div>
        </div>

    </div>
</main>

</form>

<script>
    // 메뉴강조
    $(window).on('load', function() {
        $('#li-settings-board-list').addClass('active-level-1');
    });

    // 게시판 설정 삭제
    function boardConfigDelete() {
        if (confirm('정말 삭제하시겠습니까?')) {
            ajax1('/csl/settings/board/delete', 'frm', 'boardConfigDeleteAfter');
        }
    }

    // 삭제 후 처리
    function boardConfigDeleteAfter(proc_result) {
        var result = proc_result.result;
        var message = proc_result.message;
        
        if (result == true) {
            alert('삭제되었습니다.');
            location.href = '/csl/settings/board/list';
        } else {
            alert(message);
        }
    }
</script>