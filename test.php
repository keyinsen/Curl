<?php

/**
 * Created by PhpStorm.
 * User: 坤同
 * Date: 2017/2/21
 * Time: 12:01
 */

require_once "AutoLoader.php";
const ROOT = __DIR__;
function addNamespace(){
    $loader = AutoLoader::getInstance();
    $loader->addNamespace('Ares333\CurlMulti','Vendor\CurlMulti');
    $loader->addNamespace('SimpleHtmlDom','Vendor\SimpleHtmlDom');
    $loader->requireFile(ROOT.'/Vendor/Db/MysqliDb.php');
    $loader->addNamespace('App',"App");
    $loader->addNamespace('Conf',"Conf");
    $loader->addNamespace('Curl','Vendor\Curl');
    $loader->addNamespace('Parse','Vendor\Parse');
    $loader->requireFile('Vendor/PHPExcel/Classes/PHPExcel.php');
    $loader->requireFile('Vendor/PhpQuery/phpQuery.php');
}
addNamespace();

/*$model = new \App\Model\Copy();
$model->get();*/

/*$model = new \App\Excel\BookClass();
print_r($model->get());*/

/*$model = new \App\Model\HeadLine();
print_r($model->get());*/

$url = 'http://ws.stream.qqmusic.qq.com/C100001H3aXP1jHwie.m4a?fromtag=38';
$curl = new \Curl\Request($url);
$content = $curl->exec();
file_put_contents('a.mp3',$content);










