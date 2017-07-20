<?php
/**
 * Created by PhpStorm.
 * User: 坤同
 * Date: 2017/4/24
 * Time: 11:42
 */

namespace App\Model;


use Ares333\CurlMulti\Base;
use SimpleHtmlDom\simple_html_dom;

class Mater extends Base
{
    protected $title;
    protected $detail;
    protected $link;
    function __construct($curlmulti = null)
    {
        parent::__construct($curlmulti);
    }
    function get($url = 'http://www.gwcg.com.cn/index.php?s=/Home/Purchase/purchase/cid.html'){
        $this->curl->add(
            array(
                'url'=>$url,
            ),
            array(
                $this,
                'info'
            )
        );
    }
    function info($ret){
        $content = $ret['content'];
        $html = new simple_html_dom();
        $html->load($content);
        $ul = $html->find('.list-page',0);
        $str = $ul->find('.num',0);
        if(!empty($str)){
            $this->link = 'http://www.gwcg.com.cn'.$ul->find('a.num',0)->href;
        }else{
            $this->link = '';
        }
        $ul = $html->find('.caigou',0);
        $length = count($ul->find('div'));
        for($i = 0; $i < $length; $i++){
            $li = $ul->find('div',$i);
            $url = 'http://www.gwcg.com.cn'.$li->find('a',0)->href;
            /*$str = $li->find('span',0)->plaintext;
            $author = substr($str,strpos($str,'：')+strlen('：'));
            $str = $li->find('span',1)->plaintext;
            $time = substr($str,strpos($str,'：')+strlen('：'));*/
            $this->title[] = [
                'url'=>$url,
                /*'title'=>$li->find('h5',0)->plaintext,
                'author'=>$author,
                'time'=>$time,*/
            ];
            $this->curl->add(
                array(
                    'url'=>$url,
                ),
                array(
                    $this,
                    'detail',
                )
            );
        }
    }
    function detail($ret){
        $content = $ret['content'];
        $html = new simple_html_dom();
        $html->load($content);
        $ul = $html->find('.caigou',0);
        $str = $ul->find('span',0)->plaintext;
        $author = substr($str,strpos($str,'：')+strlen('：'));
        $str = $ul->find('span',1)->plaintext;
        $time = substr($str,strpos($str,'：')+strlen('：'));
        $table = $ul->find('table',0);
        $str = trim(html_entity_decode($table->find('tr',1)->find('td',1)->find('p',0)->plaintext));
        $pos = strpos($str,"\n");
        if($pos === false){
            $name = $str;
            if(!empty($table->find('tr',1)->find('td',1)->find('p',1))){
                $str = trim(html_entity_decode($table->find('tr',1)->find('td',1)->find('p',1)->plaintext));
                $pos = strpos($str,'：')+strlen('：');
                if($pos === false){
                    $pos = strpos($str,':')+strlen(':');
                    $address = substr($str,$pos);
                }else{
                    $address = substr($str,$pos);
                }
            }else{
                $address = '';
            }
            if(!empty($table->find('tr',1)->find('td',1)->find('p',2))){
                $str = trim(html_entity_decode($table->find('tr',1)->find('td',1)->find('p',2)->plaintext));
                $pos = strpos($str,'：')+strlen('：');
                if($pos === false){
                    $pos = strpos($str,':')+strlen(':');
                    $phone = substr($str,$pos);
                }else{
                    $phone = substr($str,$pos);
                }
            }else{
                $phone = '';
            }
        }else{
            $arr = explode("\n",$str);
            $name = $arr[0];
            if(isset($arr[1])){
                $pos = strrpos($arr[1],'：');
                if($pos === false){
                    $pos = strrpos($arr[1],':');
                    $address = substr($arr[1],$pos+strlen(':'));
                }else{
                    $address = substr($arr[1],$pos)+strlen('：');
                }
            }else{
                $address = '';
            }
            if(isset($arr[2])){
                $pos = strrpos($arr[2],'：');
                if($pos === false){
                    $pos = strrpos($arr[2],':');
                    $phone = substr($arr[2],$pos+strlen(':'));
                }else{
                    $phone = substr($arr[2],$pos)+strlen('：');
                }
            }else{
                $phone = '';
            }
        }
        $str = html_entity_decode($table->find('tr',2)->find('td',1)->find('p',1)->plaintext);
        $pos = strpos($str,'（');
        if($pos === false){
            $proxy = explode('，',$str);
            if(isset($proxy[1])){
                $proxy[1] = substr($proxy[1],strpos($proxy[1],'：')+strlen('：'));
            }else{
                $proxy[1] = '';
            }
        }else{
            $proxy[0] = substr($str,strpos($str,'（')+strlen('（'),strpos($str,'总台电话'));
            $proxy[1] = substr($str,strpos($str,'总台电话：')+strlen('总台电话：'),strpos($str,'）'));
        }
        $con = $table->find('tr',6)->find('td',1);
        $conLen = count($con->find('p'));
        for($i = 0; $i < $conLen; $i++){
            $cont[] = html_entity_decode($con->find('p',$i)->plaintext);
        }
        $req = $table->find('tr',9)->find('td',1);
        $reqLen = count($req->find('p'));
        for($i = 0; $i < $reqLen; $i++){
            $require[] = html_entity_decode($req->find('p',$i)->plaintext);
        }
        $fe = $table->find('tr',10)->find('td',1);
        $feLen = count($fe->find('p'));
        for($i = 0; $i < $feLen; $i++){
            $file[] = html_entity_decode($fe->find('p',$i)->plaintext);
        }
        $otr = $table->find('tr',15)->find('td',1);
        $otrLen = count($otr->find('p'));
        for($i = 0; $i < $otrLen; $i++){
            $other[] = html_entity_decode($otr->find('p',$i)->plaintext);
        }
        $this->detail[] = [
            'title'=> $ul->find('h3',0)->plaintext,
            'author'=> $author,
            'time'=> $time,
            'no'=> $table->find('tr',0)->find('td',1)->plaintext,
            'people'=>[
                'name'=> $name,
                'address'=> $address,
                'phone'=> $phone,
            ],
            'proxy'=>[
                'name'=>html_entity_decode($table->find('tr',2)->find('td',1)->find('p',0)->plaintext),
                'address'=>$proxy[0],
                'phone'=>$proxy[1],
            ],
            'name'=>html_entity_decode($table->find('tr',3)->find('td',1)->plaintext),
            'source'=>html_entity_decode($table->find('tr',4)->find('td',1)->plaintext),
            'way'=>html_entity_decode($table->find('tr',5)->find('td',1)->plaintext),
            'content'=>$cont,
            'budget'=>html_entity_decode($table->find('tr',7)->find('td',1)->plaintext),
            'policy'=>html_entity_decode($table->find('tr',8)->find('td',1)->plaintext),
            'require'=>$require,
            'file'=>$file,
            'price'=>html_entity_decode($table->find('tr',11)->find('td',1)->plaintext),
            'deadline'=>html_entity_decode($table->find('tr',12)->find('td',1)->plaintext),
            'reply'=>[
                'time'=>html_entity_decode($table->find('tr',13)->find('td',1)->find('p',0)->plaintext),
                'address'=>empty($table->find('tr',13)->find('td',1)->find('p',1)) ? '' : html_entity_decode($table->find('tr',13)->find('td',1)->find('p',1)->plaintext),
            ],
            'contact'=>[
                'name'=>html_entity_decode($table->find('tr',14)->find('td',1)->find('p',0)->plaintext),
                'phone'=>html_entity_decode($table->find('tr',14)->find('td',1)->find('p',1)->plaintext),
            ],
            'other'=>$other,
        ];
        /*if(!empty($this->link)){
            $this->get($this->link);
        }*/
    }
    function getTitle(){
        return $this->title;
    }
    function getDetail(){
        return $this->detail;
    }
    function start(){
        $this->curl->start();
    }
}