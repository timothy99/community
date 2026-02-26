<?php

/**
 * [Description for getPagingArray]
 * 페이징 생성
 *
 * @param array $data
 *
 * @return  array
 *
 * @author  timothy99
 */
function getPagingArray(array $data): array
{
    $cnt = $data['cnt'];
    $page = $data['search_page'];
    $rows = $data['search_rows'];

    $prev_pages = 4; // 현재 페이지($page)기준 앞에 있을 최대 페이지 수
    $next_pages = 4; // 현재 페이지($page)기준 뒤에 있을 최대 페이지 수

    $max_page = ceil($cnt/$rows); // 총 합 기준 가장 마지막 페이지

    $start_page = $page-$prev_pages < 1 ? 1 : $page-$prev_pages; // 현재 페이지에서 앞에 있을 페이지를 빼서 1보다 작으면 1로 고정
    $end_page = $page+$next_pages > $max_page ? $max_page : $page+$next_pages; // 현재 페이지에서 마지막 페이지를 더해 크면 마지막 페이지로 고정

    if ($page < 5) {
        $end_page = 9;
    }

    if ($end_page-$start_page < 8) {
        $start_page = $end_page-8;
    }

    if ($start_page < 1) { // 시작페이지가 1보다 작으면 1로 고정
        $start_page = 1;
    }

    if ($max_page < $end_page) {
        $end_page = $max_page;
    }

    if ($end_page == 0) { // 데이터가 하나도 없어서 0이라면
        $end_page = 1; // 1페이지로 고정
    }

    // 이전 페이지 계산
    $prev_page = $page-1;
    if ($prev_page < 1) { // 이전페이지 계산해서 1보다 작으면
        $prev_page = 1; // 1로 고정
    }

    // 다음 페이지 계산
    $next_page = $page+1;
    if ($next_page > $end_page) { // 다음페이지가 끝나는 페이지 보다 크면
        $next_page = $end_page; // 끝 페이지로 고정
    }

    $page_arr = array();
    for ($i = $start_page; $i <= $end_page; $i++) {
        $active_class = '';
        if ($i == $page) {
            $active_class = 'active';
        }
        $page_arr[] = ['page_num'=>$i, 'active_class'=>$active_class];
    }

    $paging = array();
    $paging['page'] = $page;
    $paging['start_page'] = $start_page;
    $paging['end_page'] = $end_page;
    $paging['max_page'] = $max_page;
    $paging['prev_page'] = $prev_page;
    $paging['next_page'] = $next_page;
    $paging['cnt'] = $cnt;
    $paging['page_arr'] = $page_arr;

    return $paging;
}

function getPagingView($data, $search_arr)
{
    unset($search_arr['cnt']);
    unset($search_arr['search_page']);

    $uri_path = current_url(true)->getPath();

    $paging_file = $data['paging_file'] ?? '/console/paging/general';
    $http_query = http_build_query($search_arr);
    $paging = $data['paging'];

    $paging_view = view($paging_file, ['paging'=>$paging, 'http_query'=>$http_query, 'href_link'=>$uri_path]); // 페이징 뷰

    return $paging_view;
}

function getPagingInfo($data)
{
    $search_arr = $data;

    $paging_array = getPagingArray($data);
    $data['paging'] = $paging_array;
    $paging_view = getPagingView($data, $search_arr);

    $paging_info = array();
    $paging_info['paging_array'] = $paging_array;
    $paging_info['paging_view'] = $paging_view;

    return $paging_info;
}