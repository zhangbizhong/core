<?php
/**
 * Created by PhpStorm.
 * User: tanszhe
 * Date: 2018/8/24
 * Time: 上午11:16
 */

namespace Core\Swoole\Server;


use Core\Swoole\Event\HttpEvent;
use Core\Swoole\Server;

class HttpServer extends Server
{
    use HttpEvent;
}