<?php

namespace Hongfs\Think\Log\Driver;

use Ramsey\Uuid\Uuid;
use Aliyun\SLS\Client;
use Aliyun\SLS\Requests\PutLogsRequest;
use Aliyun\SLS\Models\LogItem;

/**
 * 阿里云日志服务驱动
 */
class Sls
{
    /**
     * 配置
     *
     * @return array
     */
    protected $config = [
        'access_key_id'     => '',
        'access_key_secret' => '',
        'endpoint'          => '',
        'project'           => '',
        'log_store'         => '',
        'topic'             => '',
        'source'            => '',
    ];

    /**
     * SKS Client
     *
     * @return \Aliyun\SLS\Client
     */
    protected $client;

    /**
     * 请求 UUID
     *
     * @return string
     */
    protected $request_id;

    // 实例化并传入参数
    public function __construct($config = [])
    {
        if(is_array($config)) {
            $this->config = array_merge($this->config, $config);
        }

        $this->client = new Client($this->config['endpoint'], $this->config['access_key_id'], $this->config['access_key_secret']);

        $this->request_id = Uuid::uuid4()->toString();
    }

    /**
     * 日志写入接口
     * @access public
     * @param array $log 日志信息
     * @param  bool $append 是否追加请求信息
     * @return bool
     */
    public function save(array $log = [], $append = false)
    {
        if(!function_exists('request')) {
            return false;
        }

        $logs = [];

        if($append) {
            $logs[] = new LogItem([
                'level'         => 'info',
                'request_id'    => $this->request_id,
                'url'           => request()->url(true),
                'ip'            => isset($_SERVER['HTTP_REMOTEIP']) ? $_SERVER['HTTP_REMOTEIP'] : request()->ip(),
                'server_name'   => php_uname('n'),
            ]);
        }

        foreach($log as $level => $items) {
            foreach($items as $item) {
                $content = $item;

                if(!is_string($content)) {
                    $content = var_export($content, true);
                }

                $logs[] = new LogItem([
                    'level'         => $level,
                    'request_id'    => $this->request_id,
                    'message'       => $content,
                ]);
            }
        }

        $put = new PutLogsRequest($this->config['project'], $this->config['log_store'], $this->config['topic'], $this->config['source'], $logs);

        try {
            $this->client->putLogs($put);
        } catch (\Exception $e) {
            throw $e;
        }

        return true;
    }
}