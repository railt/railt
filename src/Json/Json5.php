<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json;

use Phplrt\Exception\ExternalException;
use Phplrt\Io\File;
use Phplrt\Lexer\LexerInterface;
use Phplrt\Parser\Exception\UnexpectedTokenException;
use Phplrt\Parser\Exception\UnrecognizedTokenException;
use Phplrt\Parser\ParserInterface;
use Railt\Json\Exception\JsonException;
use Railt\Json\Exception\JsonSyntaxException;
use Railt\Json\Json5\Ast\Json5Node;
use Railt\Json\Json5\Parser;

/**
 * Class Json5
 */
class Json5 extends Json
{
    /**
     * @param string $json5
     * @param int $options
     * @param int $depth
     * @return array|mixed|object
     * @throws JsonException
     */
    public static function decode(string $json5, int $options = self::DEFAULT_DECODING_OPTIONS, int $depth = 512)
    {
        \assert(\interface_exists(ParserInterface::class),
            self::missingDependency('phplrt/parser'));

        \assert(\interface_exists(LexerInterface::class),
            self::missingDependency('phplrt/lexer'));


        try {
            return parent::decode($json5, $options, $depth);
        } catch (\JsonException $e) {
            try {
                $parser = new Parser($options, $depth);

                /** @var Json5Node $ast */
                $ast = $parser->parse(File::fromSources($json5));

                return $ast->reduce();
            } catch (UnrecognizedTokenException | UnexpectedTokenException $e) {
                throw new JsonSyntaxException(self::message($e), $e->getCode(), $e);
            } catch (ExternalException $e) {
                throw new JsonException(self::message($e), $e->getCode(), $e);
            }
        }
    }

    /**
     * @param string $dependency
     * @return \LogicException
     */
    private static function missingDependency(string $dependency): string
    {
        $message = 'The "%s" package is required, make sure the component ' .
            'is installed correctly or use the "composer require %1$s" ' .
            'command to install missing dependency';

        return \sprintf($message, $dependency);
    }

    /**
     * @param ExternalException $e
     * @return string
     */
    private static function message(ExternalException $e): string
    {
        $message = '%s on line %d at column %d';

        return \sprintf($message, $e->getMessage(), $e->getLine(), $e->getColumn());
    }
}
