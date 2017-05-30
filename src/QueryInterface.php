<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun;

use Serafim\Railgun\Schema\Arguments;
use Serafim\Railgun\Schema\BelongsTo;
use Serafim\Railgun\Schema\Creators\ArgumentDefinitionCreator;
use Serafim\Railgun\Support\NameableInterface;
use Serafim\Railgun\Schema\Creators\CreatorInterface;
use Serafim\Railgun\Schema\Definitions\ArgumentDefinitionInterface;

/**
 * Interface QueryInterface
 * @package Serafim\Railgun
 */
interface QueryInterface extends NameableInterface
{
    /**
     * @param BelongsTo $schema
     * @return CreatorInterface
     */
    public function getType(BelongsTo $schema): CreatorInterface;

    /**
     * @param Arguments $schema
     * @return iterable|ArgumentDefinitionCreator[]
     */
    public function getArguments(Arguments $schema): iterable;

    /**
     * @param array $arguments
     * @return mixed
     */
    public function resolve(array $arguments = []);
}
