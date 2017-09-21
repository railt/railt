<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Standard;

use Railt\Reflection\Contracts\Document;

/**
 * An interface that standardizes the constructor
 * of all types of the standard library.
 */
interface StandardType
{
    /**
     * @param Document $document
     */
    public function __construct(Document $document);
}
