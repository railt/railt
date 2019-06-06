<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Exception;

use Phplrt\Exception\MutableException\MutableFileTrait;
use Phplrt\Exception\MutableException\MutablePositionTrait;
use Railt\Http\Exception\Location\MutableLocationsProviderTrait;
use Railt\Http\Exception\Path\MutablePathProviderTrait;
use Railt\Http\Extension\ExtensionProviderTrait;
use Railt\Http\Extension\MutableExtensionProviderTrait;

/**
 * Class GraphQLException
 */
class GraphQLException extends \Exception implements MutableGraphQLExceptionInterface
{
    use MutableFileTrait;
    use MutablePositionTrait;
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
    protected $public = false;

    /**
     * @var string
     */
    protected $originalMessage;

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
}
