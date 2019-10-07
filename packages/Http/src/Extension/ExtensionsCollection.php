<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Extension;

use Railt\Http\Common\RenderableTrait;
use Railt\Http\Common\RenderableInterface;
use Ramsey\Collection\Map\AbstractTypedMap;

/**
 * Class ExtensionsCollection
 */
final class ExtensionsCollection extends AbstractTypedMap implements RenderableInterface
{
    use RenderableTrait;

    /**
     * ExtensionsCollection constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    /**
     * @return string
     */
    public function getKeyType(): string
    {
        return 'string';
    }

    /**
     * @return string
     */
    public function getValueType(): string
    {
        return 'mixed';
    }
}
