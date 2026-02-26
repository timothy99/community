<form id="frm" name="frm">

<input type="hidden" id="company_logo_hidden" name="company_logo_hidden" value="<?= $info->company_logo ?>">

<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
        <h3>환경설정</h3>

        <div class="card mb-4">
            <div class="card-header bg-success bg-opacity-75 text-white">기본정보</div>
            <div class="card-body">
                <!-- 회사명 -->
                <div class="mb-3">
                    <label for="title" class="form-label">회사명</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?= $info->title ?>">
                </div>

                <!-- 회사로고 -->
                <div class="mb-3">
                    <label for="company_logo" class="form-label">회사로고</label>
                    <input type="file" class="form-control" id="company_logo" name="company_logo" onchange="uploadFile(this.id, 'image', 'uploadAfter')">
                    <div class="mt-2" id="company_logo_view">
<?php   if ($info->company_logo != null) { ?>
                        <div class="row g-2 align-items-center">
                            <div class="col">
                                <img src="/csl/file/view/<?= $info->company_logo ?>" class="img-thumbnail" style="width: 300px; height: auto;">
                            </div>
                            <div class="col">
                                <small class="text-muted">원본파일명</small><br>
                                <?= $info->company_logo_info->file_name_org ?>
                            </div>
                            <div class="col">
                                <small class="text-muted">가로해상도</small><br>
                                <?= $info->company_logo_info->image_width_txt ?>px
                            </div>
                            <div class="col">
                                <small class="text-muted">세로해상도</small><br>
                                <?= $info->company_logo_info->image_height_txt ?>px
                            </div>
                            <div class="col">
                                <small class="text-muted">사이즈</small><br>
                                <?= $info->company_logo_info->file_size_kb ?> KB
                            </div>
                        </div>
<?php   } else { ?>
                        <p class="text-muted">등록된 이미지가 없습니다.</p>
<?php   } ?>
                    </div>
                </div>

                <!-- 프로그램 버전 -->
                <div class="mb-3">
                    <label for="program_ver" class="form-label">프로그램 버전</label>
                    <input type="text" id="program_ver" class="form-control" value="<?= $info->program_ver ?>">
                </div>

                <!-- 전화번호 -->
                <div class="mb-3">
                    <label for="phone" class="form-label">전화번호</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?= $info->phone ?>">
                </div>

                <!-- 팩스번호 -->
                <div class="mb-3">
                    <label for="fax" class="form-label">팩스번호</label>
                    <input type="text" class="form-control" id="fax" name="fax" value="<?= $info->fax ?>">
                </div>

                <!-- 이메일 -->
                <div class="mb-3">
                    <label for="email" class="form-label">이메일</label>
                    <input type="text" class="form-control" id="email" name="email" value="<?= $info->email ?>">
                </div>

                <!-- 업무시간 -->
                <div class="mb-3">
                    <label for="work_hour" class="form-label">업무시간</label>
                    <input type="text" class="form-control" id="work_hour" name="work_hour" value="<?= $info->work_hour ?>">
                </div>

                <!-- 우편번호 -->
                <div class="mb-3">
                    <label for="post_code" class="form-label">우편번호</label>
                    <div class="input-group">
                        <input type="text" class="form-control bg-gray" id="post_code" name="post_code" placeholder="우편번호" maxlength="5" value="<?= $info->post_code ?>" readonly>
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
                    <label for="addr1" class="form-label">주소1</label>
                    <input type="text" class="form-control bg-gray" id="addr1" name="addr1" value="<?= $info->addr1 ?>">
                </div>

                <!-- 주소2 -->
                <div class="mb-3">
                    <label for="addr2" class="form-label">주소2</label>
                    <input type="text" class="form-control" id="addr2" name="addr2" value="<?= $info->addr2 ?>">
                </div>

                <!-- 사업자등록번호 -->
                <div class="mb-3">
                    <label for="biz_no" class="form-label">사업자등록번호</label>
                    <input type="text" class="form-control" id="biz_no" name="biz_no" value="<?= $info->biz_no ?>">
                </div>

                <!-- 메일발송기능 사용여부 -->
                <div class="mb-3">
                    <label for="smtp_yn" class="form-label">메일발송기능 사용여부</label>
                    <input type="text" class="form-control" id="smtp_yn" name="smtp_yn" value="<?= $info->smtp_yn ?>">
                </div>

                <!-- SMTP 호스트 -->
                <div class="mb-3">
                    <label for="smtp_host" class="form-label">SMTP 호스트</label>
                    <input type="text" class="form-control" id="smtp_host" name="smtp_host" value="<?= $info->smtp_host ?>">
                </div>

                <!-- SMTP 이메일 주소 -->
                <div class="mb-3">
                    <label for="smtp_mail" class="form-label">SMTP 이메일 주소</label>
                    <input type="text" class="form-control" id="smtp_mail" name="smtp_mail" value="<?= $info->smtp_mail ?>">
                </div>

                <!-- SMTP 사용자아이디 -->
                <div class="mb-3">
                    <label for="smtp_user" class="form-label">SMTP 사용자아이디</label>
                    <input type="text" class="form-control" id="smtp_user" name="smtp_user" value="<?= $info->smtp_user ?>">
                </div>

                <!-- SMTP 암호 -->
                <div class="mb-3">
                    <label for="smtp_pass" class="form-label">SMTP 비밀번호</label>
                    <input type="text" class="form-control" id="smtp_pass" name="smtp_pass" value="<?= $info->smtp_pass ?>">
                </div>

                <!-- SMTP 포트 -->
                <div class="mb-3">
                    <label for="smtp_port" class="form-label">SMTP 포트</label>
                    <input type="text" class="form-control" id="smtp_port" name="smtp_port" value="<?= $info->smtp_port ?>">
                </div>

                <!-- SMTP 발송자명 -->
                <div class="mb-3">
                    <label for="smtp_name" class="form-label">SMTP 발송자명</label>
                    <input type="text" class="form-control" id="smtp_name" name="smtp_name" value="<?= $info->smtp_name ?>">
                </div>

            </div>
            <div class="card-footer text-end">
                <div class="d-flex gap-2 justify-content-end">
                    <button type="button" class="btn btn-primary" onclick="configUpdate()">수정</button>
                </div>
            </div>
        </div>

    </div>
</main>

</form>

<script>
    // 메뉴강조
    $(window).on('load', function() {
        $('#li-config').addClass('active-level-1');
    });

    function configUpdate() {
        if (confirm('수정하시겠습니까?')) {
            ajax1('/csl/config/update', 'frm', 'configUpdateAfter');
        }
    }

    function configUpdateAfter(proc_result) {
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
            $('#company_logo_hidden').val(info.file_id);
            var html = '<div class="row g-2 align-items-center">';
            html += '<div class="col"><img src="/csl/file/view/' + info.file_id + '" class="img-thumbnail" style="width: 300px; height: auto;"></div>';
            html += '<div class="col"><small class="text-muted">원본파일명</small><br>' + info.file_name_org + '</div>';
            html += '<div class="col"><small class="text-muted">가로해상도</small><br>' + info.image_width_txt + 'px</div>';
            html += '<div class="col"><small class="text-muted">세로해상도</small><br>' + info.image_height_txt + 'px</div>';
            html += '<div class="col"><small class="text-muted">사이즈</small><br>' + info.file_size_kb + 'KB</div>';
            html += '</div>';
            $('#company_logo_view').html(html);
        } else {
            alert(message);
        }
    }

    // 우편번호 검색된 결과값으로 페이지에 맞는 데이터 넣기
    function postcodeAfter(data) {
        // 우편번호와 주소 정보를 해당 필드에 넣는다.
        document.getElementById('post_code').value = data.zonecode;
        document.getElementById('addr1').value = data.addr1;
        document.getElementById('addr2').value = data.addr2;
        // 커서를 상세주소 필드로 이동한다.
        document.getElementById('addr2').focus();
    }

</script>