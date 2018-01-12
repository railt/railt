<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation;

use Railt\Foundation\Extensions\Collection;
use Railt\Foundation\Extensions\RouterExtension;
use Railt\SDL\Reflection\CompilerInterface;

/**
 * Class BaseExtensions
 */
class BaseExtensions extends Collection
{
    /**
     * @var array
     */
    private const DEFAULT_EXTENSIONS = [
        RouterExtension::class,
    ];

    /**
     * BaseExtensions constructor.
     * @param CompilerInterface $compiler
     * @param array $configs
     */
    public function __construct(CompilerInterface $compiler, array $configs = [])
    {
        parent::__construct($compiler, $configs);

        foreach (self::DEFAULT_EXTENSIONS as $extension) {
            $this->add($extension);
        }
    }
}
