<?php
    /**
     * @class  portalpointView
     * @author 러키군 (admin@barch.kr)
     * @brief  portalpoint 모듈의 View class
     **/

    class portalpointView extends portalpoint {
        /**
         * @brief 초기화
         **/
        function init() {
            // template path지정
            $this->setTemplatePath($this->module_path.'tpl');
        }
        /**
         * @brief 실W제 클릭이벤트 발생
         **/
        function dispPortalpointIndex() {
            global $lang;

            // 권한 체크
            $logged_info = Context::get('logged_info');
            //if(!$logged_info->member_srl) return new Object(-1,'msg_not_permitted');

            // title 존재하는지 검사
            $title = Context::get('title');
            if(!$title) return new Object(-1,'msg_invalid_request');

            // 포탈목록 가져옴
            $oPortalModel = &getModel('portalpoint');
            $portal_config = $oPortalModel->getConfig();

            // 클릭한 포탈정보 구해옴
            if($portal_config->url_list) {
                foreach($portal_config->url_list as $key => $val) {
                    if($title==$val['title']) {
                        $selected = $val;
                        break;
                    }
                }
            }
            if(!$selected) return new Object(-1,'msg_invalid_request');
            Context::set('selected',$selected);

            // 비회원일때
            if(!$logged_info->member_srl) {
                if($portal_config->use_guest!='N') $message = ($portal_config->guest_message)? str_replace("\n","\\n",$portal_config->guest_message) : $lang->guest_message;
            } else { // 회원일때 처리
                // 오늘 획득한 포인트를 구해옴
                $today_point = $oPortalModel->getTodayTotalPoint($logged_info->member_srl);

                // 오늘 획득할수 있는 포인트를 초과했는지 검사
                if(($portal_config->max_point > 0) && ($today_point > $portal_config->max_point)) {
                    // 최대포인트 초과시 메세지를 출력할때..
                    if($portal_config->use_excess!='N') $message = ($portal_config->excess_message)? str_replace(array("[max_point]","[today_point]","\n"),array($portal_config->max_point,$today_point,"\\n"),$portal_config->excess_message) : $lang->excess_message;
                } else { // 포인트 초과안했을경우..
                    // 오늘 클릭했는지 검사
                    $check = $oPortalModel->todayClickCheck($logged_info->member_srl,$selected);

                    // 이미 클릭했을때 처리..
                    if($check) {
                        // 메세시 설정에 따라 처리
                        if($selected['use_message2']!='N') $message = ($selected['click_message2'])? str_replace(array("[point]","[url]","\n"),array($selected['point'],$selected['url'],"\\n"),$selected['click_message2']) : $lang->click_message2;
                    } else { // 클릭안했으면 포인트 지급
                        $oPointController = &getController('point');
                        $oPortalController = &getController('portalpoint');
                        $oPointController->setPoint($logged_info->member_srl,$selected['point'], 'add'); // 포인트 지급

                        // 로그 추가
                        $args = null;
                        $args->member_srl = $logged_info->member_srl;
                        $args->title = $selected['title'];
                        $args->url = $selected['url'];
                        $args->point = $selected['point'];
                        $oPortalController->insertLog($args);

                        // 메시지 출력
                        if($selected['use_message1']!='N') $message = ($selected['click_message1'])? str_replace(array("[point]","[url]","\n"),array($selected['point'],$selected['url'],"\\n"),$selected['click_message1']) : sprintf($lang->click_message1,$selected['point']);
                    }

                }
            }

            if($message) Context::set('message',$message);
            $this->setTemplateFile('_result');
        }
    }
?>