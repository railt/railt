<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json\Json5;

use Railt\Io\File;
use Railt\Json\Exception\JsonSyntaxException;
use Railt\Json\JsonDecoder;
use Railt\Json\Rfc7159\NativeJsonDecoder;
use Railt\Lexer\LexerInterface;
use Railt\Parser\Exception\ParserException;
use Railt\Parser\Exception\UnexpectedTokenException;
use Railt\Parser\Exception\UnrecognizedTokenException;
use Railt\Parser\ParserInterface;

/**
 * Class Json5Decoder
 */
class Json5Decoder extends JsonDecoder
{
    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var NativeJsonDecoder
     */
    private $native;

    /**
     * Json5Decoder constructor.
     *
     * @throws \LogicException
     */
    public function __construct()
    {
        if (! \interface_exists(ParserInterface::class)) {
            throw $this->throwDependencyException('railt/parser', 1);
        }

        if (! \interface_exists(LexerInterface::class)) {
            throw $this->throwDependencyException('railt/lexer', 2);
        }

        $this->parser = new Parser();
        $this->native = new NativeJsonDecoder();
    }

    /**
     * @param string $dependency
     * @param int $code
     * @return \LogicException
     */
    private function throwDependencyException(string $dependency, int $code = 0): \LogicException
    {
        $message = 'The "%s" package is required, make sure the component ' .
            'is installed correctly or use the "composer require %1$s" ' .
            'command to install missing dependency';

        $message = \sprintf($message, $dependency);

        return new \LogicException($message, $code);
    }

    /**
     * @param string $json
     * @return mixed
     */
    public function decode(string $json)
    {
        return $this->tryFallback($json, function (string $json) {
            return $this->tryParse($json);
        });
    }

    /**
     * Try parsing with native JSON extension first, since that's much faster.
     *
     * @param string $json
     * @param \Closure $otherwise
     * @return mixed
     */
    private function tryFallback(string $json, \Closure $otherwise)
    {
        try {
            return $this->native->decode($json);
        } catch (\JsonException|\AssertionError $e) {
            return $otherwise($json);
        }
    }

    /**
     * @param string $json5
     * @throws JsonSyntaxException
     * @return mixed
     */
    private function tryParse(string $json5)
    {
        try {
            $ast = $this->parser->parse(File::fromSources($json5));
            // TODO

        } catch (ParserException $e) {
            $message = \vsprintf('%s at line %d column %d in %s', [
                $e->getMessage(),
                $e->getLine(),
                $e->getColumn(),
                $json5
            ]);

            throw new JsonSyntaxException($message);
        }
    }
}
