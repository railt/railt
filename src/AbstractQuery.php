<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun;

use Serafim\Railgun\Schema\Arguments;
use Serafim\Railgun\Support\InteractWithName;

/**
 * Class AbstractQuery
 * @package Serafim\Railgun
 */
abstract class AbstractQuery implements QueryInterface
{
    use InteractWithName;

    /**
     * @param Arguments $schema
     * @return iterable
     */
    public function getArguments(Arguments $schema): iterable
    {
        return [];
    }
}
