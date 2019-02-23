<?php
// +----------------------------------------------------------------------
// | HttpException.php
// +----------------------------------------------------------------------
// | Description: 
// +----------------------------------------------------------------------
// | Time: 2019/2/22 11:58 PM
// +----------------------------------------------------------------------
// | Author: Felix <Fzhengpei@gmail.com>
// +----------------------------------------------------------------------

namespace EsOpen\Exceptions;

use Throwable;

class HttpException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        wlog($message);

        parent::__construct($message, $code, $previous);
    }
}
