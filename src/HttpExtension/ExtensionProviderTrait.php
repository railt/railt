<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\HttpExtension;

/**
 * Trait ExtensionProviderTrait
 */
trait ExtensionProviderTrait
{
    /**
     * @var array|ExtensionInterface[]
     */
    protected $extensions = [];

    /**
     * @return array|ExtensionInterface[]
     */
    public function getOriginalExtensions(): array
    {
        return $this->extensions;
    }

    /**
     * @return array
     */
    public function getExtensions(): array
    {
        $result = [];

        foreach ($this->getOriginalExtensions() as $extension) {
            $result[$extension->getName()] = $extension->getValue();
        }

        return $result;
    }
}
