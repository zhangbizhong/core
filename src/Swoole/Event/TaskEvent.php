<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/11/14
 * Time: 15:32
 */

namespace Core\Swoole\Event;

trait TaskEvent
{
    public function onTask(\swoole_server $server, $task_id, $src_worker_id, $data)
    {

    }

    public function onFinish(\swoole_server $server, $task_id, $data)
    {

    }

}