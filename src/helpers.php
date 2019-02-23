<?php
/**
 * Created by PhpStorm.
 * User: Felix
 * Date: 2019/2/22 11:49 PM
 */

if ( !function_exists('wlog')) {
    /**
     * @function 日志
     * @param $info
     * @param string $fileName
     * @param int $flags
     * @param string $type
     * @param string $pathName
     * @param bool $isShowTime
     */
    function wlog($info, $fileName = "es-openapi.log", $flags = FILE_APPEND, $type = 'serialize', $pathName = 'Log', $isShowTime = true)
    {
        //组合路径
        $path = $_SERVER['DOCUMENT_ROOT'] . "/../runtime/logs/es/" . date('Ymd') . '/';
        //判断目录是否存在
        if ( !is_dir($path)) mkdir($path, 0777, true);
        //判断是否是数组
        if (is_array($info)) {
            $info = $type($info);
        }
        //打印文本
        if ($isShowTime) {
            file_put_contents($path . '/' . $fileName, '[' . date(DATE_ISO8601) . '][info] ' . $info . "\n", $flags);
        } else {
            file_put_contents($path . '/' . $fileName, $info, $flags);
        }
    }
}
