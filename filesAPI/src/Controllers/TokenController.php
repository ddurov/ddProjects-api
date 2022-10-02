<?php declare(strict_types=1);

namespace Api\Controllers;

use Api\DTO\Response;
use Api\Methods\Session;
use Api\Methods\Token;
use Api\Tools\selfThrows;
use Api\Tools\Validator;
use Api\Tools\ValidatorField;
use Krugozor\Database\MySqlException;

class TokenController extends ParentController
{
    public array $inputData;


    /**
     * @return void
     * @throws MySqlException
     * @throws selfThrows
     */
    public function create(): void
    {
        (new Validator([
            (new ValidatorField(0, "tokenType", ["needInt"])),
            (new ValidatorField(1, "HTTP_AUTH_SESSION", []))
        ], $this->inputData))->validate();

        (new Session())->check($this->inputData["headers"]["HTTP_AUTH_SESSION"]);

        (new Response())->setResponse(
            ["token" =>
                (new Token())->create(
                    (int) $this->inputData["data"]["tokenType"],
                    $this->inputData["headers"]["HTTP_AUTH_SESSION"]
                )
            ]
        )->send();
    }

    /**
     * @return void
     * @throws MySqlException
     * @throws selfThrows
     */
    public function get(): void
    {
        (new Validator([
            (new ValidatorField(0, "tokenType", ["needInt"])),
            (new ValidatorField(1, "HTTP_AUTH_SESSION", []))
        ], $this->inputData))->validate();

        (new Session())->check($this->inputData["headers"]["HTTP_AUTH_SESSION"]);

        (new Response())->setResponse(
            ["token" =>
                (new Token())->get(
                    (int) $this->inputData["data"]["tokenType"],
                    $this->inputData["headers"]["HTTP_AUTH_SESSION"]
                )
            ]
        )->send();
    }

    /**
     * @return void
     * @throws MySqlException
     * @throws selfThrows
     */
    public function check(): void
    {
        (new Validator([
            (new ValidatorField(0, "token", [])),
            (new ValidatorField(1, "HTTP_AUTH_SESSION", []))
        ], $this->inputData))->validate();

        (new Session())->check($this->inputData["headers"]["HTTP_AUTH_SESSION"]);

        (new Response())->setResponse(
            ["valid" =>
                (new Token())->check(
                    $this->inputData["data"]["token"],
                    $this->inputData["headers"]["HTTP_AUTH_SESSION"]
                )
            ]
        )->send();
    }
}