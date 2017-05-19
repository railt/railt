<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Types;

use Serafim\Railgun\Support\InteractWithName;
use Serafim\Railgun\Contracts\Types\InternalTypeInterface;

/**
 * Class InternalType
 * @package Serafim\Railgun\Types
 */
final class InternalType implements InternalTypeInterface
{
    use InteractWithName;

    /**
     * InternalType constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    protected function getDescriptionSuffix(): string
    {
        return 'internal scalar type';
    }
}
