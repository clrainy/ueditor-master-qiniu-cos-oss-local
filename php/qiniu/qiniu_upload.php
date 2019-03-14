<?php
/**
 * Created by PhpStorm.
 * User: Rainy
 * Date: 2019/3/14
 * Time: 16:48
 */

// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;

require 'autoload.php';
include "QiniuUploader.class.php";

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
$up = new QiniuUploader($fieldName, $config, $base64);
$info = $up->getFileInfo();

/*实例化七牛云 鉴权*/
$auth = new Auth($ex_config['qiniu_accessKey'], $ex_config['qiniu_secretKey']);
$token = $auth->uploadToken($ex_config['qiniu_bucket']);
$filePath = $_FILES[$fieldName]['tmp_name'];
$key = $info['title'];

/*实例化七牛云 上传*/
$uploadMgr = new UploadManager();
// 调用 UploadManager 的 putFile 方法进行文件的上传。
list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);

if ($err !== null) {
    var_dump($err);
} else {
//    var_dump($ret);
    $info['state'] = 'SUCCESS';
    $info['url'] = $ex_config['qiniu_url'].$info['title'];
//    $info['url'] = $auth->privateDownloadUrl($ex_config['qiniu_url'].$info['title']);/*私有存储 链接加密*/
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