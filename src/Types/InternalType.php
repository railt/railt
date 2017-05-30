<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Types;

/**
 * Class InternalType
 * @package Serafim\Railgun\Types
 *
 * @internal This is internal GraphQL type scalar definition.
 *           Please do not try to create your instances of this class.
 */
final class InternalType extends AbstractType
{
    /**
     * InternalType constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->inCamelCase()->rename($name);
    }
}
