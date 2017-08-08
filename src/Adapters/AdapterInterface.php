<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Adapters;

use Serafim\Railgun\Http\ResponderInterface;
use Serafim\Railgun\Reflection\Abstraction\DocumentTypeInterface;

/**
 * Interface AdapterInterface
 * @package Serafim\Railgun\Adapters
 */
interface AdapterInterface extends ResponderInterface
{
    /**
     * @return bool
     */
    public static function isSupported(): bool;

    /**
     * AdapterInterface constructor.
     * @param DocumentTypeInterface $document
     */
    public function __construct(DocumentTypeInterface $document);
}
