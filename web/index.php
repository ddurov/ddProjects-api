<?php declare(strict_types=1);

require_once "../vendor/autoload.php";

use Bramus\Router\Router;
use Core\DTO\ErrorResponse;
use Core\Exceptions\CoreExceptions;
use Core\Exceptions\ParametersException;
use Core\Exceptions\RouterException;
use Core\Tools\Other;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

$router = new Router();
$router->setNamespace("\Api\Controllers");

try {

    $router->all("/", function () {
        echo "API for ddProjects";
    });

    $router->mount("/methods", function () use ($router) {

        $router->get("/", function () {
            throw new ParametersException("method not passed");
        });

        $router->mount("/service", function () use ($router) {

            $router->get("/", function () {
                throw new ParametersException("function not passed");
            });

            $router->get("/getPinningHashDomains", "ServiceController@getPinningHashDomains");

        });

        $router->mount("/updates", function () use ($router) {

            $router->get("/", function () {
                throw new ParametersException("function not passed");
            });

            $router->post("/add", "UpdateController@add");

            $router->get("/get", "UpdateController@get");

            $router->get("/info", "UpdateController@info");

            $router->get("/infoAll", "UpdateController@infoAll");

        });

    });

    $router->set404(function () {
        throw new RouterException("current route not found for this request method");
    });

    $router->run();

} catch (CoreExceptions $coreExceptions) {

    (new ErrorResponse())->setCode($coreExceptions->getCode())->setErrorMessage($coreExceptions->getMessage())->send();

} catch (Throwable $exceptions) {

    Other::log(
        "Error: " . $exceptions->getMessage() .
        " on line: " . $exceptions->getLine() .
        " in: " . $exceptions->getFile()
    );
    (new ErrorResponse())->setErrorMessage("internal error, try later")->send();

}