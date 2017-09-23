<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Base;

use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Base\Support\Resolving;
use Railt\Reflection\Base\Support\Identifier;
use Railt\Reflection\Contracts\Types\TypeInterface;
use Railt\Reflection\Base\Behavior\BaseDeprecations;

/**
 * Class BaseType
 * @mixin TypeInterface
 */
abstract class BaseType
{
    use Resolving;
    use BaseDeprecations;

    /**
     * @var string|null
     */
    private $id;

    /**
     * @var Document
     */
    protected $document;

    /**
     * @return Document
     */
    public function getDocument(): Document
    {
        return $this->document;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getUniqueId(): string
    {
        if ($this->id === null) {
            $this->id = Identifier::generate();
        }

        return $this->id;
    }
}
