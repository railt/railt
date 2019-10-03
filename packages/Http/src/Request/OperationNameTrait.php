<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Request;

/**
 * Trait OperationNameTrait
 */
trait OperationNameTrait
{
    /**
     * @var string|null
     */
    protected ?string $operationName = null;

    /**
     * @param string|null $name
     * @return void
     */
    private function setOperationName(?string $name): void
    {
        $this->operationName = $name;
    }

    /**
     * @return string|null
     */
    public function getOperationName(): ?string
    {
        \assert(\is_string($this->operationName) || $this->operationName === null);

        return $this->operationName;
    }

    /**
     * @return bool
     */
    public function hasOperationName(): bool
    {
        return $this->operationName !== null;
    }
}
