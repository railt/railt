<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser;

/**
 * Class Environment
 */
class Environment
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * @param string $variable
     * @param mixed $value
     * @return Environment
     */
    public function share(string $variable, $value): Environment
    {
        $this->data[$variable] = $value;

        return $this;
    }

    /**
     * @param string $variable
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $variable, $default = null)
    {
        return $this->data[$variable] ?? $default;
    }
}
