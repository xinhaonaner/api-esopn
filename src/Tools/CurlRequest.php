<?php
// +----------------------------------------------------------------------
// | CurlRequest.php
// +----------------------------------------------------------------------
// | Description: 
// +----------------------------------------------------------------------
// | Time: 2019/2/22 11:55 PM
// +----------------------------------------------------------------------
// | Author: Felix <Fzhengpei@gmail.com>
// +----------------------------------------------------------------------

namespace EsOpen\Tools;

use EsOpen\Exceptions\HttpException;

class CurlRequest
{
    private $url;

    private $params;

    public function __construct($url, $params)
    {
        $this->url    = $url;
        $this->params = $this->buildQuery($params);
    }

    /**
     * @function
     * @param $url
     * @param null $postFields
     * @return mixed|null
     * @throws \Exception
     */
    public function post()
    {
        $reponse = NULL;
        $headers = array('Content-Type: application/x-www-form-urlencoded;charset=UTF-8;');
        $ch      = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->params);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $reponse = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception('CURL请求异常:' . curl_error($ch), 0);
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 !== $httpStatusCode) {
                throw new HttpException('HTTP服务器返回状态异常 [状态码 ' . $httpStatusCode . '] :' . $reponse);
            }
        }
        curl_close($ch);
        $ch = null;
        return $reponse;
    }

    /**
     * 拼装参数[编码]
     * @param $data
     * @param bool $urlEncode 是否编码
     * @return string
     */
    private function buildQuery($data, $urlEncode = FALSE)
    {
        if (is_array($data)) {
            $data = http_build_query($data);
            if ( !$urlEncode) {
                return urldecode($data);
            }
        }
        return $data;
    }
}
