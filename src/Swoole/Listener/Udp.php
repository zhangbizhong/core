<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/11/14
 * Time: 15:58
 */

namespace Core\Swoole\Listener;


use One\Swoole\Event\UdpEvent;

class Udp extends Port
{
    use UdpEvent;
}