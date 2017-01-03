<?php
    /**
     * @class  portalpointAdminController
     * @author 러키군 (admin@barch.kr)
     * @brief  portalpoint 모듈의 admin controller class
     **/

    class portalpointAdminController extends portalpoint {

        /**
         * @brief 초기화
         **/
        function init() {
        }
        /**
         * @brief 모듈설정 저장
         **/
        function procPortalpointAdminInsertConfig() {
            // 설정 정보 가져오기
            $oPortalModel = &getModel('portalpoint');
            $portal_config = $oPortalModel->getConfig();

            // 변수 정리
            $args = Context::gets('max_point','use_excess','use_guest','excess_message','guest_message');
            $portal_config->max_point = (int)$args->max_point;
            $portal_config->use_excess = ($args->use_excess == 'N')? 'N' : '';
            $portal_config->use_guest = ($args->use_guest == 'N')? 'N' : '';
            $portal_config->excess_message = $args->excess_message;
            $portal_config->guest_message = $args->guest_message;
            $portal_config->url_list = serialize($portal_config->url_list);

            // 저장
            $oModuleController = &getController('module');
            $oModuleController->insertModuleConfig('portalpoint', $portal_config);

            $this->setMessage('success_saved');
        }
        /**
         * @brief 포탈추가/수정
         **/
        function procPortalpointAdminInsertPortal() {
            // 설정 정보 가져오기
            $oPortalModel = &getModel('portalpoint');
            $portal_config = $oPortalModel->getConfig();

            // 변수 정리
            $args = Context::gets('title','url','point','target','delay','delay_type','click_message1','click_message2','use_message1','use_message2');
            if(!$args->title) return new Object(-1,'isnull_portal_title');
            if(!preg_match("/^[a-z]+:\/\//i",$args->url)) $args->url = 'http://'.$args->url;
            $args->point = (int)$args->point;
            $args->delay = (int)$args->delay;
            $args->delay_type = (int)$args->delay_type;
            $args->use_message1 = ($args->use_message1=='N')? 'N' : '';
            $args->use_message2 = ($args->use_message2=='N')? 'N' : '';
            $obj = array("title" => $args->title, "url" => $args->url, "point" => $args->point, "delay" => $args->delay, "delay_type" => $args->delay_type, "click_message1" => trim($args->click_message1), "click_message2" => trim($args->click_message2), "use_message1" => $args->use_message1, "use_message2" => $args->use_message2);

            //  title 중복검사
            $data = array();
            if($portal_config->url_list) {
                foreach($portal_config->url_list as $key => $val) {
                    if($args->title==$val['title']) {
                        if($args->target==$args->title) $val = $obj;
                        else return new Object(-1,'already_portal');
                    }
                    $data[$key] = $val;
                }
            }
            $portal_config->url_list = $data;

            // target이 없으면 추가
            if(!$args->target) $portal_config->url_list[] = $obj;

            // 저장
            $portal_config->url_list = serialize($portal_config->url_list);
            $oModuleController = &getController('module');
            $oModuleController->insertModuleConfig('portalpoint', $portal_config);

            $this->setMessage('success_saved');
        }
        /**
         * @brief 포탈삭제
         **/
        function procPortalpointAdminDeletePortal() {
            // 설정 정보 가져오기
            $oPortalModel = &getModel('portalpoint');
            $portal_config = $oPortalModel->getConfig();

            // 변수 정리
            $vars = Context::get('titles');
            $vars_list = explode("|",$vars);
            if(!count($vars_list)) return new Object(-1,'msg_cart_is_null');
            if(!count($portal_config->url_list)) return new Object(-1,'msg_cart_is_null');

            if($portal_config->url_list) {
                $data = array();
                foreach($portal_config->url_list as $key => $val) {
                    if(in_array($val['title'],$vars_list)) continue;
                    $data[$key] = $val;
                }
            }
            $portal_config->url_list = (count($data))? serialize($data) : '';
            $oModuleController = &getController('module');
            $oModuleController->insertModuleConfig('portalpoint', $portal_config);

            $this->setMessage('success_deleted');
        }
        /**
         * @brief 로그삭제
         **/
        function procPortalpointAdminLogDelete() {
            $log_srls = Context::get('log_srls');
            if(!$log_srls) return new Object(-1,'msg_cart_is_null');

            $args = null;
            $args->log_srls = $log_srls;
            $this->deleteLog($args);
            $this->setMessage('success_deleted');
        }
        function deleteLog($args) {
            return executeQuery("portalpoint.deleteLog",$args);
        }
    }
?>
