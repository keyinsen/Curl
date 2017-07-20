<?php
namespace App\Excel;
use App\Factory\Db;

/**
 * Created by PhpStorm.
 * User: 坤同
 * Date: 2017/6/16
 * Time: 15:19
 */
class BookClass extends Db
{
    function get(){
        $objReader = \PHPExcel_IOFactory::createReaderForFile('b.xls');
        $objExcel = $objReader->load('b.xls');
        $sheet = $objExcel->setActiveSheetIndex(0);
        $row = $sheet->getHighestRow();         //取得最大行数
        $field = ['A','B'];
        $str = "return [\n";
        for ($i = 2; $i <= $row; $i++){
            $code = $sheet->getCell($field[0].$i)->getValue();
            $name = $sheet->getCell($field[1].$i)->getValue();
            $str .= "\t'".$code."'=>'".$name."',\n";
        }
        $str .= "\n];";
        return $str;
    }
}