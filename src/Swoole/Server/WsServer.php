<?php
/**
 * Created by PhpStorm.
 * User: tanszhe
 * Date: 2018/8/24
 * Time: 上午11:17
 */

namespace Core\Swoole\Server;

use Core\Swoole\Event\HttpEvent;
use Core\Swoole\Event\WsEvent;
use Core\Swoole\Server;
use Core\Swoole\Session;

class WsServer extends Server
{
    use WsEvent;

    /**
     * @var Session[]
     */
    protected $session = [];

}