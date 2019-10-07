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
 * Trait ExtensionsTrait
 *
 * @mixin ExtensionsProviderInterface
 */
trait ExtensionsTrait
{
    /**
     * @var TypedMapInterface
     */
    protected TypedMapInterface $extensions;

    /**
     * @param array $extensions
     * @return void
     */
    protected function setExtensions(array $extensions): void
    {
        $this->extensions = new ExtensionsCollection($extensions);
    }

    /**
     * @return TypedMapInterface|mixed[]
     */
    public function getExtensions(): TypedMapInterface
    {
        return $this->extensions;
    }
}
