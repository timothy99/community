// 입력받은 form 데이터로 처리
function ajax1(ajax_url, form_id) {
    var progress_html = $("<div id='progress' style='display:none;'><div id='progress_loading'><img id='loading_img' src='/resource/community/image/loading.gif'/></div></div>").appendTo(document.body).show();
    $.ajax({
        url: ajax_url,
        type: "POST",
        dataType: "json",
        async: false,
        data: $("#"+form_id).serialize(),
        success: function(proc_result) {
            var result = proc_result.result;
            var message = proc_result.message;
            var return_url = proc_result.return_url;
            if(result == true) {
                location.href = return_url;
            } else {
                alert(message);
            }
            $("#progress").remove();
        }
    });
}


// 파일첨부 로직
function upload(file_id, method) {
    var progress_html = $("<div id='progress' style='display:none;'><div id='progress_loading'><img id='loading_img' src='/resource/community/image/loading.gif'/></div></div>").appendTo(document.body).show();
    var form_data = new FormData($("#frm")[0]);
    form_data.append("file_id", file_id);
    $.ajax({
        data : form_data,
        type : "POST",
        url : "/upload/"+method,
        dataType: "json",
        processData : false,
        contentType : false,
        success : function(proc_result) {
            if (proc_result.result == false) {
                alert(proc_result.message);
            } else {
                upload_after(proc_result);
            }
            $("#progress").remove();
        }
    });
}

// 첨부파일 삭제(화면에서만)
function file_delete(file_id) {
    $("#"+file_id).remove();
    $("input[value='"+file_id+"']").val("");
    $("#visible_"+file_id).val("");
    delete_after(file_id);
}

// 캐러셀과 드롭다운 초기화
document.addEventListener('DOMContentLoaded', function() {
    // 캐러셀 애니메이션 설정
    var carousel = document.querySelector('#heroCarousel');
    if (carousel) {
        var bsCarousel = new bootstrap.Carousel(carousel, {
            interval: 3000,
            ride: 'carousel'
        });
    }
    
    // 3단계 드롭다운 클릭 이벤트 처리 (모바일용)
    document.querySelectorAll('.dropdown-submenu > .dropdown-toggle').forEach(function(element) {
        element.addEventListener('click', function(e) {
            // 데스크톱에서는 호버로 처리하므로 클릭 이벤트 무시
            if (window.innerWidth >= 768) {
                return;
            }
            
            e.preventDefault();
            e.stopPropagation();
            
            // 다른 서브메뉴들 모두 닫기
            document.querySelectorAll('.dropdown-submenu .dropdown-menu').forEach(function(menu) {
                if (menu !== element.nextElementSibling) {
                    menu.style.display = 'none';
                }
            });
            
            // 현재 서브메뉴 토글
            var submenu = this.nextElementSibling;
            if (submenu) {
                submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
            }
        });
    });
    
    // 드롭다운 외부 클릭시 모든 서브메뉴 닫기
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-submenu .dropdown-menu').forEach(function(menu) {
                menu.style.display = 'none';
            });
        }
    });
});


