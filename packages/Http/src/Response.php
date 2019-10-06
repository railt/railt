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
 * Class Response
 */
final class Response implements ResponseInterface
{
    use RenderableTrait;

    /**
     * Response constructor.
     *
     * @param array|null $data
     * @param array $exceptions
     * @param array $extensions
     */
    public function __construct(array $data = null, array $exceptions = [], array $extensions = [])
    {

    }
}
