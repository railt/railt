<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun;

use Serafim\Railgun\Support\InteractWithName;
use Serafim\Railgun\Support\InteractWithTypes;
use Serafim\Railgun\Contracts\Partials\QueryTypeInterface;

/**
 * Class AbstractQuery
 * @package Serafim\Railgun
 */
abstract class AbstractQuery implements QueryTypeInterface
{
    use InteractWithName;
    use InteractWithTypes;

    /**
     * @return bool
     */
    final public function isResolvable(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    final public function isDeprecated(): bool
    {
        return $this->getDeprecationReason() !== '';
    }

    /**
     * @return string
     */
    public function getDeprecationReason(): string
    {
        return '';
    }
}
