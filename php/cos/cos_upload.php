<?php
/**
 * Created by PhpStorm.
 * User: Rainy
 * Date: 2019/3/14
 * Time: 10:21
 */
require 'vendor/autoload.php';
include "CosUploader.class.php";

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
$up = new CosUploader($fieldName, $config, $base64);
$info = $up->getFileInfo();
### 实例化腾讯云
$cosClient = new Qcloud\Cos\Client(array(
    'region' => $ex_config['cos_regin'],
    'credentials' => array(
        'secretId' => $ex_config['cos_secretId'],
        'secretKey' => $ex_config['cos_secretKey'],
    )
));

### 上传文件流
try {
    $result = $cosClient->putObject(array(
        'Bucket' => $ex_config['cos_bucket'],
        'Key' => $info['title'],
        'Body' => fopen($_FILES[$fieldName]['tmp_name'], 'rb')
    ));

    $info['state'] = 'SUCCESS';
    $info['url'] = $ex_config['cos_url'].$info['title'];
//    print_r($result);
    # 可以直接通过$result读出返回结果
//    echo ($result['ETag']);
} catch ( \Exception $e ) {
    echo($e);
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