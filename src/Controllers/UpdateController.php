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
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use JetBrains\PhpStorm\NoReturn;

class UpdateController extends Controller
{
	private UpdateService $updateService;

	/**
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
	 * @throws OptimisticLockException
	 * @throws ParametersException
	 * @throws PermissionException
	 */
	public function add(): void
	{
		parent::validateData($this->data + $_FILES, [
			"file" => "required|uploaded_file:0,100M,apk",
			"product" => "required|in:messenger,syncer",
			"versionName" => "required",
			"versionCode" => "required",
			"description" => "required",
			"uploadToken" => "required"
		]);

		if ($this->data["uploadToken"] !== getenv("UPLOAD_TOKEN"))
			throw new PermissionException("access to this method only for administrators");

		$this->updateService->add(
			$_FILES,
			$this->data["product"],
			strval($this->data["versionName"]),
			$this->data["versionCode"],
			$this->data["description"]
		);

		parent::sendResponse(new SuccessResponse("added"));
	}

	/**
	 * @return void
	 * @throws EntityException
	 * @throws ParametersException
	 */
	public function get(): void
	{
		parent::validateData($this->data, [
			"product" => "required",
			"versionName" => "required"
		]);

		$this->updateService->get(
			$this->data["product"],
			strval($this->data["versionName"])
		);
	}

	/**
	 * @return void
	 * @throws EntityException
	 * @throws ParametersException
	 */
	#[NoReturn] public function info(): void
	{
		parent::validateData($this->data, [
			"product" => "required"
		]);

		parent::sendResponse(new SuccessResponse(
			$this->updateService->info(
				$this->data["product"],
				$this->data["sort"]
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
		parent::validateData($this->data, [
			"product" => "required"
		]);

		parent::sendResponse(new SuccessResponse(
			$this->updateService->infoAll(
				$this->data["product"],
				$this->data["sort"]
			)
		));
	}
}