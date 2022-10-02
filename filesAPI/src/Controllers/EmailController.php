<?php

namespace Api\Controllers;

use Api\DTO\Response;
use Api\Methods\Email;
use Api\Tools\selfThrows;
use Api\Tools\Validator;
use Api\Tools\ValidatorField;
use Krugozor\Database\MySqlException;

class EmailController extends ParentController
{
    public array $inputData;

    /**
     * @throws MySqlException
     * @throws selfThrows
     */
    public function createCode(): void
    {
        (new Validator([
            (new ValidatorField(0, "email", []))
        ], $this->inputData))->validate();

        (new Response())->setResponse(
            ["hash" => (new Email())->createCode($this->inputData["data"]["email"])]
        )->send();
    }

    /**
     * @return void
     * @throws MySqlException
     * @throws selfThrows
     */
    public function confirmCode(): void
    {
        (new Validator([
            (new ValidatorField(0, "code", [])),
            (new ValidatorField(0, "hash", [])),
            (new ValidatorField(0, "needRemove", ["needInt"]))
        ], $this->inputData))->validate();

        (new Response())->setResponse(
            ["valid" =>
                (new Email())->confirmCode(
                    $this->inputData["data"]["code"],
                    $this->inputData["data"]["hash"],
                    $this->inputData["data"]["needRemove"]
                )
            ]
        )->send();
    }
}