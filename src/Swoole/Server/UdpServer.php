<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/11/14
 * Time: 15:31
 */

namespace Core\Swoole\Server;


use One\Swoole\Event\UdpEvent;
use One\Swoole\Server;

class UdpServer extends Server
{
    use UdpEvent;

}