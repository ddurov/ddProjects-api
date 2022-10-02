<?php

namespace Api\Controllers;

use Api\DTO\Response;
use Api\Methods\Service;
use Api\Tools\selfThrows;
use Api\Tools\Validator;
use Api\Tools\ValidatorField;
use Krugozor\Database\MySqlException;

class ServiceController extends ParentController
{
    public array $inputData;

    /**
     * @return void
     * @throws selfThrows
     * @throws MySqlException
     */
    public function getUpdates(): void
    {
        (new Validator([
            (new ValidatorField(0, "product", [])),
            (new ValidatorField(0, "amount", []))
        ], $this->inputData))->validate();

        (new Response())->setResponse(
            (new Service())->getUpdates(
                $this->inputData["data"]["product"],
                $this->inputData["data"]["amount"],
                $this->inputData["data"]["sort"]
            )
        )->send();
    }

    /**
     * @return void
     * @throws selfThrows
     */
    public function getPinningHashDomains(): void
    {
        (new Validator([
            (new ValidatorField(0, "domains", []))
        ], $this->inputData))->validate();

        (new Response())->setResponse(
            (new Service())->getPinningHashDomains($this->inputData["data"]["domains"])
        )->send();
    }
}