<?php

/**
 * 管理者帳號
 *
 */
class Angeldb_MsgBoard_Model extends MyDBModel
{
    protected $schema = array(
        'msg_id'       => 'int',
        'user_id'      => 'int',
        'msg_content'  => 'string',
        'msg_dtm'      => 'datetime',
    );

}
