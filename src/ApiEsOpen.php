<?php
// +----------------------------------------------------------------------
// | ApiEsOpen.php
// +----------------------------------------------------------------------
// | Description: 
// +----------------------------------------------------------------------
// | Time: 2019/2/22 11:40 PM
// +----------------------------------------------------------------------
// | Author: Felix <Fzhengpei@gmail.com>
// +----------------------------------------------------------------------

namespace EsOpen;

use EsOpen\Tools\CurlRequest;

class ApiEsOpen
{
    private $params = [];    // 请求参数
    private $config = [];    // 应用标识
    private $index = '';    // 目标索引
    private $handle = 'query';

    public function __construct($config = [])
    {
        if ( !empty($config)) {
            $this->config = $config;
        }
    }

    public function setParams($params)
    {
        if (is_array($params)) {
            if (isset($params['where'])) {
                foreach ($params['where'] as &$value) {
                    if (is_array($value) && $value[0] === 'like') {
                        $value[1] = str_replace('%', '*', $value[1]);
                    }
                }
            }
            $this->params = $params;
        }
    }

    public function setIndex($index)
    {
        if (is_string($index)) $this->index = $index;
    }

    public function setHandle($handle)
    {
        if (is_string($handle)) $this->handle = $handle;
    }

    /**
     * 获取参数
     */
    public function execute()
    {
        if (empty($this->params)) return ['status' => false, 'error_message' => 'params not define'];
        if (empty($this->config)) return ['status' => false, 'error_message' => 'config not define'];
        if (empty($this->index)) return ['status' => false, 'error_message' => 'index not define'];
        if (empty($this->handle)) return ['status' => false, 'error_message' => 'handle not define'];
        $curlUrl      = $this->config['ES_OPENAPI_URL'] . '/' . $this->handle . '/' . $this->index;
        $addParam     = ['docker' => $this->config['docker']];
        $this->params = array_merge($addParam, $this->params);
        wlog("start-----", "es-openapi.log");
        try {
            $curl = new CurlRequest($curlUrl, $this->params);
            $ret  = $curl->post();

        } catch (\Exception $e) {
            return ['status' => false, 'err_msg' => $e->getMessage()];
        }
        if ($this->params['size'] >= 10000) {
            wlog("请求网关：" . $curlUrl . "\n请求参数：" . json_encode($this->params, JSON_UNESCAPED_UNICODE) . "\n返回参数：success", "es-openapi.log");
        } else {
            wlog("请求网关：" . $curlUrl . "\n请求参数：" . json_encode($this->params, JSON_UNESCAPED_UNICODE) . "\n返回参数：" . $ret, "es-openapi.log");
        }
        return json_decode($ret, true);
    }
    
}
