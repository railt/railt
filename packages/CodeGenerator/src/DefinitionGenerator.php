<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\CodeGenerator;

use GraphQL\Contracts\TypeSystem\Common\DescriptionAwareInterface;
use GraphQL\Contracts\TypeSystem\DefinitionInterface;
use Railt\CodeGenerator\Value\StringValueGenerator;
use Railt\Config\RepositoryInterface;
use Railt\TypeSystem\Value\StringValue;

/**
 * Class DefinitionGenerator
 */
abstract class DefinitionGenerator extends AbstractGenerator
{
    /**
     * @var DefinitionInterface
     */
    protected DefinitionInterface $type;

    /**
     * AbstractGenerator constructor.
     *
     * @param DefinitionInterface $type
     * @param array|RepositoryInterface $config
     */
    public function __construct(DefinitionInterface $type, $config = [])
    {
        $this->type = $type;

        parent::__construct($config);
    }

    /**
     * @param DescriptionAwareInterface $type
     * @param bool $multiline
     * @return string
     */
    protected function renderDescription(DescriptionAwareInterface $type, bool $multiline = false): string
    {
        if (! $type->getDescription()) {
            return '';
        }

        $description = new StringValueGenerator(StringValue::parse($type->getDescription()), $this->config([
            StringValueGenerator::CONFIG_MULTILINE => $multiline,
        ]));

        return $this->line($description->toString(), $this->depth()) . "\n";
    }
}
