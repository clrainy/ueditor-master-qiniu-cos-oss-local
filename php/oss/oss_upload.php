<?php
/**
 * Created by PhpStorm.
 * User: Rainy
 * Date: 2019/3/14
 * Time: 16:48
 */
use OSS\OssClient;
use OSS\Core\OssException;
require 'autoload.php';
include "OssUploader.class.php";

/* 上传配置 */
$base64 = "upload";
switch (htmlspecialchars($_GET['action'])) {
    case 'uploadimage':
        $config = array(
            "pathFormat" => $CONFIG['imagePathFormat'],
            "maxSize" => $CONFIG['imageMaxSize'],
            "allowFiles" => $CONFIG['imageAllowFiles']
        );
        $fieldName = $CONFIG['imageFieldName'];
        break;
    case 'uploadscrawl':
        $config = array(
            "pathFormat" => $CONFIG['scrawlPathFormat'],
            "maxSize" => $CONFIG['scrawlMaxSize'],
            "allowFiles" => $CONFIG['scrawlAllowFiles'],
            "oriName" => "scrawl.png"
        );
        $fieldName = $CONFIG['scrawlFieldName'];
        $base64 = "base64";
        break;
    case 'uploadvideo':
        $config = array(
            "pathFormat" => $CONFIG['videoPathFormat'],
            "maxSize" => $CONFIG['videoMaxSize'],
            "allowFiles" => $CONFIG['videoAllowFiles']
        );
        $fieldName = $CONFIG['videoFieldName'];
        break;
    case 'uploadfile':
    default:
        $config = array(
            "pathFormat" => $CONFIG['filePathFormat'],
            "maxSize" => $CONFIG['fileMaxSize'],
            "allowFiles" => $CONFIG['fileAllowFiles']
        );
        $fieldName = $CONFIG['fileFieldName'];
        break;
}

/* 生成上传实例对象 */
$up = new OssUploader($fieldName, $config, $base64);
$info = $up->getFileInfo();

// 文件名称
$object = $info['title'];
// <yourLocalFile>由本地文件路径加文件名包括后缀组成，例如/users/local/myfile.txt
$filePath = $_FILES[$fieldName]['tmp_name'];

try{
    $ossClient = new OssClient($ex_config['oss_accessKeyId'], $ex_config['oss_accessKeySecret'], $ex_config['oss_endpoint']);

    $ossClient->uploadFile($ex_config['oss_bucket'], $object, $filePath);
    $info['state'] = 'SUCCESS';
    $info['url'] = $ex_config['oss_url'].$info['title'];
//    $info['url'] = $ossClient->signUrl($ex_config['oss_bucket'],$object,'7200');/*私有访问  签名*/
} catch(OssException $e) {
    printf(__FUNCTION__ . ": FAILED\n");
    printf($e->getMessage() . "\n");
    return;
}


/**
 * 得到上传文件所对应的各个参数,数组结构
 * array(
 *     "state" => "",          //上传状态，上传成功时必须返回"SUCCESS"
 *     "url" => "",            //返回的地址
 *     "title" => "",          //新文件名
 *     "original" => "",       //原始文件名
 *     "type" => ""            //文件类型
 *     "size" => "",           //文件大小
 * )
 */
return json_encode($info);