<?php

namespace Api\Controllers;

use Api\DTO\Response;
use Api\Methods\User;
use Api\Tools\selfThrows;
use Api\Tools\Validator;
use Api\Tools\ValidatorField;
use Krugozor\Database\MySqlException;

class UserController extends ParentController
{
    public array $inputData;

    /**
     * @return void
     * @throws selfThrows
     * @throws MySqlException
     */
    public function registerAccount(): void
    {
        (new Validator([
            (new ValidatorField(0, "login", ["minLength" => 6, "maxLength" => 20, "regexp" => "/\w+/"])),
            (new ValidatorField(0, "password", ["minLength" => 8, "regexp" => "/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/"])),
            (new ValidatorField(0, "email", ["regexp" => "/([\w\-\.]+)@([\w\-\.]+)/"]))
        ], $this->inputData))->validate();

        (new Response())->setResponse(
            (new User())->registerAccount(
                $this->inputData["data"]["login"],
                $this->inputData["data"]["password"],
                $this->inputData["data"]["username"] ?? null,
                $this->inputData["data"]["email"],
                $this->inputData["data"]["emailCode"] ?? null,
                $this->inputData["data"]["hash"] ?? null
            )
        )->send();
    }

    /**
     * @return void
     * @throws MySqlException
     * @throws selfThrows
     */
    public function auth(): void
    {
        (new Validator([
            (new ValidatorField(0, "login", ["minLength" => 6, "maxLength" => 20, "regexp" => "/\w+/"])),
            (new ValidatorField(0, "password", ["minLength" => 8, "regexp" => "/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/"]))
        ], $this->inputData))->validate();

        (new Response())->setResponse(
            (new User())->auth(
                $this->inputData["data"]["login"],
                $this->inputData["data"]["password"]
            )
        )->send();
    }
}