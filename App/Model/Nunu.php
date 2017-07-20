<?php
/**
 * Created by PhpStorm.
 * User: 坤同
 * Date: 2017/4/6
 * Time: 9:41
 */

namespace App\Model;


use Ares333\CurlMulti\Base;
use SimpleHtmlDom\simple_html_dom;

class Nunu extends Base
{
    protected $article;
    protected $chapter;
    protected $detail;
    function __construct($curlmulti = null)
    {
        parent::__construct($curlmulti);
    }
    function get(){
        $url = 'http://www.kanunu8.com/book2/10734/index.html';
        $this->curl->add(
            array(
                'url'=>$url,
                'opt'=>[
                    CURLOPT_TIMEOUT=>0,
                ]
            ),
            array(
                $this,
                'title'
            )
        );
    }
    function title($ret){
        $content = iconv('gbk','utf-8',$ret['content']);
        $html = new simple_html_dom();
        $html->load($content);
        $title = $html->find('td[height=60]',0)->find('font',0)->plaintext;
        $introduction = $html->find('td.p10-24',1)->plaintext;
        $this->article = [
            'title'=>$title,
            'introduction'=>$introduction,
        ];
        $length = count($html->find('table[width=800]',1)->find('a'));
        for($i = 0; $i < $length; $i++){
            $list = $html->find('table[width=800]',1)->find('a',$i);
            $url = 'http://www.kanunu8.com/book2/10734/'.$list->href;
            $this->chapter[] = [
                'href'=> $url,
                'name'=>$list->plaintext,
                'order'=>($i+1),
            ];
            $this->curl->add(
                array(
                    'url'=>$url,
                    'opt'=>[
                        CURLOPT_TIMEOUT=>0,
                    ],
                ),
                array(
                    $this,
                    'detail',
                )
            );
        }
    }
    function detail($ret){
        $content = iconv('gbk','utf-8',$ret['content']);
        $html = new simple_html_dom();
        $html->load($content);
        $title = $html->find('font[color=#dc143c]',0)->plaintext;
        $str = $html->find('td[width=820]',0)->plaintext;
        $this->detail[] = [
            'title'=>$title,
            'content'=>$str,
        ];
    }
    function getArticle(){
        return $this->article;
    }
    function getChapter(){
        return $this->chapter;
    }
    function getDetail(){
        return $this->detail;
    }
    function start(){
        $this->curl->start();
    }
}