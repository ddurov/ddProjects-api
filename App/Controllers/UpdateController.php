<?php declare(strict_types=1);

namespace Api\Controllers;

use Api\Services\UpdateService;
use Api\Singletones\Database;
use Core\Controllers\Controller;
use Core\DTO\Response;
use Core\Exceptions\EntityNotFound;
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
     * @throws EntityNotFound
     */
    public function get(): void
    {
        $validation = (new Validator())->validate($this->inputData["data"], [
            "product" => "required"
        ]);

        if (isset($validation->errors->all()[0]))
            (new Response())->setStatus("error")->setCode(400)->setResponse(["message" => $validation->errors->all()[0]])->send();

        (new Response())->setResponse(
            (new UpdateService(Database::getInstance()))->get(
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
     * @throws EntityNotFound
     */
    public function getAll(): void
    {
        $validation = (new Validator())->validate($this->inputData["data"], [
            "product" => "required"
        ]);

        if (isset($validation->errors->all()[0]))
            (new Response())->setStatus("error")->setCode(400)->setResponse(["message" => $validation->errors->all()[0]])->send();

        (new Response())->setResponse(
            (new UpdateService(Database::getInstance()))->getAll(
                $this->inputData["data"]["product"],
                $this->inputData["data"]["sort"]
            )
        )->send();
    }
}