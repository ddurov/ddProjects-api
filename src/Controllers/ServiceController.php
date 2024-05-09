<?php declare(strict_types=1);

namespace Api\Controllers;

use Api\Service;
use Core\Controllers\Controller;
use Core\DTO\SuccessResponse;
use Core\Exceptions\ParametersException;

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
	public function getPinningHashDomains(): void
	{
		parent::validateData(parent::$inputData["data"], [
			"domains" => "required"
		]);

		(new SuccessResponse())->setBody(
			$this->utils->getPinningHashDomains(parent::$inputData["data"]["domains"])
		)->send();
	}
}