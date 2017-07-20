<?php
/**
 * Created by PhpStorm.
 * User: 坤同
 * Date: 2017/4/12
 * Time: 10:19
 */

namespace App\Model;


use Ares333\CurlMulti\Base;
use SimpleHtmlDom\simple_html_dom;

class Concern extends Base
{
    protected $article;
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
        $ul = $html->find('.list-col',0);
        $length = count($ul->find('li'));
        for($i = 0; $i < $length; $i++){
            $link = $ul->find('li',$i)->find('.title',0)->find('a',0);
            $author = $ul->find('li',$i)->find('.author',0)->plaintext;
            $this->article[] = [
                'title'=>$link->plaintext,
                'url'=>$link->href,
                'author'=>trim($author),
            ];
        }
    }
    function getArticle(){
        return $this->article;
    }
    function start(){
        $this->curl->start();
    }
}