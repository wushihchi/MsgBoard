<?php

class Msg_List_Ctl extends Controller
{
    public function get_index() {
        return Smarty_View::make('msg/l_msg.html',array('pagename'=>'msg'));
    }

    public function get_msglist() {
        $sChkSession = $this->chkSession();
        //return $sChkSession;
        if($sChkSession=='SUCCESS'){
            $oMsgModel = new Angeldb_MsgBoard_Model;
            $oUserModel = new Angeldb_User_Model;
            //$oModel = new MyDBModel;
            $iPage = $_SESSION['msgpage'];
            $iPageSize = $_SESSION['msgpagesize'];

            $iMsgCnt = $oMsgModel->count(array('1=1'), array('field' => 'msg_id'));

            $iPageCnt = ceil($iMsgCnt/$iPageSize);

            $iLimit1 = $iPageSize*$iPage;
            $iLimit2 = $iPageSize*($iPage-1);
            
            //return '$iLimit1=['.$iLimit1.']$iLimit2=['.$iLimit2.']';
            $aMsgList = $oMsgModel->find(array('1=1'), array(
                'field' => 'msg_id, user_id, msg_content, msg_dtm',
                'order' => 'msg_dtm desc',
                'limit' => $iLimit2.','.$iLimit1
            ));

            foreach ($aMsgList as $key => $value) {
                $aUserList = $oUserModel->find(array('user_id'=>$value['user_id']), array(
                    'field' => 'user_id, user_name'
                ));

                $aMsgList[$key]['user_name'] = $aUserList[0]['user_name'];
            }

            unset($oMsgModel);
            unset($oUserModel);
            
            //return $aMsgList;
            //return json_encode($aMsgList);

            return Smarty_View::make('msg/l_msg.html', array(
                'msglist'       => $aMsgList,
                'msgCnt'        => $iMsgCnt,
                'session_id'    => $_SESSION['user_id'],
                'session_name'  => $_SESSION['user_name'],
                'session_level' => $_SESSION['user_level'],
                'page'          => $iPage,
                'pagesize'      => $iPageSize,
                'pagecnt'       => $iPageCnt
            ));
        }else{
            return $sChkSession;
        }
    }

    public function post_insert() {
        $sChkSession = $this->chkSession();
        
        if($sChkSession=='SUCCESS'){
            $sMsgContent = str_replace("'","''",$_POST["sMsgContent"]);

            $oModel = new Angeldb_MsgBoard_Model;
            $primary_key = $oModel->insert(array(
                'user_id'     => $_POST["sUsrId"],
                'msg_content' => $sMsgContent,
                'msg_dtm' => date ("Y-m-d H:i:s" , mktime(date('H')+7, date('i'), date('s'), date('m'), date('d'), date('Y'))),
            ));
            unset($oModel);

            return $primary_key;
        }else{
            return $sChkSession;
        }
    }

    public function post_update() {
        $sChkSession = $this->chkSession();
        
        if($sChkSession=='SUCCESS'){
            $oModel = new Angeldb_MsgBoard_Model;
            $iUpdateCnt = $oModel->update(array('msg_content'=> $_POST["sMsgContent"]),array('msg_id'=> $_POST["sMsgId"]));
            unset($oModel);

            return $iUpdateCnt;
        }
    }

    public function post_delete() {
        $sChkSession = $this->chkSession();
        
        if($sChkSession=='SUCCESS'){
            $oModel = new Angeldb_MsgBoard_Model;
            $iDeleteCnt = $oModel->delete_data(array('msg_id'=> $_POST["sMsgId"]));
            unset($oModel);

            return $iDeleteCnt;
        }
    }

    private function chkSession() {
        session_start();
        //return $_SESSION['user_id'];
        if($_SESSION['user_id']==''){
            $_SESSION['pagename'] = 'login';
            return Smarty_View::make('login/login.html',array('pagename'=>'login'));
        }else{
            $_SESSION['pagename']    ='msg';
            if(!isset($_SESSION['msgpage'])){
                $_SESSION['msgpage']     = 1;
            }
            if(!isset($_SESSION['msgpagesize'])){
                $_SESSION['msgpagesize'] = 5;
            }
            
            return "SUCCESS";
        }
    }

    public function post_changepage(){
        session_start();
        $_SESSION['msgpage'] = $_POST["page"];
        return true;
    }

    public function get_comment()
    {
        $query = $this->db->get('msgboard.mig_id, msgboard.msg_content, msgboard.user_id, user.user_name')
        ->from('msgboard')
        ->join('user', 'msgboard.user_id = user.user_id');
        return $query->result();
    }

}
