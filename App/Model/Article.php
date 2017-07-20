<?php
/**
 * Created by PhpStorm.
 * User: 坤同
 * Date: 2017/4/5
 * Time: 17:55
 */

namespace App\Model;


use Ares333\CurlMulti\Base;
use SimpleHtmlDom\simple_html_dom;

class Article extends Base
{
    protected $articleArr;
    protected $chapter;
    protected $detail;
    function __construct($curlmulti = null)
    {
        parent::__construct($curlmulti);
    }
    function get(){
        $url = 'http://www.my285.com/yq/f/fumei/sgz1/index.htm';
        $this->curl->add(
            array(
                'url'=>$url,
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
        $title = $html->find('div[align=center]',0)->find('td[style]',0)->plaintext;
        $row = $html->find('div[align=center]',1)->find('td[bgcolor=#FFDF9D]',0);
        $introduction = trim($row->find('td[bgcolor=#F6F6F6]',0)->plaintext);
        $this->articleArr = [
            'title'=>$title,
            'introduction'=>$introduction,
        ];
        $length = count($row->find('table[width=60%]',0)->find('a'));
        for($i = 0; $i < $length; $i++){
            $list = $row->find('table[width=60%]',0)->find('a',$i);
            $url = 'http://www.my285.com/yq/f/fumei/sgz1/'.$list->href;
            $this->chapter[] = [
                'href'=>$url,
                'name'=>$list->plaintext,
                'order'=>substr($list->href,0,strrpos($list->href,'.')),
            ];
            $this->curl->add(
                array(
                    'url'=>$url,
                ),
                array(
                    $this,
                    'detail'
                )
            );
        }
    }
    function detail($ret){
        $content = $this->array_iconv($ret['content']);
        $html = new simple_html_dom();
        $html->load($content);
        $row = $html->find('table[width=80%]',0);
        $name = $row->find('td',1)->plaintext;
        $str =  $row->find('td',3)->plaintext;
        $this->detail[] = [
            'name'=>$name,
            'content'=>$str,
        ];
    }
    function array_iconv($data, $output = 'utf-8') {
        $encode_arr = array('UTF-8','ASCII','GBK','GB2312','BIG5','JIS','eucjp-win','sjis-win','EUC-JP');
        $encoded = mb_detect_encoding($data, $encode_arr);
        if (!is_array($data)) {
            return mb_convert_encoding($data, $output, $encoded);
        }
        else {
            foreach ($data as $key=>$val) {
                $key = array_iconv($key, $output);
                if(is_array($val)) {
                    $data[$key] = array_iconv($val, $output);
                } else {
                    $data[$key] = mb_convert_encoding($data, $output, $encoded);
                }
            }
            return $data;
        }
    }
    function getArticle(){
        return $this->articleArr;
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
