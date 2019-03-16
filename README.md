# ueditor-master-qiniu-cos-oss-local

###
基于百度的ueditor,封装了主流云对象存储。

目录php下的extendConfig.php，可进行对ueditor的对象存储的选择，及相关云对象存储的参数配置。

## 腾讯云的对象存储（cos）
目录cos下的cos_upload.php中,默认没有对私有存储的 对象链接进行加密输出。
可自行在对象链接 输出时，调用对应的方法进行加密。

## 阿里云的对象存储（oss）
目录oss下的oss_upload.php中,默认没有对私有存储的 对象链接进行加密输出。
可自行在对象链接 输出时，调用对应的方法进行加密。

## 七牛云的对象存储（qiniu）
目录qiniu下的qiniu_upload.php中,默认没有对私有存储的 对象链接进行加密输出。
可自行在对象链接 输出时，调用对应的方法进行加密。
## 本地的对象存储（local）
###