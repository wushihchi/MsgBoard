<?php

/**
 * 管理者帳號
 *
 */
class CoreDB_AvengersInfo_Model extends MyDBModel
{
    protected $schema = array(
        'AdminID'        => 'int',
        'LoginName'      => 'string',
        'Nickname'       => 'string',
        'Password'       => 'string',
        'AdminType'      => 'int',
        'Disable'        => 'string',
        'CreateDate'     => 'datetime',
        'DisableDate'    => 'datetime',
        'HallID'         => 'int',
        'Salt'           => 'string',
        'AuthCodeEnable' => 'int',
        'AuthCode'       => 'string',
        'LoginError'     => 'int',
    );

}
