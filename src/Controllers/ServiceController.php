<?php declare(strict_types=1);

namespace Api\Controllers;

use Api\Service;
use Core\Controller;
use Core\Exceptions\ParametersException;
use Core\Models\SuccessResponse;
use JetBrains\PhpStorm\NoReturn;

class ServiceController extends Controller
{
	private Service $utils;

	public function __construct()
	{
		$this->utils = new Service();
		parent::__construct();
	}

	/**
	 * @return void
	 * @throws ParametersException
	 */
	#[NoReturn] public function getPinningHashDomains(): void
	{
		parent::validateData(parent::$inputData["data"], [
			"domains" => "required"
		]);

		parent::sendResponse(new SuccessResponse(
			$this->utils->getPinningHashDomains(parent::$inputData["data"]["domains"])
		));
	}
}