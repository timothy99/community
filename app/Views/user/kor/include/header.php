<!DOCTYPE html>
<html lang="ko">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title><?=$html_meta["meta"]["title"] ?></title>
        <meta name="title" content="<?=$html_meta["meta"]["title"] ?>">
        <meta name="keywords" content="<?=$html_meta["meta"]["keywords"] ?>">
        <meta name="description" content="<?=$html_meta["meta"]["description"] ?>">
        <meta property="og:type" content="<?=$html_meta["og"]["type"] ?>"> 
        <meta property="og:title" content="<?=$html_meta["og"]["title"] ?>">
        <meta property="og:description" content="<?=$html_meta["og"]["description"] ?>">
        <meta property="og:image" content="<?=$html_meta["og"]["image"] ?>">
        <meta property="og:url" content="<?=$html_meta["og"]["url"] ?>">

        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700&display=swap" rel="stylesheet"><!-- Noto Sans KR -->

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/inputmask@5.0.9/dist/jquery.inputmask.min.js"></script>

        <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet"><!-- 썸머노트 CSS -->
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script><!-- 썸머노트 라이트 -->
        <script src="/resource/user/js/summernote-ko-KR.js?ver=<?=env("app.program.version") ?>"></script><!-- 썸머노트 한국어 -->
        <script src="/resource/user/js/summernote_setting.js?ver=<?=env("app.program.version") ?>"></script><!-- 썸머노트 설정 -->

        <script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
        <script src="/resource/user/js/postcode.js?ver=<?=env("app.program.version") ?>"></script>

        <script src="/resource/user/js/user.js?ver=<?=env("app.program.version") ?>"></script>
        <link rel="stylesheet" href="/resource/user/css/user.css?ver=<?=env("app.program.version") ?>">
    </head>
