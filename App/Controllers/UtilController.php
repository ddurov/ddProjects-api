<?php declare(strict_types=1);

namespace Api\Controllers;

use Api\Utils;
use Core\Controllers\Controller;
use Core\DTO\Response;
use Rakit\Validation\Validator;

class UtilController extends Controller
{
    public array $inputData;

    public function getPinningHashDomains(): void
    {
        $validation = (new Validator())->validate($this->inputData["data"], [
            "domains" => "required"
        ]);

        if (isset($validation->errors->all()[0]))
            (new Response())->setStatus("error")->setCode(400)->setResponse(["message" => $validation->errors->all()[0]])->send();

        (new Response())->setResponse((new Utils())->getPinningHashDomains($this->inputData["data"]["domains"]))->send();
    }
}