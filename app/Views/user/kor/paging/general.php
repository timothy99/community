<nav>
    <ul class="pagination pagination-sm mb-0" id="pagination">
        <li class="page-item"><a class="page-link" href="<?=$href_link ?>?search_page=1&<?=$http_query ?>">&laquo;</a></li>
        <li class="page-item"><a class="page-link" href="<?=$href_link ?>?search_page=<?=$paging["prev_page"] ?>&<?=$http_query ?>">&lsaquo;</a></li>
<?php   foreach ($paging["page_arr"] as $no => $val) { ?>
        <li class="page-item <?=$val["active_class"] ?>"><a class="page-link" href="<?=$href_link ?>?search_page=<?=$val["page_num"] ?>&<?=$http_query ?>"><?=$val["page_num"] ?></a></li>
<?php   } ?>
        <li class="page-item"><a class="page-link" href="<?=$href_link ?>?search_page=<?=$paging["next_page"] ?>&<?=$http_query ?>">&rsaquo;</a></li>
        <li class="page-item"><a class="page-link" href="<?=$href_link ?>?search_page=<?=$paging["max_page"] ?>&<?=$http_query ?>">&raquo;</a></li>
    </ul>
</nav>
