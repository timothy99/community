<form id="frm" name="frm">

<!-- Main Content -->
<main id="main-content">
    <div class="container-fluid py-4">
        <h3>환경설정</h3>

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
                                <th class="align-middle bg-light">회사명</th>
                                <td><?= $info->title ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">회사로고</th>
                                <td>
<?php   if ($info->company_logo != null) { ?>
                                    <div class="mb-3">
                                        <img src="/csl/file/view/<?= $info->company_logo ?>" class="img-thumbnail img-fluid" style="max-width: 100%; height: auto;">
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-auto">
                                            <small class="text-muted d-block">원본파일명</small>
                                            <?= $info->company_logo_info->file_name_org ?>
                                        </div>
                                        <div class="col-auto">
                                            <small class="text-muted d-block">해상도</small>
                                            <?= $info->company_logo_info->image_width_txt ?> × <?= $info->company_logo_info->image_height_txt ?> px
                                        </div>
                                        <div class="col-auto">
                                            <small class="text-muted d-block">파일 크기</small>
                                            <?= $info->company_logo_info->file_size_kb ?> KB
                                        </div>
                                    </div>
<?php   } else { ?>
                                    <p class="text-muted mb-0">등록된 이미지가 없습니다.</p>
<?php   } ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">프로그램 버전</th>
                                <td><?= $info->program_ver ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">전화번호</th>
                                <td><?= $info->phone ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">팩스번호</th>
                                <td><?= $info->fax ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">이메일</th>
                                <td><?= $info->email ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">업무시간</th>
                                <td><?= $info->work_hour ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">우편번호</th>
                                <td><?= $info->post_code ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">주소1</th>
                                <td><?= $info->addr1 ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">주소2</th>
                                <td><?= $info->addr2 ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">사업자등록번호</th>
                                <td><?= $info->biz_no ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">메일발송기능 사용여부</th>
                                <td><?= $info->smtp_yn ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">SMTP 호스트</th>
                                <td><?= $info->smtp_host ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">SMTP 이메일 주소</th>
                                <td><?= $info->smtp_mail ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">SMTP 사용자아이디</th>
                                <td><?= $info->smtp_user ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">SMTP 비밀번호</th>
                                <td><?= $info->smtp_pass ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">SMTP 포트</th>
                                <td><?= $info->smtp_port ?></td>
                            </tr>
                            <tr>
                                <th class="align-middle bg-light">SMTP 발송자명</th>
                                <td><?= $info->smtp_name ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-end">
                <div class="d-flex gap-2 justify-content-end">
                    <a href="/csl/config/edit" class="btn btn-primary">수정</a>
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
</script>