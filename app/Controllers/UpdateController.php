<?php declare(strict_types=1);

namespace Api\Controllers;

use Api\Services\UpdateService;
use Api\Singletones\Database;
use Core\Controllers\Controller;
use Core\DTO\Response;
use Core\Exceptions\EntityNotFound;
use Core\Exceptions\InvalidParameter;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\Exception\ORMException;
use UnexpectedValueException;

class UpdateController extends Controller
{
    private UpdateService $updateService;

    /**
     * @throws ORMException
     * @throws Exception
     */
    public function __construct()
    {
        $this->updateService = new UpdateService(Database::getInstance());
        parent::__construct();
    }

    /**
     * @return void
     * @throws EntityNotFound
     * @throws InvalidParameter
     */
    public function get(): void
    {
        parent::validateData(parent::$inputData["data"], [
            "product" => "required"
        ]);

        (new Response())->setResponse(
            $this->updateService->get(
                parent::$inputData["data"]["product"],
                parent::$inputData["data"]["sort"]
            )
        )->send();
    }

    /**
     * @return void
     * @throws EntityNotFound
     * @throws InvalidParameter
     * @throws UnexpectedValueException
     */
    public function getAll(): void
    {
        parent::validateData(parent::$inputData["data"], [
            "product" => "required"
        ]);

        (new Response())->setResponse(
            $this->updateService->getAll(
                parent::$inputData["data"]["product"],
                parent::$inputData["data"]["sort"]
            )
        )->send();
    }
}