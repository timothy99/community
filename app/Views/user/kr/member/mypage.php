<?php
/**
 * 마이페이지
 * 회원 정보 조회/수정, SNS 연결 관리
 * 
 * @var object $info 회원 정보 (member 테이블)
 * @var array $sns_map 회원의 SNS 연결 정보 (sns_type을 key로 한 map)
 *     - 예시: [ 'kakao' => { sns_idx, member_idx, sns_type, sns_id, ins_date }, 'naver' => ... ]
 */
?>

<form id="frm" name="frm">

<input type="hidden" id="member_idx" name="member_idx" value="<?= $info->member_idx ?>">

<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow">
                <div class="card-body p-4">
                    <h3 class="card-title text-center mb-4">마이페이지</h3>

                    <!-- 아이디 (읽기전용) -->
                    <div class="mb-3">
                        <label for="member_id" class="form-label">아이디</label>
                        <input type="text" class="form-control bg-light" id="member_id" name="member_id" value="<?= $info->member_id ?>" readonly>
                    </div>

                    <!-- 암호 변경 버튼 -->
                    <div class="mb-3">
                        <label class="form-label">암호</label>
                        <div>
                            <a href="/member/password/change" class="btn btn-warning">암호 변경</a>
                        </div>
                    </div>

                    <!-- 이름 입력 -->
                    <div class="mb-3">
                        <label for="member_name" class="form-label">이름 <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="member_name" name="member_name" placeholder="이름을 입력하세요" value="<?= $info->member_name ?>" required>
                    </div>

                    <!-- 별명 입력 -->
                    <div class="mb-3">
                        <label for="member_nickname" class="form-label">별명 <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="member_nickname" name="member_nickname" placeholder="별명을 입력하세요" value="<?= $info->member_nickname ?>" required>
                    </div>

                    <!-- 이메일 입력 -->
                    <div class="mb-3">
                        <label for="email" class="form-label">이메일</label>
                        <input type="text" class="form-control" id="email" name="email" placeholder="이메일을 입력하세요" value="<?= $info->email ?>">
                    </div>

                    <!-- 수신 동의 -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="email_yn" name="email_yn" value="Y" <?= $info->email_yn == 'Y' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="email_yn">
                                뉴스레터 수신 동의
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="sms_yn" name="sms_yn" value="Y" <?= $info->sms_yn == 'Y' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="sms_yn">
                                SMS 수신 동의
                            </label>
                        </div>
                    </div>

                    <!-- 휴대전화 번호 -->
                    <div class="mb-3">
                        <label for="phone" class="form-label">휴대전화</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="형식에 맞게 입력해주세요 (예: 010-1234-5678)" maxlength="13" value="<?= $info->phone ?>">
                    </div>

                    <!-- 우편번호 -->
                    <div class="mb-3">
                        <label for="post_code" class="form-label">우편번호</label>
                        <div class="input-group">
                            <input type="text" class="form-control bg-light" id="post_code" name="post_code" placeholder="우편번호" maxlength="5" value="<?= $info->post_code ?>" readonly>
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
                        <input type="text" class="form-control bg-light" id="addr1" name="addr1" placeholder="주소" value="<?= $info->addr1 ?>" readonly>
                    </div>

                    <!-- 주소2 -->
                    <div class="mb-3">
                        <label for="addr2" class="form-label">상세주소</label>
                        <input type="text" class="form-control" id="addr2" name="addr2" placeholder="상세주소를 입력하세요" value="<?= $info->addr2 ?>">
                    </div>

                    <!-- 구분선 -->
                    <hr class="my-4">

                    <!-- SNS 연결 -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">SNS 연결</label>
                        <p class="text-muted mb-3" style="font-size:0.85rem;">연결하면 해당 SNS 버튼으로 간편하게 로그인할 수 있습니다. 여러 계정을 동시에 연결할 수 있습니다.</p>

                        <div class="d-flex flex-wrap gap-3">

                            <!-- 카카오 -->
                            <div class="d-flex flex-column align-items-center" style="min-width:80px;">
                                <div class="sns-connect-icon kakao mb-1">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 3C6.48 3 2 6.48 2 10.77c0 2.74 1.66 5.15 4.19 6.57l-.97 3.6c-.08.31.24.56.51.39L9.9 18.9A11.5 11.5 0 0 0 12 19.1c5.52 0 10-3.48 10-7.33C22 6.48 17.52 3 12 3z"/>
                                    </svg>
                                </div>
                                <small class="text-muted mb-1">카카오</small>
                                <?php if (isset($sns_map['kakao'])): ?>
                                    <span class="badge bg-success mb-1" style="font-size:0.7rem;">연결됨</span>
                                    <button class="btn btn-sm btn-outline-danger" onclick="snsDisconnect('kakao')">해제</button>
                                <?php else: ?>
                                    <span class="badge bg-secondary mb-1" style="font-size:0.7rem;">미연결</span>
                                    <a href="/member/sns/kakao/connect" class="btn btn-sm btn-outline-warning">연결</a>
                                <?php endif; ?>
                            </div>

                            <!-- 네이버 -->
                            <div class="d-flex flex-column align-items-center" style="min-width:80px;">
                                <div class="sns-connect-icon naver mb-1">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M16.273 12.845L7.376 3H3v18h4.727v-9.845L16.624 21H21V3h-4.727z"/>
                                    </svg>
                                </div>
                                <small class="text-muted mb-1">네이버</small>
                                <?php if (isset($sns_map['naver'])): ?>
                                    <span class="badge bg-success mb-1" style="font-size:0.7rem;">연결됨</span>
                                    <button class="btn btn-sm btn-outline-danger" onclick="snsDisconnect('naver')">해제</button>
                                <?php else: ?>
                                    <span class="badge bg-secondary mb-1" style="font-size:0.7rem;">미연결</span>
                                    <a href="/member/sns/naver/connect" class="btn btn-sm btn-outline-success">연결</a>
                                <?php endif; ?>
                            </div>

                            <!-- 구글 -->
                            <div class="d-flex flex-column align-items-center" style="min-width:80px;">
                                <div class="sns-connect-icon google mb-1">
                                    <svg width="28" height="28" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                                    </svg>
                                </div>
                                <small class="text-muted mb-1">구글</small>
                                <?php if (isset($sns_map['google'])): ?>
                                    <span class="badge bg-success mb-1" style="font-size:0.7rem;">연결됨</span>
                                    <button class="btn btn-sm btn-outline-danger" onclick="snsDisconnect('google')">해제</button>
                                <?php else: ?>
                                    <span class="badge bg-secondary mb-1" style="font-size:0.7rem;">미연결</span>
                                    <a href="/member/sns/google/connect" class="btn btn-sm btn-outline-secondary">연결</a>
                                <?php endif; ?>
                            </div>

                            <!-- 애플 (준비 중) -->
                            <div class="d-flex flex-column align-items-center" style="min-width:80px; opacity:0.45;">
                                <div class="sns-connect-icon apple mb-1">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98l-.09.06c-.22.15-2.18 1.27-2.16 3.8.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.37 2.68zM13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                                    </svg>
                                </div>
                                <small class="text-muted mb-1">애플</small>
                                <span class="badge bg-secondary mb-1" style="font-size:0.7rem;">준비 중</span>
                                <button class="btn btn-sm btn-outline-secondary" disabled>연결</button>
                            </div>

                        </div>
                    </div>

                    <!-- 저장 버튼 -->
                    <div class="d-grid mb-3">
                        <button type="button" class="btn btn-primary btn-lg" onclick="mypageUpdate()">저장</button>
                    </div>

                    <!-- 취소 -->
                    <div class="text-center">
                        <a href="/" class="text-decoration-none">취소</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</form>

<script>
    $(window).on('load', function() {
        $('#email').inputmask({ alias: 'email'});
        $('#phone').inputmask('9{1,3}-9{1,4}-9{1,4}');

        $('input[name="sms_yn"][value="<?=$info->sms_yn ?>"]').prop('checked', true);
        $('input[name="email_yn"][value="<?=$info->email_yn ?>"]').prop('checked', true);
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

    function mypageUpdate() {
        var member_name = $('#member_name').val();
        var member_nickname = $('#member_nickname').val();
        
        if (member_name == '') {
            alert('이름을 입력해주세요.');
            $('#member_name').focus();
            return false;
        }
        
        if (member_nickname == '') {
            alert('별명을 입력해주세요.');
            $('#member_nickname').focus();
            return false;
        }
        
        ajax1('/member/mypage/update', 'frm', 'mypageUpdateAfter');
    }

    function mypageUpdateAfter(proc_result) {
        var result = proc_result.result;
        var message = proc_result.message;
        var return_url = proc_result.return_url;
        if (result == true) {
            alert(message);
            location.href = return_url;
        } else {
            alert(message);
        }
    }

    function snsDisconnect(sns_type) {
        var name_map = { kakao: '카카오', naver: '네이버', google: '구글' };
        var name = name_map[sns_type] || sns_type;
        if (!confirm(name + ' 연결을 해제하시겠습니까?')) {
            return false;
        }
        $.ajax({
            url: '/member/sns/disconnect',
            type: 'POST',
            data: { sns_type: sns_type },
            dataType: 'json',
            success: function(proc_result) {
                alert(proc_result.message);
                if (proc_result.result == true) {
                    location.reload();
                }
            },
            error: function() {
                alert('처리 중 오류가 발생했습니다.');
            }
        });
    }
</script>
