<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Response\Exception;

use Phplrt\Source\File;
use Phplrt\Contracts\Source\FileInterface;
use Phplrt\Contracts\Source\ReadableInterface;
use Railt\Http\Exception\GraphQLExceptionInterface;
use Railt\Http\Exception\Path\MutablePathProviderTrait;
use Railt\Http\Exception\Location\MutableLocationsProviderTrait;

/**
 * Class GraphQLException
 */
class GraphQLException extends \Exception implements GraphQLExceptionInterface
{
    //use MutableFileTrait;
    //use MutablePositionTrait;
    use MutablePathProviderTrait;
    use MutableLocationsProviderTrait;
    use MutableExtensionProviderTrait;

    /**
     * For all errors that reflect the internal state of the application
     * and should not be visible to users, the message should be replaced
     * with this message.
     *
     * @var string
     */
    public const INTERNAL_EXCEPTION_MESSAGE = 'Internal Server Error';

    /**
     * @var string
     */
    public const BASIC_EXCEPTION_MESSAGE = 'Something went wrong';

    /**
     * @var string
     */
    public const MESSAGE_KEY = 'message';

    /**
     * @var string
     */
    public const LOCATIONS_KEY = 'locations';

    /**
     * @var string
     */
    public const PATH_KEY = 'path';

    /**
     * @var string
     */
    public const EXTENSIONS_KEY = 'extensions';

    /**
     * @var bool
     */
    protected bool $public = false;

    /**
     * @var string
     */
    protected string $originalMessage;

    /**
     * GraphQLException constructor.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        $this->originalMessage = $message;

        parent::__construct(static::INTERNAL_EXCEPTION_MESSAGE, $code, $previous);
    }

    /**
     * @param string $pathname
     * @return void
     */
    public function setFile(string $pathname): void
    {
        $this->file = $pathname;
    }

    /**
     * @param int $line
     * @return void
     */
    public function setLine(int $line): void
    {
        $this->line = $line;
    }

    /**
     * @return bool
     */
    public function isPublic(): bool
    {
        return $this->public;
    }

    /**
     * @return MutableGraphQLExceptionInterface|$this
     */
    public function publish(): MutableGraphQLExceptionInterface
    {
        return $this->withPublicMode(true);
    }

    /**
     * @param bool $value
     * @return GraphQLException|$this
     */
    private function withPublicMode(bool $value): self
    {
        $this->message = ($this->public = $value)
            ? $this->originalMessage
            : static::INTERNAL_EXCEPTION_MESSAGE;

        return $this;
    }

    /**
     * @return MutableGraphQLExceptionInterface|$this
     */
    public function hide(): MutableGraphQLExceptionInterface
    {
        return $this->withPublicMode(false);
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $result = [
            self::MESSAGE_KEY   => $this->getMessage() ?: static::BASIC_EXCEPTION_MESSAGE,
            self::LOCATIONS_KEY => $this->getLocations(),
            self::PATH_KEY      => $this->getPath(),
        ];

        if (\count($this->getOriginalExtensions())) {
            $result[self::EXTENSIONS_KEY] = $this->getExtensions();
        }

        return $result;
    }

    /**
     * @param mixed|ReadableInterface|resource|string $file
     * @return MutableFileInterface
     * @throws \Throwable
     */
    public function withFile($file): MutableFileInterface
    {
        $file = \is_string($file) ? File::fromPathname($file) : File::new($file);

        if (! $file instanceof FileInterface) {
            throw new \LogicException('File is not physical');
        }

        $this->file = $file;

        return $this;
    }
}
