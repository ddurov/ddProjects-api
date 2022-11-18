<?php

namespace Api\Controllers;

use Api\Configs;
use Api\Services\UpdateService;
use Core\Controllers\Controller;
use Core\DTO\Response;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\Exception\ORMException;
use Rakit\Validation\Validator;
use UnexpectedValueException;

class UpdateController extends Controller
{
    public array $inputData;

    /**
     * @return void
     * @throws Exception
     * @throws ORMException
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function get(): void
    {
        $validation = (new Validator())->validate($this->inputData["data"], [
            "product" => "required"
        ]);

        if (isset($validation->errors->all()[0]))
            (new Response())->setStatus("error")->setCode(400)->setResponse(["message" => $validation->errors->all()[0]])->send();

        (new Response())->setResponse(
            (new UpdateService(Configs::getInstance()["masterDatabase"]))->get(
                $this->inputData["data"]["product"],
                $this->inputData["data"]["sort"]
            )
        )->send();
    }

    /**
     * @return void
     * @throws Exception
     * @throws ORMException
     * @throws UnexpectedValueException
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function getAll(): void
    {
        $validation = (new Validator())->validate($this->inputData["data"], [
            "product" => "required"
        ]);

        if (isset($validation->errors->all()[0]))
            (new Response())->setStatus("error")->setCode(400)->setResponse(["message" => $validation->errors->all()[0]])->send();

        (new Response())->setResponse(
            (new UpdateService(Configs::getInstance()["masterDatabase"]))->getAll(
                $this->inputData["data"]["product"],
                $this->inputData["data"]["sort"]
            )
        )->send();
    }
}