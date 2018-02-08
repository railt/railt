<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\Io\Readable;
use Railt\SDL\Compiler\Pipeline\Linking;
use Railt\SDL\Compiler\Pipeline\Parsing;
use Railt\SDL\Compiler\Pipeline\Stage;
use Railt\SDL\Compiler\Runtime\CallStack;

/**
 * Class Pipeline
 */
class Pipeline
{
    /**
     * @var \SplFixedArray|Stage[]
     */
    private $stages;

    /**
     * Pipeline constructor.
     * @throws \LogicException
     */
    public function __construct()
    {
        $stack = new CallStack();

        $this->stages = \SplFixedArray::fromArray([
            new Parsing($stack),
            new Linking($stack),
        ]);
    }

    /**
     * @param Readable $input
     * @return mixed
     */
    public function process(Readable $input)
    {
        $result = null;

        foreach ($this->stages as $stage) {
            $result = $stage->handle($input, $result);
        }

        return $result;
    }
}
