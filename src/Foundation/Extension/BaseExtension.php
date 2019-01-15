<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Extension;

use Railt\Container\ContainerInterface;

/**
 * @deprecated Use class `Railt\Foundation\Extension\Extension` instead.
 */
abstract class BaseExtension extends Extension
{
    /**
     * BaseExtension constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $error = 'Class `%s` is deprecated since 1.3. Use class `%s` instead.';
        @\trigger_error(\sprintf($error, __CLASS__, Extension::class));

        parent::__construct($container);
    }
}
