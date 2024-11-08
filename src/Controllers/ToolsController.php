<?php declare(strict_types=1);

namespace Api\Controllers;

use Api\Services\ToolsService;
use Core\Controller;
use Core\Exceptions\ParametersException;
use Core\Models\SuccessResponse;
use JetBrains\PhpStorm\NoReturn;

class ToolsController extends Controller
{
	private ToolsService $utils;

	public function __construct()
	{
		$this->utils = new ToolsService();
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