<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Http;

use Railt\Component\Http\Output\ProvideData;

/**
 * Interface OutputInterface
 */
interface OutputInterface extends ProvideData
{
    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @return string|null
     */
    public function getTypeName(): ?string;
}
