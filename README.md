# SLS for ThinkPHP Log Driver
# ThinkPHP 阿里云日志服务驱动

> 注意：当前只测试了 thinkphp 5.0 环境下的使用！！！

## 安装

```shell
$ composer require hongfs/thinkphp-log-sls:dev-main
```

## 使用

关于服务创建的操作可见阿里云官方有关文档，其中索引可在初步请求后再阿里云控制台手动操作自动生成索引。

`application\config.php`

```php
return [
    'log'                    => [
        'type'  => '\\Hongfs\\Think\\Log\\Driver\\Sls', // 日志记录方式，采用 SLS
        'level' => [], // 日志记录级别 https://kancloud.cn/manual/thinkphp5/118127

        'access_key_id'     => 'LTAI5t8Cb3YjYwgSY3YKbZ',        // ACCESS_KEY_ID
        'access_key_secret' => 'T6c2ZbLURX33b4jbUOfivX704Rxu7', // ACCESS_KEY_SECRET
        'endpoint'          => 'cn-shenzhen.log.aliyuncs.com',  // 地域节点，尽可能使用内网
        'project'           => 'thinkphp-log-test',             // 日志项目名称
        'log_store'         => 'test',                          // 日志 Logstore
        'topic'             => '',                              // 日志主题
        'source'            => '',                              // 日志的来源
    ],
];
```

## 其他

1. thinkphp 的日志不是按照插入顺序的，会对 level 进行一个分割，这个需要注意。

## License

MIT
