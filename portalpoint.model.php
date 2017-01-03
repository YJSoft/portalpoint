<?php
    /**
     * @class  portalpointModel
     * @author 러키군 (admin@barch.kr)
     * @brief  portalpoint 모듈의 Model class
     **/

    class portalpointModel extends portalpoint {
        /**
         * @brief 초기화
         **/
        function init() {
        }
        /**
         * @brief 모듈정보 가져옴
         **/
        function getConfig() {
            // 설정 정보 가져오기
            $oModuleModel = &getModel('module');
            $portal_config = $oModuleModel->getModuleConfig('portalpoint');
            if($portal_config->url_list) $portal_config->url_list = unserialize($portal_config->url_list);

            return $portal_config;
        }
        /**
         * @brief 해당포탈의 쿨타임이 지났는지 검사함
         * 클릭 안했을경우 false, 이미 했으면 true
         **/
        function todayClickCheck($member_srl,$obj) {
            if(!$obj['title']) return false;
            if(!$obj['delay']) return true;
            $obj['delay_type'] = (int)$obj['delay_type']; // 일,분

            // 이미 클릭한 로그정보가 있는지 체크함
            $args = null;
            $args->title = $obj['title'];
            $args->member_srl = $member_srl;
            $args->ipaddress_prefix  = preg_replace('/([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9]+)/','$1.$2.$3', $_SERVER['REMOTE_ADDR']);
            $output = executeQuery("portalpoint.getLog",$args);

            // 로그가 없으면 그냥 false
            if(!$output->data) return false;

            $last_regdate = zdate($output->data->regdate,"Y-m-d H:i:s");
            $now = date("YmdHi");

            // 시간비교-분일때
            if($obj['delay_type']==1) $end_date = date("YmdHi",strtotime(sprintf("+%d minutes",$obj['delay']),strtotime($last_regdate)));
            else $end_date = date("YmdHi",strtotime(sprintf("+%d day",$obj['delay']),strtotime($last_regdate)));
            if($now > $end_date) return false;

            return true;
        }
        /**
         * @brief 클릭한회원의 오늘 획득포인트를 리턴
         **/
        function getTodayTotalPoint($member_srl) {
            $args = null;
            $args->member_srl = $member_srl;
            $args->ipaddress_prefix  = preg_replace('/([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9]+)/','$1.$2.$3', $_SERVER['REMOTE_ADDR']);
            $args->regdate = date("Ymd");
            $output = executeQuery("portalpoint.getLogWithPoint",$args);

            $total_point = (int)$output->data->total_point;
            return $total_point;
        }
    }
?>
