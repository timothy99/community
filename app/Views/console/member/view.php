<form id="frm" name="frm">

<input type="hidden" id="member_idx" name="member_idx" value="<?= $info->member_idx ?>">
<input type="hidden" id="member_id" name="member_id" value="<?= $info->member_id ?>">

<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
        <h3>회원</h3>

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
                                <th class="align-middle bg-light">이름</th>
                                <td><?= $info->member_name ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">아이디</th>
                                <td><?= $info->member_id ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">별명</th>
                                <td><?= $info->member_nickname ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">이메일 수신여부</th>
                                <td><?= $info->email_yn_txt ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">SMS 수신여부</th>
                                <td><?= $info->sms_yn_txt ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">이메일</th>
                                <td><?= $info->email ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">전화</th>
                                <td><?= $info->phone ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">주소</th>
                                <td>[<?= $info->post_code ?>] <?= $info->addr1 ?> <?= $info->addr2 ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">권한 그룹</th>
                                <td><?= $info->auth_group ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">마지막 로그인</th>
                                <td><?= $info->last_login_date_txt ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">마지막 IP</th>
                                <td><?= $info->last_login_ip ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">포인트</th>
                                <td><?= $info->member_point_txt ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">등록일</th>
                                <td><?= $info->ins_date_txt ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">수정일</th>
                                <td><?= $info->upd_date_txt ?></td>
                            </tr>
                        </tbody>
                    </table>
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
    </div>
</main>

</form>

<script>
    // 메뉴강조
    $(window).on('load', function() {
        // $('#li-member').addClass('active-level-1').attr({'data-bs-toggle': 'collapse', 'aria-expanded': 'true'});
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
</script>
