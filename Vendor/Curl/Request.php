<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2017/1/23
 * Time: 上午11:17
 */

namespace Curl;


class Request
{
    protected $curlOPt = array(
        CURLOPT_CONNECTTIMEOUT=>3,
        CURLOPT_TIMEOUT=>10,
        CURLOPT_AUTOREFERER=>true,
        CURLOPT_USERAGENT=>"Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET4.0C; .NET4.0E)",
        CURLOPT_FOLLOWLOCATION=>true,
        CURLOPT_RETURNTRANSFER=>true,
        CURLOPT_SSL_VERIFYPEER=>false,
        CURLOPT_SSL_VERIFYHOST=>false,
        CURLOPT_HEADER=>true,
    );
    function __construct($url,array $opt = array())
    {
        $this->curlOPt[CURLOPT_URL] = $url;
        if(!empty($opt)){
            $this->curlOPt = $opt + $this->curlOPt;
        }
    }
    function post(array $data){
        $this->curlOPt[CURLOPT_POST] =  true;
        $this->curlOPt[CURLOPT_POSTFIELDS] = $data;
        return $this;
    }
    function addFile($key,$filePath){
        $this->curlOPt[CURLOPT_POST] =  true;
        $this->curlOPt[CURLOPT_POSTFIELDS] =  array(
            $key=>new \CURLFile($filePath),
        );
        return $this;
    }
    function setOpt(array $opt){
        $this->curlOPt = $this->curlOPt + $opt;
        return $this;
    }
    function setUrl($url){
        $this->curlOPt[CURLOPT_URL] = $url;
        return $this;
    }
    function getOpt(){
        return $this->curlOPt;
    }

    function exec($autoDebug = 1){
        $curl = curl_init();
        curl_setopt_array($curl,$this->getOpt());
        $result = curl_exec($curl);
        if($autoDebug){
            $info = curl_getinfo($curl);
            $curl_error = curl_error($curl);
            $curl_errorNo = curl_errno($curl);
        }else{
            $info = null;
            $curl_error = null;
            $curl_errorNo = null;
        }
        curl_close($curl);
        return new Response($result,$info,$curl_error,$curl_errorNo);
    }
}