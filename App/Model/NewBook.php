<?php
/**
 * Created by PhpStorm.
 * User: 坤同
 * Date: 2017/4/12
 * Time: 10:30
 */

namespace App\Model;


use Ares333\CurlMulti\Base;
use SimpleHtmlDom\simple_html_dom;

class NewBook extends Base
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
        $ul = $html->find('.carousel',0);
        $length = count($ul->find('li'));
        for($i = 0; $i < $length; $i++){
            $link = $ul->find('li',$i)->find('.title',0)->find('a',0);
            $author = $ul->find('li',$i)->find('.author',0)->plaintext;
            $this->article[$i] = [
                'title'=>$link->plaintext,
                'url'=>$link->href,
                'author'=>trim($author),
            ];
            $this->curl->add(
                array(
                    'url'=>$link->href,
                    'opt'=>[
                        CURLOPT_SSL_VERIFYHOST=>false,
                        CURLOPT_SSL_VERIFYPEER=>false,
                    ],
                    'args'=>[
                        'pos'=>$i,
                    ]
                ),
                array(
                    $this,
                    'detail'
                )
            );
        }
    }
    function detail($ret,$args){
        $content = $ret['content'];
        $html = new simple_html_dom();
        $html->load($content);
        $str = $html->find('#info',0)->innertext;
        $pos = $args['pos'];
        $posStr = substr($str,strpos($str,'<span class="pl">出版社:</span>')+strlen('<span class="pl">出版社:</span>'));
        $this->article[$pos]['press'] = substr($posStr,0,strpos($posStr,'<br/>'));
        $posStr = substr($posStr,strpos($posStr,'<span class="pl">出版年:</span>')+strlen('<span class="pl">出版年:</span>'));
        $this->article[$pos]['time'] = substr($posStr,0,strpos($posStr,'<br/>'));
        $posStr = substr($posStr,strpos($posStr,'<span class="pl">定价:</span>')+strlen('<span class="pl">定价:</span>'));
        $this->article[$pos]['price'] = substr($posStr,0,strpos($posStr,'<br/>'));
        $posStr = substr($posStr,strpos($posStr,'<span class="pl">装帧:</span>')+strlen('<span class="pl">装帧:</span>'));
        $this->article[$pos]['bind'] = substr($posStr,0,strpos($posStr,'<br/>'));
        $posStr = substr($posStr,strpos($posStr,'<span class="pl">ISBN:</span>')+strlen('<span class="pl">ISBN:</span>'));
        $this->article[$pos]['isbn'] = substr($posStr,0,strpos($posStr,'<br/>'));
    }
    function getArticle(){
        return $this->article;
    }
    function start(){
        $this->curl->start();
    }
}