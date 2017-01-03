<?php
    /**
     * @class  portalpointAdminModel
     * @author 러키군 (admin@barch.kr)
     **/

    class portalpointAdminModel extends portalpoint {
        /**
         * @brief 초기화
         **/
        function init() {
        }
        /**
         * @brief 로그리스트 구해옴
         **/
        function getLogList() {
            $oMemberModel = &getModel('member');
            $search_keyword = trim(Context::get('search_keyword'));
            $search_target = Context::get('search_target');
            $search_target_list = array("member_srl","title","ipaddress");
            $regdate_less = Context::get('regdate_less');
            $regdate_more = Context::get('regdate_more');

            $args = null;
            $args->sort_index = "regdate";
            $args->sort_order = "desc";
            if($regdate_less) $args->regdate_less = (int)$regdate_less;
            if($regdate_more) $args->regdate_more = (int)$regdate_more;
            if($search_keyword) {
                if($search_target=='user_id') {
                    $member_srl = $oMemberModel->getMemberSrlByUserID($search_keyword);
                    $args->member_srl = (int)$member_srl;
                } elseif($search_target=='nick_name') {
                    $member_srl = $oMemberModel->getMemberSrlByNickName($search_keyword);
                    $args->member_srl = (int)$member_srl;
                } elseif(in_array($search_target,$search_target_list)) {
                    $args->{$search_target} = $search_keyword;
                }
            }

            // 기타 변수들 정리
            $args->page = Context::get('page');
            $args->list_count = 20;
            $args->page_count = 10;
            $output = executeQuery("portalpoint.getLogList",$args);

            Context::set('total_count', $output->total_count);
            Context::set('total_page', $output->total_page);
            Context::set('page', $output->page);
            Context::set('page_navigation', $output->page_navigation);

            return $output->data;
        }
    }
?>
