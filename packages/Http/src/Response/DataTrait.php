<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Response;

use Railt\Contracts\Http\Response\DataInterface;

/**
 * @mixin DataInterface
 */
trait DataTrait
{
    /**
     * @var array|null
     */
    private ?array $data;

    /**
     * @param array|null $payload
     * @return void
     */
    protected function setData(?array $payload): void
    {
        $this->data = $payload;
    }

    /**
     * @return array|null
     */
    public function getData(): ?array
    {
        return $this->data;
    }
}
