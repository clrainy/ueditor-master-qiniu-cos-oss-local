<?php
/**
 * Created by PhpStorm.
 * User: Rainy
 * Date: 2019/3/14
 * Time: 14:45
 */
return [
    "upload_type" => "local", /* [qiniu|local|cos|oss] 设置上传方式 qiniu 上传到七牛云存储 ,local 上传到本地, cos 腾讯云对象存储， oss 阿里云对象存储*/

    /* 腾讯云对象存储配置 */
    "cos_regin" => "",
    "cos_secretId" => "",
    "cos_secretKey" => "",
    "cos_bucket" => "",
    "cos_url" => "",

    /* 七牛云对象存储配置 */
    "qiniu_accessKey" => "",
    "qiniu_secretKey" => "",
    "qiniu_bucket" => "",
    "qiniu_url" => "",

    /* 阿里云对象存储配置 */
    "oss_accessKeyId" => "",
    "oss_accessKeySecret" => "",
    "oss_bucket" => "",
    "oss_url" => "",
    "oss_endpoint" => ""
];