var summernote_settings = {
    height: 300, // 높이
    focus: true, // 로딩후 포커스 이동
    lang: "ko-KR", // 언어파일
    codeviewFilter: false, // XSS 필터
    codeviewIframeFilter: true, // iframe필터
    callbacks : { 
        onImageUpload : function(files) { // 파일 업로드(다중업로드를 위해 반복문 사용)
            for (var i = files.length-1; i >= 0; i--) {
                uploadSummernoteFile(files[i]);
            }
        }
    },
};

// 썸머노트 파일 첨부 로직
function uploadSummernoteFile(file) {
    formData = new FormData();
    formData.append("attach", file);
    formData.append("file_id", "attach");
    $.ajax({
        data : formData,
        type : "POST",
        url : "/upload/general",
        dataType: "json",
        processData : false,
        contentType : false,
        success : function(proc_result) {
            var info = proc_result.info;
            var result = info.result;
            var message = info.message;
            if (result == false) {
                alert(message);
            } else {
                var category = info.category;
                var file_id = info.file_id;
                var file_name_org = info.file_name_org;
                if (category == "image") {
                    var file_html = "<img src='/download/view/"+file_id+"' class='img-fluid'>";
                } else {
                    var file_html = "<a href='/download/download/"+file_id+"'>"+file_name_org+"</a>";
                }
                $("#contents").summernote("pasteHTML", file_html);
            }
        }
    });
}

// base64decode
function decodeUnicode(str) {
    // Going backwards: from bytestream, to percent-encoding, to original string.
    return decodeURIComponent(atob(str).split("").map(function (c) {
        return "%" + ("00" + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(""));
}
