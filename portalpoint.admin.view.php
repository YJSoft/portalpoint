<?php
    /**
     * @class  portalpointAdminView
     * @author 러키군 (admin@barch.kr)
     * @brief  portalpoint 모듈의 admin view class
     **/

    class portalpointAdminView extends portalpoint {

        /**
         * @brief 초기화
         *
         **/
        function init() {
            // template path지정
            $this->setTemplatePath($this->module_path.'tpl');
        }
        /**
         * @brief 포탈설정
         **/
        function dispPortalpointAdminPortal() {
            $oPortalModel = &getModel('portalpoint');
            $portal_config = $oPortalModel->getConfig();

            // target가 있으면 수정모드
            $target = Context::get('target');
            if($target) {
                foreach($portal_config->url_list as $key => $val) {
                    if($target==$val['title']) {
                        Context::set('m_data',$val);
                        break;
                    }
                }
            }

            Context::set('portal_config', $portal_config);

            // 템플릿 파일 지정
            $this->setTemplateFile('portal_list');
        }
        /**
         * @brief 모듈설정
         **/
        function dispPortalpointAdminConfig() {
            $oPortalModel = &getModel('portalpoint');
            $portal_config = $oPortalModel->getConfig();
            Context::set('portal_config', $portal_config);

            // 템플릿 파일 지정
            $this->setTemplateFile('config');
        }
        /**
         * @brief 로그관리
         **/
        function dispPortalpointAdminLog() {
            // 코드리스트 구해옴
            $oPortalAdminModel = &getAdminModel("portalpoint");
            $log_list = $oPortalAdminModel->getLogList();
            Context::set('log_list',$log_list);

            $print_option = Context::get('print_option');
            $print_option_list = Context::getLang('member_print_option');
            if(!$print_option_list[$print_option]) $print_option = "nick_name";
            Context::set('print_option',$print_option);

            // 템플릿 파일 지정
            $this->setTemplateFile('log_list');
        }
    }
?>
