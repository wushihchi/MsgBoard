<?php

class Login_Page_Ctl extends Controller
{
    public function get_index()
    {
        session_start();
        $_SESSION['pagename']='login';
        return Smarty_View::make('login/login.html',array('pagename'=>'login'));
    }

    public function post_login()
    {
        $oModel = new Angeldb_User_Model;

        $aEmailExist = $oModel->get(
            array('user_email'=>$_POST["userEmail"]), 
            array('field'=>'user_id'
        ));

        if ($aEmailExist==null) {
            unset($oModel);
            //return '您尚未註冊，請先註冊!';
            return false;
        }

        $aUserList = $oModel->get(
            array('user_email'=>$_POST["userEmail"],'user_pwd'=>md5($_POST["userPwd"])),
            array('field'=>'user_id','user_name','user_level')
        );
        
        if ($aUserList==null) {
            unset($oModel);
            //return '密碼輸入錯誤，請確認!';
            return false;
        }

        session_start();

        $_SESSION['user_id']    = $aUserList['user_id'];
        $_SESSION['user_level'] = $aUserList['user_level'];
        $_SESSION['user_name']  = $aUserList['user_name'];

        unset($oModel);

        return true;
    }

    public function get_logout()
    {
        session_start();
        session_destroy();

        $_SESSION['pagename']='login';
        return Smarty_View::make('login/login.html',array('pagename'=>$_SESSION['pagename']));
    }

}

