<?php declare(strict_types=1);

require_once "../filesAPI/vendor/autoload.php";

use Api\DTO\Response;
use Api\Tools\Other;
use Api\Tools\selfThrows;
use Bramus\Router\Router;

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
            throw new selfThrows(["message" => "method not passed"], 500);
        });

        $router->mount("/token", function () use ($router) {

            $router->get("/", function () {
                throw new selfThrows(["message" => "function not passed"], 500);
            });

            $router->post("/create", "TokenController@create");

            $router->get("/get", "TokenController@get");

            $router->get("/check", "TokenController@check");

        });

        $router->mount("/email", function () use ($router) {

            $router->get("/", function () {
                throw new selfThrows(["message" => "function not passed"], 500);
            });

            $router->post("/createCode", "EmailController@createCode");

            $router->get("/confirmCode", "EmailController@confirmCode");

        });

        $router->mount("/user", function () use ($router) {

            $router->get("/", function () {
                throw new selfThrows(["message" => "function not passed"], 500);
            });

            $router->post("/registerAccount", "UserController@registerAccount");

            $router->get("/auth", "UserController@auth");

        });

        $router->mount("/users", function () use ($router) {

            $router->get("/", function () {
                throw new selfThrows(["message" => "function not passed"], 500);
            });

            $router->get("/get", "UsersController@get");

            $router->get("/search", "UsersController@search");

        });

        $router->mount("/service", function () use ($router) {

            $router->get("/", function () {
                throw new selfThrows(["message" => "function not passed"], 500);
            });

            $router->get("/getUpdates", "ServiceController@getUpdates");

            $router->get("/getPinningHashDomains", "ServiceController@getPinningHashDomains");

        });

    });

    $router->set404(function () {
        throw new selfThrows(["message" => "route not found (check request method)"], 404);
    });

    $router->run();

} catch (selfThrows $e) {

    die($e->getMessage());

} catch (Throwable $exceptions) {

    Other::log("Error: " . $exceptions->getMessage() . " on line: " . $exceptions->getLine() . " in: " . $exceptions->getFile());
    (new Response)->setStatus("error")->setCode(500)->setResponse(["message" => "internal error, try later"])->send();

}