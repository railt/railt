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
use Railt\Json\Exception\JsonEncodingException;
use Railt\Json\Exception\JsonException;
use Railt\Json\Exception\JsonStackOverflowException;
use Railt\Json\Exception\JsonSyntaxException;
use Railt\Json\Json5\Decoder\Ast\Json5Node;
use Railt\Json\Json5\Decoder\Parser;
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
     * @throws JsonException
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
     * @throws JsonException
     */
    private function tryFallback(string $json, \Closure $otherwise)
    {
        try {
            return $this->native->decode($json);
        } catch (JsonStackOverflowException | JsonEncodingException $e) {
            throw $e;
        } catch (\Throwable $e) {
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
            /** @var Json5Node $ast */
            $ast = $this->parser->parse(File::fromSources($json5));

            return $ast->reduce();
        } catch (UnrecognizedTokenException | UnexpectedTokenException $e) {
            throw $this->throwJson5Exception($e);
        }
    }

    /**
     * @param ParserException $e
     * @return JsonSyntaxException
     */
    private function throwJson5Exception(ParserException $e): JsonSyntaxException
    {
        $message = '%s on line %d at column %d';
        $message = \sprintf($message, $e->getMessage(), $e->getLine(), $e->getColumn());

        return new JsonSyntaxException($message);
    }
}
