<?php declare(strict_types=1);

require_once "../vendor/autoload.php";

use Bramus\Router\Router;
use Core\DTO\Response;
use Core\Exceptions\CoreExceptions;
use Core\Exceptions\FunctionNotPassed;
use Core\Exceptions\RouteNotFound;
use Core\Tools\Other;

header('Access-Control-Allow-Origin: *');

$router = new Router();
$router->setNamespace("\Api\Controllers");

try {

    $router->all("/", function () {
        echo "API for ddProjects";
    });

    $router->mount("/methods", function () use ($router) {

        header('Content-Type: application/json; charset=utf-8');

        $router->get("/", function () {
            throw new FunctionNotPassed("method not passed");
        });

        $router->mount("/updates", function () use ($router) {

            $router->get("/", function () {
                throw new FunctionNotPassed("function not passed");
            });

            $router->get("/get", "UpdateController@get");

            $router->get("/getAll", "UpdateController@getAll");

        });

    });

    $router->mount("/utils", function () use ($router) {

        $router->get("/", function () {
            throw new FunctionNotPassed("function not passed");
        });

        $router->get("/getPinningHashDomains", "UtilController@getPinningHashDomains");

    });

    $router->set404(function () {
        throw new RouteNotFound();
    });

    $router->run();

} catch (CoreExceptions $coreExceptions) {

    (new Response)->setStatus("error")->setCode($coreExceptions->getCode())->setResponse(["message" => $coreExceptions->getMessage()])->send();

} catch (Throwable $exceptions) {

    Other::log("Error: " . $exceptions->getMessage() . " on line: " . $exceptions->getLine() . " in: " . $exceptions->getFile());
    (new Response())->setStatus("error")->setCode(500)->setResponse(["message" => "internal error, try later"])->send();

}