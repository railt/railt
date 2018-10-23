<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Exception\Extension;

/**
 * Class DataExtension
 */
class DataExtension extends Extension
{
    /**
     * @var array
     */
    private $data;

    /**
     * DataExtension constructor.
     * @param mixed $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return array|mixed
     */
    public function getValue()
    {
        return $this->data;
    }
}
