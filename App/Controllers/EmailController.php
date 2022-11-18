<?php

namespace Api\Controllers;

use Api\Configs;
use Core\Controllers\Controller;
use Core\DTO\Response;
use Core\Exceptions\InvalidParameter;
use Api\Services\EmailService;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Rakit\Validation\Validator;

class EmailController extends Controller
{
    public array $inputData;


    /**
     * @return void
     * @throws \Exception
     */
    public function createCode(): void
    {
        $validation = (new Validator())->validate($this->inputData["data"], [
            "email" => "required|email"
        ]);

        if (isset($validation->errors->all()[0]))
            (new Response())->setStatus("error")->setCode(400)->setResponse(["message" => $validation->errors->all()[0]])->send();

        (new Response())->setResponse(
            ["hash" => (new EmailService(Configs::getInstance()["masterDatabase"], Configs::getInstance()["mail"]))->createCode($this->inputData["data"]["email"])]
        )->send();
    }

    /**
     * @return void
     * @throws InvalidParameter
     * @throws ORMException
     * @throws ORMInvalidArgumentException
     * @throws OptimisticLockException
     * @throws Exception
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function confirmCode(): void
    {
        $validation = (new Validator())->validate($this->inputData["data"], [
            "code" => "required",
            "hash" => "required",
            "needRemove" => "required|numeric"
        ]);

        if (isset($validation->errors->all()[0]))
            (new Response())->setStatus("error")->setCode(400)->setResponse(["message" => $validation->errors->all()[0]])->send();

        (new Response())->setResponse(
            ["valid" =>
                (new EmailService(Configs::getInstance()["masterDatabase"], Configs::getInstance()["mail"]))->confirmCode(
                    $this->inputData["data"]["code"],
                    $this->inputData["data"]["hash"],
                    $this->inputData["data"]["needRemove"]
                )
            ]
        )->send();
    }
}