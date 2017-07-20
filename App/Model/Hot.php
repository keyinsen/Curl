<?php
/**
 * Created by PhpStorm.
 * User: 坤同
 * Date: 2017/4/12
 * Time: 10:10
 */

namespace App\Model;


use Ares333\CurlMulti\Base;
use SimpleHtmlDom\simple_html_dom;

class Hot extends Base
{
    protected $jd;
    protected $dang;
    function __construct($curlmulti = null)
    {
        parent::__construct($curlmulti);
    }
    function get(){
        $url = 'https://book.douban.com';
        $this->curl->add(
            array(
                'url'=>$url,
                'opt'=>[
                    CURLOPT_SSL_VERIFYHOST=>false,
                    CURLOPT_SSL_VERIFYPEER=>false,
                ]
            ),
            array(
                $this,
                'source'
            )
        );
    }
    function source($ret){
        $content = $ret['content'];
        $html = new simple_html_dom();
        $html->load($content);
        $ul = $html->find('.list-ranking',0);
        $length = count($ul->find('li'));
        for($i = 0; $i < $length; $i++){
            $link = $ul->find('li',$i)->find('.book-info',0)->find('a',0);
            $author = $ul->find('li',$i)->find('.author',0)->plaintext;
            $this->jd[] = [
                'title'=>$link->plaintext,
                'url'=>$link->href,
                'author'=>$author,
            ];
        }
        $ul = $html->find('.list-ranking',1);
        $length = count($ul->find('li'));
        for($i = 0; $i < $length; $i++){
            $link = $ul->find('li',$i)->find('.book-info',0)->find('a',0);
            $author = $ul->find('li',$i)->find('.author',0)->plaintext;
            $this->dang[] = [
                'title'=>$link->plaintext,
                'url'=>$link->href,
                'author'=>$author,
            ];
        }
    }
    function getJd(){
        return $this->jd;
    }
    function getDang(){
        return $this->dang;
    }
    function start(){
        $this->curl->start();
    }
}