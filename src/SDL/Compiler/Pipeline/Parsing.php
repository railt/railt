<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Pipeline;

use Railt\Compiler\Exception\Exception;
use Railt\Compiler\Parser;
use Railt\Io\Readable;
use Railt\SDL\Compiler\Exceptions\ParseException;
use Railt\SDL\Compiler\Parser\Factory;
use Railt\SDL\Compiler\Runtime\CallStackInterface;

/**
 * Class Parsing
 */
class Parsing implements Stage
{
    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var CallStackInterface
     */
    private $stack;

    /**
     * Parsing constructor.
     * @param CallStackInterface $stack
     * @param Parser|null $parser
     * @throws \LogicException
     */
    public function __construct(CallStackInterface $stack, Parser $parser = null)
    {
        $this->parser = $parser ?? (new Factory())->getParser();
        $this->stack  = $stack;
    }

    /**
     * @param Readable $input
     * @param mixed $data
     * @return \Railt\Compiler\Ast\LeafInterface|\Railt\Compiler\Ast\NodeInterface|\Railt\Compiler\Ast\RuleInterface
     * @throws \Railt\SDL\Compiler\Exceptions\ParseException
     */
    public function handle(Readable $input, $data)
    {
        try {
            return $this->parser->parse($input->getContents());
        } catch (Exception $e) {
            throw new ParseException($e->getMessage(), $this->stack);
        }
    }
}
