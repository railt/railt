<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Extension;

use Phplrt\Parser\Rule\RuleInterface;

/**
 * Interface ExtensionInterface
 */
interface ExtensionInterface
{
    /**
     * @return iterable|string[]
     */
    public function tokens(): iterable;

    /**
     * @return \Generator|RuleInterface[]
     */
    public function rules(): \Generator;

    /**
     * @return iterable|\Closure[]
     */
    public function reducers(): iterable;
}
