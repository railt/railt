<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Driver;

use Railt\Io\Readable;
use Railt\Parser\Ast\RuleInterface;
use Railt\Parser\ParserInterface;

/**
 * Class Proxy
 */
class Proxy implements ParserInterface
{
    /**
     * @var ParserInterface
     */
    private $parent;

    /**
     * Proxy constructor.
     * @param ParserInterface $parent
     */
    public function __construct(ParserInterface $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @param Readable $input
     * @return RuleInterface
     */
    public function parse(Readable $input): RuleInterface
    {
        return $this->parent->parse($input);
    }

    /**
     * @param string $rule
     * @param \Closure $then
     * @return ParserInterface
     */
    public function extend(string $rule, \Closure $then): ParserInterface
    {
        return $this->parent->extend($rule, $then);
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments = [])
    {
        return $this->parent->$name(...$arguments);
    }
}
