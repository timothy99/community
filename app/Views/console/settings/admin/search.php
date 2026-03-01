                    <div class="table-responsive mt-3" id="search_result_table">
                        <table class="table table-bordered table-hover bg-white align-middle text-center mb-0 text-nowrap">
                            <thead class="table-info">
                                <tr>
                                    <th>번호</th>
                                    <th>아이디</th>
                                    <th>이름</th>
                                    <th>전화</th>
                                    <th>이메일</th>
                                    <th>관리</th>
                                </tr>
                            </thead>
                            <tbody id="search_result_body">
<?php   foreach($list as $no => $val) { ?>
                                <tr>
                                    <td><?=$cnt-$no ?></td>
                                    <td><?=$val->member_id ?></td>
                                    <td><?=$val->member_name ?></td>
                                    <td><?=$val->phone ?></td>
                                    <td><?=$val->email ?></td>
                                    <td><button type="button" class="btn btn-sm btn-primary" onclick="adminAdd('<?=$val->member_id ?>')">등록</button></td>
                                </tr>
<?php   } ?>
<?php   if (count($list) == 0) { ?>
                                <tr>
                                    <td colspan="6" class="text-center">검색 결과가 없습니다.</td>
                                </tr>
<?php   } ?>
                            </tbody>
                        </table>
                    </div>