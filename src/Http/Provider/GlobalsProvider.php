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
class GlobalsProvider extends Provider
{
    /**
     * @var string Name of a read-only stream that allows you to read raw data from the request body.
     */
    protected const PHP_INPUT_STREAM = 'php://input';

    /**
     * @return bool
     */
    protected function isJson(): bool
    {
        return $this->matchJson($this->getContentType());
    }

    /**
     * @return string
     */
    private function getContentType(): string
    {
        return $this->readServerArguments()[static::CONTENT_TYPE_KEY] ?? static::CONTENT_TYPE_DEFAULT;
    }

    /**
     * @return array
     */
    protected function readServerArguments(): array
    {
        return (array)($_SERVER ?? []);
    }

    /**
     * @return array
     */
    protected function getJson(): array
    {
        try {
            $content = (string)@\file_get_contents(static::PHP_INPUT_STREAM);

            return $this->parseJson($content);
        } catch (\LogicException $e) {
            return [];
        }
    }

    /**
     * @return iterable
     */
    protected function getRequestArguments(): iterable
    {
        return \array_merge($this->readGetArguments(), $this->readPostArguments());
    }

    /**
     * @return array
     */
    protected function readGetArguments(): array
    {
        return (array)($_GET ?? []);
    }

    /**
     * @return array
     */
    protected function readPostArguments(): array
    {
        return (array)($_POST ?? []);
    }
}
