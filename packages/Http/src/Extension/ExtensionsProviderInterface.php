<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Extension;

use Ramsey\Collection\Map\TypedMapInterface;

/**
 * Interface ExtensionsProviderInterface
 */
interface ExtensionsProviderInterface
{
    /**
     * @var string
     */
    public const FIELD_EXTENSIONS = 'extensions';

    /**
     * @return TypedMapInterface|mixed[]
     */
    public function getExtensions(): TypedMapInterface;
}
