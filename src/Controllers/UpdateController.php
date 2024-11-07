<?php declare(strict_types=1);

namespace Api\Controllers;

use Api\Services\UpdateService;
use Core\Controller;
use Core\Database;
use Core\Exceptions\EntityException;
use Core\Exceptions\InternalError;
use Core\Exceptions\ParametersException;
use Core\Exceptions\PermissionException;
use Core\Models\SuccessResponse;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use JetBrains\PhpStorm\NoReturn;

class UpdateController extends Controller
{
	private UpdateService $updateService;


	/**
	 * @throws MissingMappingDriverImplementation
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->updateService = new UpdateService(Database::getInstance()->getEntityManager());
		parent::__construct();
	}

	/**
	 * @return void
	 * @throws EntityException
	 * @throws InternalError
	 * @throws ORMException
	 * @throws ParametersException
	 * @throws PermissionException
	 * @throws OptimisticLockException
	 */
	public function add(): void
	{
		parent::validateData(parent::$inputData["data"] + $_FILES, [
			"file" => "required|uploaded_file:0,100M,apk",
			"product" => "required|in:messenger,syncer",
			"versionName" => "required",
			"versionCode" => "required",
			"description" => "required",
			"uploadToken" => "required"
		]);

		if (parent::$inputData["data"]["uploadToken"] !== getenv("UPLOAD_TOKEN"))
			throw new PermissionException("access to this method only for administrators");

		parent::sendResponse(new SuccessResponse(
			$this->updateService->add(
				$_FILES,
				parent::$inputData["data"]["product"],
				strval(parent::$inputData["data"]["versionName"]),
				parent::$inputData["data"]["versionCode"],
				parent::$inputData["data"]["description"]
			)
		));
	}

	/**
	 * @return void
	 * @throws EntityException
	 * @throws ParametersException
	 */
	public function get(): void
	{
		parent::validateData(parent::$inputData["data"], [
			"product" => "required",
			"versionName" => "required"
		]);

		$this->updateService->get(
			parent::$inputData["data"]["product"],
			strval(parent::$inputData["data"]["versionName"])
		);
	}

	/**
	 * @return void
	 * @throws EntityException
	 * @throws ParametersException
	 */
	#[NoReturn] public function info(): void
	{
		parent::validateData(parent::$inputData["data"], [
			"product" => "required"
		]);

		parent::sendResponse(new SuccessResponse(
			$this->updateService->info(
				parent::$inputData["data"]["product"],
				parent::$inputData["data"]["sort"]
			)
		));
	}

	/**
	 * @return void
	 * @throws EntityException
	 * @throws ParametersException
	 */
	#[NoReturn] public function infoAll(): void
	{
		parent::validateData(parent::$inputData["data"], [
			"product" => "required"
		]);

		parent::sendResponse(new SuccessResponse(
			$this->updateService->infoAll(
				parent::$inputData["data"]["product"],
				parent::$inputData["data"]["sort"]
			)
		));
	}
}