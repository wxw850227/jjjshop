<?php

namespace app\common\library;

/**
 * 工具类
 */
class helper
{
    /**
     * 获取数组中指定的列
     */
    public static function getArrayColumn($source, $column)
    {
        $columnArr = [];
        foreach ($source as $item) {
            $columnArr[] = $item[$column];
        }
        return $columnArr;
    }

    /**
     * 获取数组中指定的列
     */
    public static function getArrayColumns($source, $columns)
    {
        $columnArr = [];
        foreach ($source as $item) {
            $temp = [];
            foreach ($columns as $index) {
                $temp[$index] = $item[$index];
            }
            $columnArr[] = $temp;
        }
        return $columnArr;
    }

    /**
     * 获取数组中指定的列中的id
     */
    public static function getArrayColumnIds($source)
    {
        $orderId_arr = [];
        foreach ($source as $item){
            array_push($orderId_arr, $item);
        }
        return $orderId_arr;
    }
    /**
     * 把二维数组中某列设置为key返回
     */
    public static function arrayColumn2Key($source, $index)
    {
        $data = [];
        foreach ($source as $item) {
            $data[$item[$index]] = $item;
        }
        return $data;
    }

    public static function number2($number, $isMinimum = false, $minimum = 0.01)
    {
        $isMinimum && $number = max($minimum, $number);
        return sprintf('%.2f', $number);
    }

    public static function getArrayItemByColumn($array, $column, $value)
    {
        foreach ($array as $item) {
            if ($item[$column] == $value) {
                return $item;
            }
        }
        return false;
    }

    public static function getArrayColumnSum($array, $column)
    {
        $sum = 0;
        foreach ($array as $item) {
            $sum += $item[$column] * 100;
        }
        return $sum / 100;
    }

    public static function setDataAttribute(&$source, $defaultData, $isArray = false)
    {
        if (!$isArray) $dataSource = [&$source]; else $dataSource = &$source;
        foreach ($dataSource as &$item) {
            foreach ($defaultData as $key => $value) {
                $item[$key] = $value;
            }
        }

        return $source;
    }

    public static function bcsub($leftOperand, $rightOperand, $scale = 2)
    {
        return \bcsub($leftOperand, $rightOperand, $scale);
    }

    public static function bcadd($leftOperand, $rightOperand, $scale = 2)
    {
        return \bcadd($leftOperand, $rightOperand, $scale);
    }

    public static function bcmul($leftOperand, $rightOperand, $scale = 2)
    {
        return \bcmul($leftOperand, $rightOperand, $scale);
    }

    public static function bcdiv($leftOperand, $rightOperand, $scale = 2)
    {
        return \bcdiv($leftOperand, $rightOperand, $scale);
    }

    public static function bccomp($leftOperand, $rightOperand, $scale = 2)
    {
        return \bccomp($leftOperand, $rightOperand, $scale);
    }

    public static function bcequal($leftOperand, $rightOperand, $scale = 2)
    {
        return self::bccomp($leftOperand, $rightOperand, $scale) === 0;
    }

    /**
     * 数组转义为json
     */
    public static function jsonEncode($data)
    {
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    /**
     * json转义为数组
     */
    public static function jsonDecode($json)
    {
        return json_decode($json, true);
    }
}