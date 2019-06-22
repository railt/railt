<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Request;

use Railt\Contracts\Http\Request\MutableOperationNameInterface;

/**
 * Trait MutableOperationNameTrait
 */
trait MutableOperationNameTrait
{
    use OperationNameTrait;

    /**
     * @param string|null $name
     * @return MutableOperationNameInterface|$this
     */
    public function withOperation(?string $name): MutableOperationNameInterface
    {
        $this->operationName = $name;

        return $this;
    }
}
