<?php

class Regist_Page_Ctl extends Controller
{
    public function get_index()
    {
        session_start();
        $_SESSION['pagename']='regist';
        return Smarty_View::make('regist/regist.html',array());
    }

    public function post_insert()
    {
        $oModel = new Angeldb_User_Model;
        $sUserName = str_replace("'","''",$_POST["UserName"]);

        $primary_key = $oModel->insert(array(
            'user_name'  => $sUserName,
            'user_email' => $_POST["UserEmail"],
            'user_pwd'   => md5($_POST["UserPwd"]),
            'user_level' => '5',
        ));
        unset($oModel);
        if ($primary_key) {
            return true;
        }
        
    }

    public function post_checkemailexist()
    {
        $oModel = new Angeldb_User_Model;

        $aChkUserEmail = $oModel->get(
            array('user_email' => $_POST["userEmail"]), 
            array('field' => 'user_id'
        ));

        unset($oModel);

        //return json_encode($aChkUserEmail); 
        if ($aChkUserEmail!=null) {
            return false;
        } else {
            return true;
        }

        
    }

    public function post_checknameexist()
    {
        $oModel = new Angeldb_User_Model;
        $sUserName = str_replace("'","''",$_POST["userName"]);
        $aChkUserName = $oModel->get(
            array('user_name' => $sUserName), 
            array('field' => 'user_id'
        ));
        
        unset($oModel);
        if ($aChkUserName!=null) {
            return false;
        } else {
            return true;
        }

        
    }

}
