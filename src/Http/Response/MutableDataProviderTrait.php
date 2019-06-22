<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Response;

use Railt\Contracts\Http\Response\MutableDataProviderInterface;

/**
 * Trait MutableDataProviderTrait
 */
trait MutableDataProviderTrait
{
    use DataProviderTrait;

    /**
     * @param array|null $data
     * @return MutableDataProviderInterface|$this
     */
    public function withData(?array $data): MutableDataProviderInterface
    {
        $this->data = $data;

        return $this;
    }
}
