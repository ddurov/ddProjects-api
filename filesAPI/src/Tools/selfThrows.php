<?php declare(strict_types=1);

namespace Api\Tools;

use Api\DTO\Response;
use Exception;

class selfThrows extends Exception
{
    public function __construct($message, $code = 500)
    {
        parent::__construct((new Response)->setCode($code)->setStatus("error")->setResponse($message)->toJson(), $code);
    }

}