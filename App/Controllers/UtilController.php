<?php declare(strict_types=1);

namespace Api\Controllers;

use Api\Utils;
use Core\Controllers\Controller;
use Core\DTO\Response;
use Core\Exceptions\InvalidParameter;

class UtilController extends Controller
{
    private Utils $utils;

    public function __construct()
    {
        $this->utils = new Utils();
        parent::__construct();
    }

    /**
     * @return void
     * @throws InvalidParameter
     */
    public function getPinningHashDomains(): void
    {
        parent::validateData(parent::$inputData["data"], [
            "product" => "required"
        ]);

        (new Response())->setResponse($this->utils->getPinningHashDomains(parent::$inputData["data"]["domains"]))->send();
    }
}