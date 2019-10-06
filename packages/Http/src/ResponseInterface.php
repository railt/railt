<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

/**
 * Interface ResponseInterface
 */
interface ResponseInterface
{
    /**
     * @var string
     */
    public const FIELD_DATA = 'data';

    /**
     * @var string
     */
    public const FIELD_ERRORS = 'errors';

    /**
     * @var string
     */
    public const FIELD_EXTENSIONS = 'extensions';

    /**
     * @return array
     */
    public function toArray(): array;
}
