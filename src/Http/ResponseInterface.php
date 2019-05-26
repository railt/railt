<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Railt\Http\Response\ExceptionsProviderInterface;
use Railt\HttpExtension\ExtensionProviderInterface;

/**
 * Interface ResponseInterface
 */
interface ResponseInterface extends
    ExtensionProviderInterface,
    ExceptionsProviderInterface,
    RenderableInterface
{
    /**
     * @return array|null
     */
    public function getData(): ?array;

    /**
     * @return array|null
     */
    public function getErrors(): ?array;

    /**
     * @return array
     */
    public function getExtensions(): array;

    /**
     * @return bool
     */
    public function isValid(): bool;

    /**
     * @return bool
     */
    public function isInvalid(): bool;
}
