<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL;

use Railt\Compiler\Ast\RuleInterface;
use Railt\Compiler\Parser;
use Railt\Io\Readable;
use Railt\SDL\Compiler\Pipeline;
use Railt\SDL\Parser\Factory;

/**
 * Class Compiler
 */
class Compiler
{
    /**
     * The LL(k) GraphQL SDL Parser implementation.
     *
     * @var Parser
     */
    private $parser;

    /**
     * Compiler constructor.
     */
    public function __construct()
    {
        $this->parser = (new Factory())->getParser();
    }

    /**
     * @return Pipeline
     */
    private function pipeline(): Pipeline
    {
        $pipeline = new Pipeline();

        /**
         * The Header Table contains a list of all type
         * names and their ASTs for their subsequent construction
         * in the form of an object model.
         *
         * When you add and receive data in the table, the
         * Resolver appears as the name formatter, so
         * you can generate a table where the actual
         * name of the type will not match its virtual name.
         * In this way, you can implement virtual namespaces
         * for the interaction between conflicting type names.
         * This is the first layer, bringing names at compile
         * time and should not be used if the output of the real
         * name is based on data inside the document
         * (for example, directives).
         */
        $pipeline->subscribe(Pipeline::STATE_COLLECT, function (RuleInterface $ast) {
            return (new HeaderBuilder($ast))->getHeader();
        });

        return $pipeline;
    }

    /**
     * @param Readable $readable
     * @return mixed
     */
    public function compile(Readable $readable)
    {
        $ast = $this->parser->parse($readable->getContents());

        return $this->pipeline()->process($ast);
    }
}
