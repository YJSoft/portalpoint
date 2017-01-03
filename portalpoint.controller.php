<?php
    /**
     * @class  portalpointController
     * @author 러키군 (admin@barch.kr)
     * @brief  portalpoint 모듈의 Controller class
     **/

    class portalpointController extends portalpoint {

        /**
         * @brief 초기화
         **/
        function init() {
        }
        function insertLog($args) {
            $args->log_srl = getNextSequence();
            $output = executeQuery("portalpoint.insertLog",$args);

            return $output;
        }
        /**
         * @brief 회원탈퇴시 로그 삭제 trigger
         **/
        function triggerDeleteMember(&$obj) {
            $args->member_srl = $obj->member_srl;
            $output = executeQuery("portalpoint.deleteLog",$args);
            if(!$output->toBool()) return $output;

            return new Object();
        }
    }
?>
