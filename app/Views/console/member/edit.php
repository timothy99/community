<form id="frm" name="frm">

<input type="hidden" id="member_id" name="member_id" value="<?= $info->member_id ?>">

<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
        <h3>슬라이드</h3>

        <div class="card mb-4">
            <div class="card-header bg-success bg-opacity-75 text-white">기본정보</div>
            <div class="card-body">
                <!-- 이름 -->
                <div class="mb-3">
                    <label for="member_name" class="form-label">이름 <span class="text-danger">*</span></label>
                    <input type="text" class="form-control w-25" id="member_name" name="member_name" placeholder="이름을 입력하세요" value="<?= $info->member_name ?>">
                </div>

                <!-- 아이디 -->
                <div class="mb-3">
                    <label for="member_id" class="form-label">아이디 <span class="text-danger">*</span></label>
                    <input type="text" class="form-control bg-light" placeholder="아이디를 입력하세요" value="<?= $info->member_id ?>" readonly>
                </div>

                <!-- 별명 -->
                <div class="mb-3">
                    <label for="member_nickname" class="form-label">별명</label>
                    <input type="text" class="form-control" id="member_nickname" name="member_nickname" placeholder="별명을 입력하세요" value="<?= $info->member_nickname ?>">
                </div>

                <!-- 이메일 수신여부 -->
                <div class="mb-3">
                    <label class="form-label">이메일 수신여부</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="email_yn" id="email_yn_y" value="Y">
                            <label class="form-check-label" for="email_yn_y">수신</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="email_yn" id="email_yn_n" value="N">
                            <label class="form-check-label" for="email_yn_n">미수신</label>
                        </div>
                    </div>
                </div>

                <!-- SMS 수신여부 -->
                <div class="mb-3">
                    <label class="form-label">SMS 수신여부</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sms_yn" id="sms_yn_y" value="Y">
                            <label class="form-check-label" for="sms_yn_y">수신</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sms_yn" id="sms_yn_n" value="N">
                            <label class="form-check-label" for="sms_yn_n">미수신</label>
                        </div>
                    </div>
                </div>

                <!-- 이메일 -->
                <div class="mb-3">
                    <label for="email" class="form-label">이메일</label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="이메일을 입력하세요" value="<?= $info->email ?>">
                </div>

                <!-- 전화 -->
                <div class="mb-3">
                    <label for="phone" class="form-label">전화</label>
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="전화번호를 입력하세요" value="<?= $info->phone ?>">
                </div>

                <!-- 우편번호 -->
                <div class="mb-3">
                    <label for="post_code" class="form-label">우편번호</label>
                    <div class="input-group">
                        <input type="text" class="form-control bg-light" id="post_code" name="post_code" value="<?= $info->post_code ?>" placeholder="우편번호" maxlength="5" readonly>
                        <button class="btn btn-secondary" type="button" onclick="postcodeShow('postcode_div', 'postcode_wrap')">우편번호 찾기</button>
                    </div>
                </div>

                <!-- 우편번호 -->
                <div class="mb-3" style="display:none" id="postcode_div">
                    <div id="postcode_wrap" style="display:block;border:1px solid;width:100%;height:300px;margin:5px 0;position:relative">
                        <img src="//t1.daumcdn.net/postcode/resource/images/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" onclick="postcodeClose('postcode_div')" alt="접기 버튼">
                    </div>
                </div>

                <!-- 주소1 -->
                <div class="mb-3">
                    <label for="addr1" class="form-label">주소</label>
                    <input type="text" class="form-control bg-light" id="addr1" name="addr1" value="<?= $info->addr1 ?>" placeholder="주소" readonly>
                </div>

                <!-- 주소2 -->
                <div class="mb-3">
                    <label for="addr2" class="form-label">상세주소</label>
                    <input type="text" class="form-control" id="addr2" name="addr2" placeholder="상세주소를 입력하세요">
                </div>

                <!-- 권한그룹 셀렉트박스 -->
                <div class="mb-3">
                    <label for="auth_group" class="form-label">권한 그룹</label>
                    <select class="form-select" id="auth_group" name="auth_group">
                        <option value="">권한 그룹을 선택하세요</option>
                        <option value="일반">일반</option>
                        <option value="관리자">관리자</option>
                        <option value="최고관리자">최고관리자</option>
                    </select>
                </div>


            </div>
            <div class="card-footer text-end">
                <div class="d-flex gap-2 justify-content-end">
                    <a href="/csl/member/view/<?= $info->member_id ?>" class="btn btn-secondary">취소</a>
                    <button type="button" class="btn btn-primary" onclick="memberUpdate()">저장</button>
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
        $('#li-member').addClass('active-level-1');

        $('#email').inputmask({ alias: 'email'});
        $('#phone').inputmask('9{1,3}-9{1,4}-9{1,4}');

        $('input[name="sms_yn"][value="<?=$info->sms_yn ?>"]').prop('checked', true);
        $('input[name="email_yn"][value="<?=$info->email_yn ?>"]').prop('checked', true);
        $('select[name="auth_group"]').val('<?=$info->auth_group ?>');
    });

    // 우편번호 검색된 결과값으로 페이지에 맞는 데이터 넣기
    function postcodeAfter(data) {
        // 우편번호와 주소 정보를 해당 필드에 넣는다.
        document.getElementById('post_code').value = data.zonecode;
        document.getElementById('addr1').value = data.addr1;
        document.getElementById('addr2').value = data.addr2;
        // 커서를 상세주소 필드로 이동한다.
        document.getElementById('addr2').focus();
    }

    function memberUpdate() {
        ajax1('/csl/member/update', 'frm', 'memberUpdateAfter');
    }

    function memberUpdateAfter(proc_result) {
        var result = proc_result.result;
        var message = proc_result.message;
        var return_url = proc_result.return_url;
        if (result == true) {
            location.href = return_url;
        } else {
            alert(message);
        }
    }

    function uploadAfter(proc_result) {
        var result = proc_result.result;
        var message = proc_result.message;
        var info = proc_result.info;
        if (result == true) {
            $('#slide_file_hidden').val(info.file_id);
            var html = '<div class="row g-2 align-items-center">';
            html += '<div class="col"><img src="/csl/file/view/' + info.file_id + '" class="img-thumbnail" style="width: 300px; height: auto;"></div>';
            html += '<div class="col"><small class="text-muted">원본파일명</small><br>' + info.file_name_org + '</div>';
            html += '<div class="col"><small class="text-muted">가로해상도</small><br>' + info.image_width_txt + 'px</div>';
            html += '<div class="col"><small class="text-muted">세로해상도</small><br>' + info.image_height_txt + 'px</div>';
            html += '<div class="col"><small class="text-muted">사이즈</small><br>' + info.file_size_kb + 'KB</div>';
            html += '</div>';
            $('#slide_file_view').html(html);
        } else {
            alert(message);
        }
    }
</script>