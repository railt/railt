<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Contracts\Registrars;

use Serafim\Railgun\Contracts\ContainsNameInterface;

/**
 * Interface FieldRegistrarInterface
 * @package Serafim\Railgun\Contracts\Registrars
 */
interface FieldRegistrarInterface extends ContainsNameInterface
{
    /**
     * @param \Closure $then
     * @return FieldRegistrarInterface
     */
    public function then(\Closure $then): FieldRegistrarInterface;
}
