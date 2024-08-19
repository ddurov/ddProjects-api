<?php declare(strict_types=1);

namespace Api\Controllers;

use Api\Services\UpdateService;
use Api\Singleton\Database;
use Core\Controllers\Controller;
use Core\DTO\SuccessResponse;
use Core\Exceptions\EntityException;
use Core\Exceptions\InternalError;
use Core\Exceptions\ParametersException;
use Core\Exceptions\PermissionException;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;

class UpdateController extends Controller
{
	private UpdateService $updateService;

	/**
	 * @throws InternalError
	 * @throws NotSupported
	 */
	public function __construct()
	{
		$this->updateService = new UpdateService(Database::getEntityManager());
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

		(new SuccessResponse())->setBody(
			$this->updateService->add(
				$_FILES,
				parent::$inputData["data"]["product"],
				strval(parent::$inputData["data"]["versionName"]),
				parent::$inputData["data"]["versionCode"],
				parent::$inputData["data"]["description"]
			)
		)->send();
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
	public function info(): void
	{
		parent::validateData(parent::$inputData["data"], [
			"product" => "required"
		]);

		(new SuccessResponse())->setBody(
			$this->updateService->info(
				parent::$inputData["data"]["product"],
				parent::$inputData["data"]["sort"]
			)
		)->send();
	}

	/**
	 * @return void
	 * @throws EntityException
	 * @throws ParametersException
	 */
	public function infoAll(): void
	{
		parent::validateData(parent::$inputData["data"], [
			"product" => "required"
		]);

		(new SuccessResponse())->setBody(
			$this->updateService->infoAll(
				parent::$inputData["data"]["product"],
				parent::$inputData["data"]["sort"]
			)
		)->send();
	}
}