<?php
namespace App\Factory;
use Conf\Config;

/**
 * Created by PhpStorm.
 * User: 坤同
 * Date: 2017/6/16
 * Time: 15:21
 */
class Db
{
    protected $db;
    function __construct()
    {
        $conf = Config::getInstance()->getConf('MYSQL');
        $this->db = new \MysqliDb(
            $conf['DB_HOST'],
            $conf['DB_USER'],
            $conf['DB_PWD'],
            $conf['DB_NAME'],
            $conf['DB_PORT'],
            $conf['DB_CHARSET']
        );
    }
}