<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Dumper\Resolver;

use Railt\Component\Dumper\TypeDumperInterface;

/**
 * Class Resolver
 */
abstract class Resolver implements ResolverInterface
{
    /**
     * @var TypeDumperInterface
     */
    protected $dumper;

    /**
     * Resolver constructor.
     *
     * @param TypeDumperInterface $dumper
     */
    public function __construct(TypeDumperInterface $dumper)
    {
        $this->dumper = $dumper;
    }
}
