<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2017/2/6
 * Time: 下午6:48
 */

namespace Conf;


class Config
{
    protected static $instance;
    private $conf;

    function __construct()
    {
        $this->setConf();
    }

    /*
     * Core Instance is a singleTon in a request lifecycle
     * @return Config instance
     */
    static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new static();
        }
        return self::$instance;
    }

    function getConf($key){
        if(isset($this->conf[$key])){
            return $this->conf[$key];
        }else{
            return null;
        }
    }

    private function setConf(){
        $this->conf = array(
            "MYSQL"=>array(
                "DB_HOST"=>"127.0.0.1",
                "DB_USER"=>"root",
                "DB_PWD"=>"root",
                "DB_NAME"=>"blank",
                "DB_PORT"=>null,
                "DB_CHARSET"=>'utf8'
            ),
        );
    }
}