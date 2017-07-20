<?php
/**
 * Created by PhpStorm.
 * User: 坤同
 * Date: 2017/6/6
 * Time: 10:06
 */

namespace App\Model;


use Curl\Request;

class Popular
{
    function get(){
        $url = 'http://static.funshion.com/market/p2p/openplatform/master/2017-3-28/FunVodPlayer.swf';
        $param = [
            'type'=>'movie',
            'videoid'=>860045,
            'next'=>0,
            'startAd'=>1,
            'stoppage'=>1,
            'funshionSetup'=>0,
            'partner'=>1024,
            'userMac'=>null,
            'gtype'=>1,
            'channelid'=>null,
            'galleryid'=>306745,
            'mediaAp'=>'c_wb',
            'pauseAp'>'c_wps',
            'h5'=>1,
            'vjjkey'=>'V1b7myrXW',
            'vjjmedia'=>1,
            'showStop'=>1,
            'historytime'=>0,
            'vodnum'=>'video-player-1496728294857_0',
            'time'=>40,
            'uuid'=>'1D01863A-6DB2-FBFF-34B4-B4C242A49A14',
        ];
        $url = $url.'?'.http_build_query($param);
        $curl = new Request($url,[CURLOPT_ENCODING=>'gzip']);
        file_put_contents('a.mp4',$curl->exec()->getBody());
        return /*$curl->exec()->getHeader()*/;
    }
}