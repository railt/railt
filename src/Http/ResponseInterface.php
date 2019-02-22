<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Railt\Http\Extension\ProvidesExtensions;
use Railt\Http\Response\ProvideExceptions;
use Railt\Http\Response\Renderable;

/**
 * Interface ResponseInterface
 */
interface ResponseInterface extends ProvideExceptions, ProvidesExtensions, Renderable
{
    /**
     * @var string Data field name
     */
    public const FIELD_DATA = 'data';

    /**
     * @var string Errors field name
     */
    public const FIELD_ERRORS = 'errors';

    /**
     * @var int Positive status code
     */
    public const STATUS_CODE_SUCCESS = 200;

    /**
     * @var int Negative status code
     */
    public const STATUS_CODE_ERROR = 500;

    /**
     * @return bool
     */
    public function isSuccessful(): bool;

    /**
     * @return int
     */
    public function getStatusCode(): int;

    /**
     * @param int $code
     * @return ResponseInterface|$this
     */
    public function withStatusCode(int $code): self;

    /**
     * @return array|null
     */
    public function getData(): ?array;

    /**
     * @param array|null $data
     * @return ResponseInterface|$this
     */
    public function withData(?array $data): self;

    /**
     * @return array
     */
    public function toArray(): array;
}
