<?php

class Msg_List_Ctl extends Controller
{
    public function get_index()
    {
        return Smarty_View::make('msg/l_msg.html',array('pagename'=>'msg'));
    }

    public function get_msglist()
    {
        if($this->chkSession()){
            $oMsgModel = new Angeldb_MsgBoard_Model;
            $oUserModel = new Angeldb_User_Model;

            $iPage = $_SESSION['msgpage'];
            $iPageSize = $_SESSION['msgpagesize'];

            $iMsgCnt = $oMsgModel->count(array(), array('field'=>'msg_id'));

            $iPageCnt = ceil($iMsgCnt/$iPageSize);

            $iLimit1 = $iPageSize*$iPage;
            $iLimit2 = $iPageSize*($iPage-1);
            
            $aMsgList = $oMsgModel->find(array(), array(
                'field' => 'msg_id, user_id, msg_content, msg_dtm',
                'order' => 'msg_dtm desc',
                'limit' => $iLimit2.','.$iLimit1
            ));

            $aMsgUser = $oMsgModel->find_col('user_id',array(),array('field' => 'user_id'));
            $aUserList = $oUserModel->find_pair('user_id','user_name',array('user_id'=>$aMsgUser),array());

            
            foreach ($aMsgList as $iKey => $aValue) {
                $aMsgList[$iKey]['user_name'] = $aUserList[$aMsgList[$iKey]['user_id']];
            }
            
            //return json_encode($aMsgList);
            unset($oMsgModel);
            unset($oUserModel);

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
            return Smarty_View::make('login/login.html',array('pagename'=>'login'));
        }
    }

    public function post_insert()
    {
        if($this->chkSession()){
            $sMsgContent = str_replace("'","''",$_POST["sMsgContent"]);

            $oModel = new Angeldb_MsgBoard_Model;
            $primary_key = $oModel->insert(array(
                'user_id'     => $_POST["sUsrId"],
                'msg_content' => $sMsgContent,
                'msg_dtm' => date ("Y-m-d H:i:s" , mktime(date('H')+7, date('i'), date('s'), date('m'), date('d'), date('Y'))),
            ));
            unset($oModel);
            if($primary_key){
                return true;
            }else{
                return false;
            }
            //return $primary_key;
        }else{
            return Smarty_View::make('login/login.html',array('pagename'=>'login'));
        }
    }

    public function post_update()
    {
        if($this->chkSession()){
            $oModel = new Angeldb_MsgBoard_Model;
            $iUpdateCnt = $oModel->update(array('msg_content'=> $_POST["sMsgContent"]),array('msg_id'=> $_POST["sMsgId"]));
            unset($oModel);

            if($iUpdateCnt>0){
                return true;
            }else{
                return false;
            }
            
        }else{
            return Smarty_View::make('login/login.html',array('pagename'=>'login'));
        }
    }

    public function post_delete()
    {
        if($this->chkSession()){
            $oModel = new Angeldb_MsgBoard_Model;
            $iDeleteCnt = $oModel->delete_data(array('msg_id'=> $_POST["sMsgId"]));
            unset($oModel);

            if($iDeleteCnt>0){
                return true;
            }else{
                return false;
            }
        }else{
            return Smarty_View::make('login/login.html',array('pagename'=>'login'));
        }
    }

    private function chkSession()
    {
        session_start();

        if($_SESSION['user_id']==''){
            $_SESSION['pagename'] = 'login';
            return false;
        }else{
            $_SESSION['pagename'] ='msg';
            if(!isset($_SESSION['msgpage'])){
                $_SESSION['msgpage'] = 1;
            }
            if(!isset($_SESSION['msgpagesize'])){
                $_SESSION['msgpagesize'] = 5;
            }
            
            return true;
        }
    }

    public function post_changepage()
    {
        session_start();
        $_SESSION['msgpage'] = $_POST["page"];
        return true;
    }

}
