// 입력받은 form 데이터로 처리
function ajax1(ajax_url, form_data, callback) {
    $('<div id="progress" style="display:none;"><div id="progress_loading"><img id="loading_img" src="/resource/console/image/loading.gif"/></div></div>').appendTo(document.body).show();

    // form_data 처리 로직
    var ajaxData;
    var processData = true;
    var contentType = "application/x-www-form-urlencoded; charset=UTF-8";

    if (typeof form_data === "string") {
        // 문자열인 경우 jQuery 선택자로 시도해보기
        var $formElement = $('#' + form_data);
        if ($formElement.length > 0 && $formElement.is("form")) {
            // jQuery 폼 객체인 경우 serialize 처리
            ajaxData = $formElement.serialize();
        } else {
            // 일반 문자열 데이터인 경우
            ajaxData = form_data;
        }
    } else if (form_data instanceof FormData) {
        // FormData 객체인 경우
        ajaxData = form_data;
        processData = false;
        contentType = false;
    } else if (form_data instanceof jQuery) {
        // jQuery 객체인 경우 serialize 처리
        ajaxData = form_data.serialize();
    } else {
        // 일반 객체인 경우
        ajaxData = form_data;
    }

    $.ajax({
        url: ajax_url,
        type: 'POST',
        dataType: 'json',
        data: ajaxData,
        processData: processData,
        contentType: contentType,
        success: function(proc_result) {
            // callback이 존재하는 경우에만 실행
            if(callback) {
                if(typeof callback === 'string') {
                    if(typeof window[callback] === 'function') {
                        window[callback](proc_result);
                    } else {
                        console.error('함수 ' + callback + '이 존재하지 않습니다.');
                    }
                } else if(typeof callback === 'function') {
                    callback(proc_result);
                }
            }
            $('#progress').remove();
        }
    });
}


// 파일첨부 로직
function uploadFile(file_id, method, callback) {
    $('<div id="progress" style="display:none;"><div id="progress_loading"><img id="loading_img" src="/resource/console/image/loading.gif"/></div></div>').appendTo(document.body).show();
    var form_data = new FormData($('#frm')[0]);
    form_data.append('file_id', file_id);
    $.ajax({
        data : form_data,
        type : 'POST',
        url : '/csl/file/upload/'+method,
        dataType: 'json',
        processData : false,
        contentType : false,
        success : function(proc_result) {
            if(callback) {
                if(typeof callback === 'string') {
                    if(typeof window[callback] === 'function') {
                        window[callback](proc_result);
                    } else {
                        console.error('함수 ' + callback + '이 존재하지 않습니다.');
                    }
                } else if(typeof callback === 'function') {
                    callback(proc_result);
                }
            }
            $('#progress').remove();
        }
    });
}

// 첨부파일 삭제(화면에서만)
function fileDelete(file_id) {
    $('#'+file_id).remove();
    $('input[value="'+file_id+'"]').val('');
    $('#visible_'+file_id).val('');
    deleteAfter(file_id);
}
