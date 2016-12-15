<?php
return [
    'secret' => [
        'pwd' => 'asdf*sad12',//生成密码的秘钥
        'token' => 'asdf*sadasd%.2asd',//获取token的秘钥
    ],
    'default'=>[
    	'page_size'=>10,//默认每页显示的数量
    	'upload_max_size'=>1000240,//默认上传文件的最大大小
        'Upload_server_url'=>"http://qrck-assets.mez100.cn:8031//uploadsfile.php",//远程上服务器地址
        'Upload_server_ip'=>"qrck-assets.mez100.cn:8031/",//远程上服务器IP
        'Upload_type' => 'jpg,png,zip', //上传文件类型
        'Upload_temp_url' => $_SERVER[ 'DOCUMENT_ROOT' ],
    ]
];
