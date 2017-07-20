<?php
/**
 * Created by PhpStorm.
 * User: 坤同
 * Date: 2017/6/2
 * Time: 11:37
 */

namespace App\Model;


use Curl\Request;
use SimpleHtmlDom\simple_html_dom;

class Live
{
    function get(){
        $url = 'https://www.bigo.tv/all';
        $request = new Request($url);
        $res = $request->exec();
        $cookie = $res->getCookie(true);
        $content = $res->getBody();
        $html = new simple_html_dom();
        $html->load($content);
        $str = $html->find('#all_ignore_uids',0)->outertext;
        $str = substr($str,strpos($str,'data-value="')+strlen('data-value="'));
        $dataValue = substr($str,0,strpos($str,'"'));
        $arr = explode(' ',microtime());
        $time = intval($arr[0] * 1000) + $arr[1];
        echo $cookie;
        echo PHP_EOL.urldecode($cookie);
        /*$cookie = "hdjs_session_time=$time; ".$cookie;
        $newUrl = "https://www.bigo.tv/openOfficialWeb/vedioList/5";
        $opt = array(
            CURLOPT_REFERER=>$url,
            CURLOPT_COOKIE=>$cookie,
            CURLOPT_HTTPHEADER=>array(
                'Expect:',
            ),
        );
        $data = array(
            'ignoreUids'=>$dataValue,
            'tabType'=>'ALL',
        );
        $request = new Request($newUrl,$opt);
        $request->post($data);
        return $request->exec()->getBody();*/
    }
}