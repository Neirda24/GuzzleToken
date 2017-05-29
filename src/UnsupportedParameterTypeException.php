<?php

namespace Guzzle\Token;

use LogicException;
use Throwable;

class UnsupportedParameterTypeException extends LogicException
{
    public function __construct(string $parameterType = '', int $code = 0, Throwable $previous = null)
    {
        $message = sprintf('`%s` is not supported. Currently availables are: `[%s]`', $parameterType, TokenMiddleware::PARAMETER_TYPES);

        parent::__construct($message, $code, $previous);
    }
}
