<form id="frm" name="frm" onsubmit="return false;">

<input type="hidden" id="member_idx" name="member_idx" value="<?= $info->member_idx ?>">
<input type="hidden" id="member_id" name="member_id" value="<?= $info->member_id ?>">
<input type="hidden" id="member_memo_idx" name="member_memo_idx" value="0">

<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
        <h3>회원관리</h3>

        <div class="card mb-4">
            <div class="card-header bg-success bg-opacity-75 text-white">기본정보</div>
            <div class="card-body p-3">
                <div class="row g-0 border-bottom border-top">
                    <div class="tbl-label">이름</div>
                    <div class="tbl-value"><?= $info->member_name ?></div>
                </div>
                <div class="row g-0 border-bottom">
                    <div class="tbl-label">아이디</div>
                    <div class="tbl-value"><?= $info->member_id ?></div>
                </div>
                <div class="row g-0 border-bottom">
                    <div class="tbl-label">암호</div>
                    <div class="tbl-value">
                        <a href="/csl/member/password/<?= $info->member_id ?>" class="btn btn-sm btn-warning">암호 변경</a>
                    </div>
                </div>
                <div class="row g-0 border-bottom">
                    <div class="tbl-label">별명</div>
                    <div class="tbl-value"><?= $info->member_nickname ?></div>
                </div>
                <div class="row g-0 border-bottom">
                    <div class="tbl-label">이메일 수신여부</div>
                    <div class="tbl-value"><?= $info->email_yn_txt ?></div>
                </div>
                <div class="row g-0 border-bottom">
                    <div class="tbl-label">SMS 수신여부</div>
                    <div class="tbl-value"><?= $info->sms_yn_txt ?></div>
                </div>
                <div class="row g-0 border-bottom">
                    <div class="tbl-label">이메일</div>
                    <div class="tbl-value"><?= $info->email ?></div>
                </div>
                <div class="row g-0 border-bottom">
                    <div class="tbl-label">전화</div>
                    <div class="tbl-value"><?= $info->phone ?></div>
                </div>
                <div class="row g-0 border-bottom">
                    <div class="tbl-label">주소</div>
                    <div class="tbl-value">[<?= $info->post_code ?>] <?= $info->addr1 ?> <?= $info->addr2 ?></div>
                </div>
                <div class="row g-0 border-bottom">
                    <div class="tbl-label">권한 그룹</div>
                    <div class="tbl-value"><?= $info->auth_group ?></div>
                </div>
                <div class="row g-0 border-bottom">
                    <div class="tbl-label">마지막 로그인</div>
                    <div class="tbl-value"><?= $info->last_login_date_txt ?></div>
                </div>
                <div class="row g-0 border-bottom">
                    <div class="tbl-label">마지막 IP</div>
                    <div class="tbl-value"><?= $info->last_login_ip ?></div>
                </div>
                <div class="row g-0 border-bottom">
                    <div class="tbl-label">포인트</div>
                    <div class="tbl-value"><?= $info->member_point_txt ?></div>
                </div>
                <div class="row g-0 border-bottom">
                    <div class="tbl-label">등록일</div>
                    <div class="tbl-value"><?= $info->ins_date_txt ?></div>
                </div>
                <div class="row g-0 border-bottom">
                    <div class="tbl-label">수정일</div>
                    <div class="tbl-value"><?= $info->upd_date_txt ?></div>
                </div>
            </div>
            <div class="card-footer text-end">
                <div class="d-flex gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger" onclick="memberDelete()">삭제</button>
                    <a href="/csl/member/list" class="btn btn-secondary">목록</a>
                    <a href="/csl/member/edit/<?= $info->member_id ?>" class="btn btn-primary">수정</a>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-info bg-opacity-75 text-white">회원메모</div>
            <div class="card-body p-3">
                <table class="table table-bordered table-hover">
                    <thead class="table-secondary">
                        <tr class="text-center">
                            <th style="width:70px;">연번</th>
                            <th style="width:170px;">시간</th>
                            <th>내용</th>
                            <th style="width:120px;">관리</th>
                        </tr>
                    </thead>
                    <tbody>
<?php   foreach ($list as $no => $val) { ?>
                        <tr>
                            <td class="text-center"><?=$val->list_no ?></td>
                            <td class="text-center"><?=$val->upd_date_txt ?></td>
                            <td id="memo-cell-<?=$val->member_memo_idx ?>">
                                <span class="memo-text"><?=$val->memo ?></span>
                                <input type="text" class="form-control form-control-sm memo-edit-input d-none" value="<?= esc($val->memo, 'attr') ?>">
                            </td>
                            <td class="text-center">
                                <div class="memo-view-btns">
                                    <button type="button" class="btn btn-sm btn-success" onclick="memoView('<?= $val->member_memo_idx ?>', this)" data-memo="<?= esc($val->memo, 'attr') ?>">수정</button>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="memoDelete('<?= $val->member_memo_idx ?>')">삭제</button>
                                </div>
                                <div class="memo-edit-btns d-none">
                                    <button type="button" class="btn btn-sm btn-primary" onclick="memoSave('<?= $val->member_memo_idx ?>')">저장</button>
                                    <button type="button" class="btn btn-sm btn-secondary" onclick="memoCancel('<?= $val->member_memo_idx ?>')">취소</button>
                                </div>
                            </td>
                        </tr>
<?php   } ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer" id="memo-new-footer">
                <div class="d-flex gap-2">
                    <input type="text" class="form-control form-control-sm" id="memo" name="memo" placeholder="신규 메모를 입력하세요">
                    <button type="button" class="btn btn-primary text-nowrap flex-shrink-0" onclick="memoInsert()">입력</button>
                </div>
            </div>
        </div>
    </div>
</main>

</form>

<script>
    // 메뉴강조
    $(window).on('load', function() {
        $('#li-member').addClass('active-level-1');
    });

    function memberDelete() {
        if (confirm('정말 삭제하시겠습니까?')) {
            ajax1('/csl/member/delete', 'frm', 'memberDeleteAfter');
        }
    }

    function memberDeleteAfter(proc_result) {
        var result = proc_result.result;
        var message = proc_result.message;
        if (result == true) {
            location.href = '/csl/member/list';
        } else {
            alert(message);
        }
    }

    function memoView(member_memo_idx, btn) {
        $('#member_memo_idx').val(member_memo_idx);
        $('#memo-cell-' + member_memo_idx + ' .memo-text').addClass('d-none');
        $('#memo-cell-' + member_memo_idx + ' .memo-edit-input').val($(btn).data('memo')).removeClass('d-none').focus();
        $(btn).closest('td').find('.memo-view-btns').addClass('d-none');
        $(btn).closest('td').find('.memo-edit-btns').removeClass('d-none');
        $('#memo-new-footer').addClass('d-none');
    }

    function memoSave(member_memo_idx) {
        var memo = $('#memo-cell-' + member_memo_idx + ' .memo-edit-input').val();
        if (memo.trim() === '') {
            alert('메모를 입력하세요.');
            return;
        }
        $('#memo').val(memo);
        ajax1('/csl/member/memo/update', 'frm', 'memoSaveAfter');
    }

    function memoSaveAfter(proc_result) {
        var result = proc_result.result;
        var message = proc_result.message;
        if (result == true) {
            location.reload();
        } else {
            alert(message);
        }
    }

    function memoCancel(member_memo_idx) {
        $('#member_memo_idx').val(0);
        $('#memo-cell-' + member_memo_idx + ' .memo-text').removeClass('d-none');
        $('#memo-cell-' + member_memo_idx + ' .memo-edit-input').addClass('d-none');
        var $row = $('#memo-cell-' + member_memo_idx).closest('tr');
        $row.find('.memo-view-btns').removeClass('d-none');
        $row.find('.memo-edit-btns').addClass('d-none');
        $('#memo-new-footer').removeClass('d-none');
    }

    function memoInsert() {
        var memo = $('#memo').val();
        if (memo.trim() === '') {
            alert('메모를 입력하세요.');
            return;
        }
        ajax1('/csl/member/memo/insert', 'frm', 'memoInsertAfter');
    }

    function memoInsertAfter(proc_result) {
        var result = proc_result.result;
        var message = proc_result.message;
        if (result == true) {
            location.reload();
        } else {
            alert(message);
        }
    }

    function memoDelete(member_memo_idx) {
        if (confirm('정말 삭제하시겠습니까?')) {
            $('#member_memo_idx').val(member_memo_idx);
            ajax1('/csl/member/memo/delete', 'frm', 'memoDeleteAfter');
        }
    }

    function memoDeleteAfter(proc_result) {
        var result = proc_result.result;
        var message = proc_result.message;
        if (result == true) {
            location.reload();
        } else {
            alert(message);
        }
    }
</script>
