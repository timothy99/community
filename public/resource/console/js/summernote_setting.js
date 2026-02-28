var summernote_settings = {
    height: 300, // 높이
    focus: false, // 로딩후 포커스 이동 (false: 자동 스크롤 방지)
    lang: 'ko-KR', // 언어파일
    codeviewFilter: false, // XSS 필터
    codeviewIframeFilter: true, // iframe필터
    fontNames: ['Noto Sans','Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Helvetica', 'Impact', 'Tahoma', 'Times New Roman', 'Verdana'], // 글꼴 목록
    fontNamesIgnoreCheck: ['Noto Sans'], // 글꼴 존재 여부 체크 무시
    styleTags: ['p', 'blockquote', 'pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
    callbacks : {
        onInit: function() {
            // 에디터 영역에 기본 폰트 스타일 적용
            $(this).next('.note-editor').find('.note-editable').css({
                'font-family': 'Noto Sans, sans-serif'
            });
        },
        onImageUpload : function(files) { // 파일 업로드(다중업로드를 위해 반복문 사용)
            for (var i = files.length-1; i >= 0; i--) {
                uploadSummernoteFile(files[i]);
            }
        }
    },
};

// 썸머노트 초기화 헬퍼 함수
function initSummernote(selector, options) {
    var settings = $.extend({}, summernote_settings, options || {});
    
    $(selector).summernote(settings);
    
    // 기존 내용이 있다면 로드 (data-encoded 속성 또는 hidden field 사용)
    var encodedContent = $(selector).data('encoded');
    if (!encodedContent) {
        var hiddenField = $(selector).attr('id') + '_code';
        encodedContent = $('#' + hiddenField).val();
    }
    
    if (encodedContent) {
        $(selector).summernote('code', decodeUnicode(encodedContent));
    }
    
    // 에디터 영역에 기본 폰트 적용
    $(selector).next('.note-editor').find('.note-editable').css('font-family', 'Noto Sans, sans-serif');
    
    return $(selector);
}

// 썸머노트 파일 첨부 로직
function uploadSummernoteFile(file) {
    formData = new FormData();
    formData.append('attach', file);
    formData.append('file_id', 'attach');
    $.ajax({
        data : formData,
        type : 'POST',
        url : '/file/upload/general',
        dataType: 'json',
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
                if (category == 'image') {
                    var file_html = '<img src="/file/view/'+file_id+'" class="img-fluid">';
                } else {
                    var file_html = '<a href="/file/download/'+file_id+'">'+file_name_org+'</a>';
                }
                $('#contents').summernote('pasteHTML', file_html);
            }
        }
    });
}

// base64decode
function decodeUnicode(str) {
    // Going backwards: from bytestream, to percent-encoding, to original string.
    return decodeURIComponent(atob(str).split('').map(function (c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));
}
