<?php
/**
 * Created by PhpStorm.
 * User: 坤同
 * Date: 2017/5/31
 * Time: 16:18
 */

namespace App\Model;


use SimpleHtmlDom\simple_html_dom;

class Test
{
    function get(){
        $url = 'http://news.baidu.com/f/';
        $request = new \Curl\Request($url);
        $content = iconv('gbk','utf-8',$request->exec()->getBody());
        $html = new simple_html_dom();
        $html->load($content);
        $length = count($html->find('center'));
        $data = [];
        for($i = 1; $i < $length; $i++){
            $keyObj = $html->find('center',$i);
            $key = trim(html_entity_decode($keyObj->plaintext));
            $table = $keyObj->next_sibling();
            $trLen = count($table->find('td'));
            for($j = 0; $j < $trLen; $j++){
                $link = $table->find('td',$j)->find('a',0);
                if(isset($link)){
                    $data[$key][] = [
                        'link'=>$url.$link->href,
                        'text'=>$link->plaintext,
                    ];
                }
            }
        }
        return $data;
    }
}