<?php

namespace Api\Controllers;

use Api\DTO\Response;
use Api\Methods\Session;
use Api\Methods\Token;
use Api\Methods\Users;
use Api\Tools\selfThrows;
use Api\Tools\Validator;
use Api\Tools\ValidatorField;
use Krugozor\Database\MySqlException;

class UsersController extends ParentController
{
    public array $inputData;

    /**
     * @return void
     * @throws selfThrows
     * @throws MySqlException
     */
    public function get(): void
    {
        (new Validator([
            (new ValidatorField(0, "id", ["needInt"])),
            (new ValidatorField(0, "token", [])),
            (new ValidatorField(1, "HTTP_AUTH_SESSION", []))
        ], $this->inputData))->validate();

        (new Session())->check($this->inputData["headers"]["HTTP_AUTH_SESSION"]);
        (new Token())->check($this->inputData["data"]["token"], $this->inputData["headers"]["HTTP_AUTH_SESSION"]);

        (new Response())->setResponse(
            (new Users())->get($this->inputData["data"]["id"], $this->inputData["data"]["token"])
        )->send();
    }

    /**
     * @return void
     * @throws MySqlException
     * @throws selfThrows
     */
    public function search(): void
    {
        (new Validator([
            (new ValidatorField(0, "query", [])),
            (new ValidatorField(1, "HTTP_AUTH_SESSION", []))
        ], $this->inputData))->validate();

        (new Session())->check($this->inputData["headers"]["HTTP_AUTH_SESSION"]);

        (new Response())->setResponse(
            (new Users())->search($this->inputData["data"]["query"])
        )->send();
    }
}