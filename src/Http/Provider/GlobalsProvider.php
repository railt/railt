<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Provider;

/**
 * Class GlobalsProvider
 */
class GlobalsProvider implements ProviderInterface
{
    /**
     * @var string Name of a read-only stream that allows you to read raw data from the request body.
     */
    public const PHP_INPUT_STREAM = 'php://input';

    /**
     * @var string Content-Type header key name from $_SERVER variable
     */
    public const CONTENT_TYPE_KEY = 'CONTENT_TYPE';

    /**
     * @return array
     */
    public function getQueryArguments(): array
    {
        return $_GET ?? [];
    }

    /**
     * @return array
     */
    public function getPostArguments(): array
    {
        return $_POST ?? [];
    }

    /**
     * @return null|string
     */
    public function getContentType(): ?string
    {
        return $_SERVER[static::CONTENT_TYPE_KEY] ?? null;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return (string)@\file_get_contents(static::PHP_INPUT_STREAM);
    }
}
