<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Input;

/**
 * Interface ProvideRelatedFields
 */
interface ProvideRelatedFields
{
    /**
     * @return array|string[]
     */
    public function getRelatedFields(): array;

    /**
     * @param array $fields
     * @return ProvideRelatedFields|$this
     */
    public function withRelatedFields(array $fields): self;

    /**
     * @param array $fields
     * @return ProvideRelatedFields|$this
     */
    public function setRelatedFields(array $fields): self;

    /**
     * @param string $field
     * @return bool
     */
    public function wants(string $field): bool;
}
