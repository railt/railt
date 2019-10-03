<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Response;

/**
 * Trait DataProviderTrait
 */
trait DataProviderTrait
{
    /**
     * @var array|null
     */
    protected ?array $data = null;

    /**
     * @return array|null
     */
    public function getData(): ?array
    {
        \assert($this->data === null || \is_array($this->data));

        return $this->data;
    }
}
