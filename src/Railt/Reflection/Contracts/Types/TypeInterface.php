<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Contracts\Types;

use Railt\Reflection\Contracts\Behavior\Deprecatable;
use Railt\Reflection\Contracts\Document;

/**
 * Interface TypeInterface
 */
interface TypeInterface extends Deprecatable
{
    /**
     * @return Document
     */
    public function getDocument(): Document;

    /**
     * @return string
     */
    public function getTypeName(): string;
}
