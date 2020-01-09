<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\CodeGenerator\Value;

use Railt\CodeGenerator\AbstractGenerator;
use Railt\Config\RepositoryInterface;
use Railt\TypeSystem\Value\ValueInterface;

/**
 * Class ValueGenerator
 */
abstract class ValueGenerator extends AbstractGenerator
{
    /**
     * @var ValueInterface
     */
    protected ValueInterface $value;

    /**
     * IntValueGenerator constructor.
     *
     * @param ValueInterface $value
     * @param array|RepositoryInterface $config
     */
    public function __construct(ValueInterface $value, $config = [])
    {
        $this->value = $value;

        parent::__construct($config);
    }
}
