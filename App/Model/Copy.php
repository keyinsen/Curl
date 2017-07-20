<?php
/**
 * Created by PhpStorm.
 * User: 坤同
 * Date: 2017/6/20
 * Time: 18:12
 */

namespace App\Model;


use Ares333\CurlMulti\AutoClone;

class Copy
{
    function get(){
        $dir = ROOT.'/static';
        $cacheDir = ROOT.'/cache';
        if (! file_exists($dir)) {
            mkdir($dir);
        }
        if (! file_exists($cacheDir)) {
            mkdir($cacheDir);
        }
        $url = array(
            'http://www.laruence.com/manual' => array(
                '/' => null
            )
        );
        $clone = new AutoClone($url, $dir);
        $clone->overwrite = true;
        $clone->getCurl()->maxThread = 3;
        $clone->getCurl()->cache['enable'] = true;
        $clone->getCurl()->cache['enableDownload'] = true;
        $clone->getCurl()->cache['dir'] = $cacheDir;
        $clone->getCurl()->cache['compress'] = true;
        $clone->start();
    }
}