<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Runtime;

use Serafim\Railgun\Adapters\AdapterInterface;
use Serafim\Railgun\Adapters\Webonyx;
use Serafim\Railgun\Reflection\Abstraction\DocumentTypeInterface;

/**
 * Class Adapter
 * @package Serafim\Railgun\Runtime
 */
class Adapter
{
    /**
     * @var array|AdapterInterface[]
     */
    private static $adapters = [
        Webonyx::class
    ];

    /**
     * @param DocumentTypeInterface $document
     * @return AdapterInterface
     * @throws \LogicException
     */
    public static function resolve(DocumentTypeInterface $document): AdapterInterface
    {
        foreach (self::$adapters as $adapter) {
            if ($adapter::isSupported()) {
                return new $adapter($document);
            }
        }

        throw new \LogicException('Can not find allowed query adapter');
    }
}
