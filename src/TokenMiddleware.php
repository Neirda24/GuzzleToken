<?php

namespace Guzzle\Token;

use Psr\Http\Message\RequestInterface;

class TokenMiddleware
{
    public const PARAMETER_TYPE_HEADER = 'header';

    public const PARAMETER_TYPES = [
        self::PARAMETER_TYPE_HEADER,
    ];

    /**
     * @var string
     */
    private $parameterType;

    /**
     * @var string
     */
    private $parameterName;

    /**
     * @var string
     */
    private $token;

    /**
     * TokenMiddleware constructor.
     *
     * @param string $parameterType
     * @param string $parameterName
     * @param string $token
     *
     * @throws UnsupportedParameterTypeException
     */
    public function __construct(string $parameterType, string $parameterName, string $token)
    {
        if (!in_array($parameterType, self::PARAMETER_TYPES, true)) {
            throw new UnsupportedParameterTypeException($parameterType);
        }

        $this->parameterType = $parameterType;
        $this->parameterName = $parameterName;
        $this->token         = $token;
    }

    /**
     * Add token auth header to Request
     *
     * @return callable
     */
    public function attach()
    {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                $request = $request->withAddedHeader($this->parameterName, $this->token);

                return $handler($request, $options);
            };
        };
    }
}
