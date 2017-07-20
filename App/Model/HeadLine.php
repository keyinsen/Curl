<?php
/**
 * Created by PhpStorm.
 * User: 坤同
 * Date: 2017/6/2
 * Time: 14:55
 */

namespace App\Model;


use Curl\Request;

class HeadLine
{
    function get(){
        $url= 'http://www.toutiao.com/search_content/';
        $param = [
            'offset'=>0,
            'format'=>'json',
            'keyword'=>'每日穿衣之道',
            'autoload'=>true,
            'count'=>20,
            'cur_tab'=>1,
        ];
        $url = $url.'?'.http_build_query($param);
        $curl = new Request($url);
        $content = $curl->exec()->getBody();
        $arr = json_decode($content,true);
        $sourceUrl = $arr['data'][0]['source_url'];
        preg_match_all('/[\d]+/',$sourceUrl,$match);
        $uid = $match[0][0];
        $origin = 'http://www.toutiao.com';
        $curl->setUrl($origin);
        $cookie =  $curl->exec()->getCookie(1);
        $newUrl = 'http://www.toutiao.com/c/user/article/';
        $param = [
            'user_id'=>$uid,
            'count'=>100,
        ];
        $newUrl = $newUrl.'?'.http_build_query($param);
        $curl->setUrl($newUrl);
        $curl->setOpt([CURLOPT_COOKIE=>$cookie]);
        $ret = $curl->exec()->getBody();
        $json = json_decode($ret,true);
        $data = $json['data'];
        $res = [];
        foreach ($data as $item){
            $list = [];
            foreach($item['image_list'] as $subItem){
                $list[] = $subItem['url'];
            }
            $res[] = [
                'title'=>$item['title'],
                'source'=>$item['source'],
                'image_url'=>$item['image_url'],
                'image_list'=>$list,
                'link'=>'http://www.toutiao.com'.$item['source_url'],
            ];
        }
        file_put_contents('a.txt',json_encode($res,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
        return $res;
    }
}