<?php
    /**
     * @class  portalpoint
     * @author 러키군 (admin@barch.kr)
     **/

    class portalpoint extends ModuleObject {
        var $default_link_list = array(
            array("title" => "naver","url" => "http://www.naver.com","point" => 10, "delay" => 1),
            array("title" => "daum","url" => "http://www.daum.net","point" => 10, "delay" => 1)
        );

        /**
         * @brief 설치시 추가 작업이 필요할시 구현
         **/
        function moduleInstall() {
            $oModuleController = &getController('module');

            // 모듈의 기본설정 저장
            $config = null;
            $config->url_list = serialize($this->default_link_list);;
            $oModuleController->insertModuleConfig('portalpoint', $config);

            // 회원탈퇴시 로그를 삭제하기위한 트리거
            $oModuleController->insertTrigger('member.deleteMember', 'portalpoint', 'controller', 'triggerDeleteMember', 'after');

            return new Object();
        }

        /**
         * @brief 설치가 이상이 없는지 체크하는 method
         **/
        function checkUpdate() {
            $oModuleModel = &getModel('module');
            if(!$oModuleModel->getTrigger('member.deleteMember', 'portalpoint', 'controller', 'triggerDeleteMember', 'after')) return true;

            return false;
        }

        /**
         * @brief 업데이트 실행
         **/
        function moduleUpdate() {
            $oModuleModel = &getModel('module');
            $oModuleController = &getController('module');

            if(!$oModuleModel->getTrigger('member.deleteMember', 'portalpoint', 'controller', 'triggerDeleteMember', 'after')) 
                $oModuleController->insertTrigger('member.deleteMember', 'portalpoint', 'controller', 'triggerDeleteMember', 'after');

            return new Object(0, 'success_updated');
        }

		function moduleUninstall() {
            return new Object();
		}

        /**
         * @brief 캐시 파일 재생성
         **/
        function recompileCache() {
        }

    }
?>
