<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Foundation\Stub;

/**
 * Class MagicGetIssetObject
 */
class MagicGetIssetObject
{
    /**
     * @var array
     */
    private $data;

    /**
     * ArrayAccessObject constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @param mixed $name
     * @return mixed|null
     */
    public function __get($name)
    {
        return $this->data[$name] ?? null;
    }

    /**
     * @param mixed $name
     * @return bool
     */
    public function __isset($name): bool
    {
        return isset($this->data[$name]) || \array_key_exists($name, $this->data);
    }
}
