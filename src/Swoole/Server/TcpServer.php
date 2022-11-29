<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/11/14
 * Time: 15:30
 */

namespace Core\Swoole\Server;


use Core\Swoole\Event\TcpEvent;
use Core\Swoole\Server;

class TcpServer extends Server
{
    use TcpEvent;
}