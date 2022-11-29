<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/11/14
 * Time: 15:58
 */

namespace Core\Swoole\Listener;


use Core\Swoole\Event\TcpEvent;

class Tcp extends Port
{
    use TcpEvent;
}