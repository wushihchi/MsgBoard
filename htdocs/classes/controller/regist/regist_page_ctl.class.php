<?php

class Regist_Page_Ctl extends Controller
{
    public function get_index()
    {
        //return "Message_List_Ctl";
        session_start();
        $_SESSION['pagename']='regist';
        return Smarty_View::make('regist/regist.html',array());
    }

    public function post_insert()
    {
        $oModel = new Angeldb_User_Model;
        $sUserName = str_replace("'","''",$_POST["UserName"]);
        //return 'md5($_POST["UserPwd"])=['.md5($_POST["UserPwd"]).']$_POST["UserPwd"]=['.$_POST["UserPwd"].']';
        $primary_key = $oModel->insert(array(
            'user_name'  => $sUserName,
            'user_email' => $_POST["UserEmail"],
            'user_pwd'   => md5($_POST["UserPwd"]),
            'user_level' => '5',
        ));
        unset($oModel);

        return $primary_key;
    }

    public function post_checkexist()
    {
        $oModel = new Angeldb_User_Model;
        $sUserName = str_replace("'","''",$_POST["userName"]);
        $aChkUserName = $oModel->get(array('user_name' => $sUserName), array('field' => 'user_id'));
        if($aChkUserName!=null){
            unset($oModel);

            return "暱稱已存在,請重新輸入!";
        }

        $aChkUserEmail = $oModel->get(array('user_email' => $_POST["userEmail"]), array('field' => 'user_id'));
        if($aChkUserEmail!=null){
            unset($oModel);

            return "Email已存在,請重新輸入!";
        }

        unset($oModel);

        return "NONE";
    }

}
