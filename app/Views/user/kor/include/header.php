<!DOCTYPE html>
<html lang="ko">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?=env("app.sitename") ?></title>

        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/inputmask@5.0.9/dist/jquery.inputmask.min.js"></script>

        <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet"><!-- 썸머노트 CSS -->
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script><!-- 썸머노트 라이트 -->
        <script src="/resource/community/js/summernote-ko-KR.js?ver=<?=env("app.program.version") ?>"></script><!-- 썸머노트 한국어 -->
        <script src="/resource/community/js/summernote_setting.js?ver=<?=env("app.program.version") ?>"></script><!-- 썸머노트 설정 -->

        <script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
        <script src="/resource/community/js/postcode.js?ver=<?=env("app.program.version") ?>"></script>

        <script src="/resource/community/js/community.js?ver=<?=env("app.program.version") ?>"></script>
        <link rel="stylesheet" href="/resource/community/css/community.css?ver=<?=env("app.program.version") ?>">
    </head>
