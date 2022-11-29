<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/11/14
 * Time: 15:32
 */

namespace Core\Swoole\Event;

use Core\Database\Mysql\DbException;
use Core\Exceptions\Handler;
use Core\Exceptions\HttpException;
use Core\Facades\Log;
use Core\Http\Router;
use Core\Http\RouterException;
use Core\Swoole\Server;

trait HttpEvent
{
    public function onRequest(\swoole_http_request $request, \swoole_http_response $response)
    {

    }

    /**
     * @param \swoole_http_request $request
     * @param \swoole_http_response $response
     */
    protected function httpRouter(\swoole_http_request $request, \swoole_http_response $response)
    {
        $req   = new \Core\Swoole\Request($request);
        $res   = new \Core\Swoole\Response($req, $response);
        try {
            $router = new Router();
            $server = $this instanceof Server ? $this : $this->server;
            list($req->class, $req->func, $mids, $action, $req->args, $req->as_name) = $router->explain($req->method(), $req->uri(), $req, $res, $server);
            $f    = $router->getExecAction($mids, $action, $res, $server);
            $data = $f();
        } catch (\Core\Exceptions\HttpException $e) {
            $data = Handler::render($e);
        } catch (\Throwable $e) {
            error_report($e);
            $msg = $e->getMessage();
            if ($e instanceof DbException) {
                $msg = 'db error!';
            }
            $data = Handler::render(new HttpException($res, $msg, $e->getCode()));
        }
        $response->end($data);

    }
}