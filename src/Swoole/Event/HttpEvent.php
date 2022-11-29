<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/11/14
 * Time: 15:32
 */

namespace Core\Swoole\Event;

use One\Database\Mysql\DbException;
use One\Exceptions\Handler;
use One\Exceptions\HttpException;
use One\Facades\Log;
use One\Http\Router;
use One\Http\RouterException;
use One\Swoole\Server;

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
        $req   = new \One\Swoole\Request($request);
        $res   = new \One\Swoole\Response($req, $response);
        try {
            $router = new Router();
            $server = $this instanceof Server ? $this : $this->server;
            list($req->class, $req->func, $mids, $action, $req->args, $req->as_name) = $router->explain($req->method(), $req->uri(), $req, $res, $server);
            $f    = $router->getExecAction($mids, $action, $res, $server);
            $data = $f();
        } catch (\One\Exceptions\HttpException $e) {
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