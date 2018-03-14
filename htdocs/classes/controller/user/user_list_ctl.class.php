<?php

class User_List_Ctl extends Controller
{
    public function get_index() {
        return Smarty_View::make('user/l_user.html',array('pagename'=>'user'));
    }

    public function get_userlist() {
        $sChkSession = $this->chkSession();
        
        if($sChkSession=='SUCCESS'){
            $oModel = new Angeldb_User_Model;

            $iPage = $_SESSION['userpage'];
            $iPageSize = $_SESSION['userpagesize'];

            $iUserCnt = $oModel->count(array('1=1'), array('field' => 'msg_id'));

            $iPageCnt = ceil($iUserCnt/$iPageSize);

            $iLimit1 = $iPageSize*$iPage;
            $iLimit2 = $iPageSize*($iPage-1);
            
            $aUserList = $oModel->find(array('1=1'), array(
                'field' => 'user_id, user_name, user_email, user_pwd, user_level',
                'limit' => $iLimit2.','.$iLimit1
            ));
            unset($oModel);

            return Smarty_View::make('user/l_user.html', array(
                'userlist'      => $aUserList,
                'pagename'      => $_SESSION['pagename'],
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

    public function post_update() {
        $sChkSession = $this->chkSession();
        
        if($sChkSession=='SUCCESS'){
            $oModel = new Angeldb_User_Model;
            $iUpdateCnt = $oModel->update(array('user_level'=> $_POST["UserLevel"]),array('user_id'=> $_POST["UserId"]));
            unset($oModel);

            return $iUpdateCnt;
        }
    }

    public function post_delete() {
        $sChkSession = $this->chkSession();
        
        if($sChkSession=='SUCCESS'){
            $oUserModel = new Angeldb_User_Model;
            $iDeleteCnt = $oUserModel->delete_data(array('user_id'=> $_POST["UserId"]));
            unset($oUserModel);

            //使用者刪除,留言人跟著變成0
            $oMsgModel = new Angeldb_MsgBoard_Model;
            $iUpdateCnt = $oMsgModel->update(array('user_id'=> 0),array('user_id'=> $_POST["UserId"]));
            unset($oMsgModel);

            return $iDeleteCnt;
        }
    }

    private function chkSession() {
        session_start();
        if($_SESSION['user_id']==''){
            $_SESSION['pagename']='login';

            return Smarty_View::make('login/login.html',array('pagename'=>'login'));
        }else{
            $_SESSION['pagename']='user';
            if(!isset($_SESSION['userpage'])){
                $_SESSION['userpage']     = 1;
            }
            if(!isset($_SESSION['userpagesize'])){
                $_SESSION['userpagesize'] = 5;
            }

            return "SUCCESS";
        }
    }

    public function post_changepage(){
        session_start();
        $_SESSION['userpage'] = $_POST["page"];
        return true;
    }

}
