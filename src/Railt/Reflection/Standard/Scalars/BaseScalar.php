<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Standard\Scalars;

use Railt\Reflection\Builder\Support\Deprecation;
use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Contracts\Types\ScalarType;
use Railt\Reflection\Standard\Common\HasName;

/**
 * Class BaseScalar
 */
abstract class BaseScalar implements ScalarType
{
    use HasName;
    use Deprecation;

    /**
     * @var Document
     */
    private $document;

    /**
     * RFC Description
     */
    protected const RFC_IMPL_DESCRIPTION = 'At the moment the type is not supported by the 
        GraphQL standard, its implementation is not allowed in the future.';

    /**
     * BaseScalar constructor.
     * @param Document $document
     * @param string $name
     */
    public function __construct(Document $document, string $name)
    {
        $this->name = $name;
        $this->document = $document;
    }

    /**
     * @return Document
     */
    public function getDocument(): Document
    {
        return $this->document;
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Scalar';
    }
}
