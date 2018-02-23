<?php
/**
 * This file is part of railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Generator\Grammar\Reader\Productions;

use Railt\Compiler\Runtime\Ast\Rule;

/**
 * Class RuleDefinition
 */
class Definition extends Rule
{
    /**
     *
     */
    protected const DEFAULT_RULE = 'Anonymous';

    /**
     * @var string
     */
    protected $alias;

    /**
     * RuleDefinition constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct($this->alias = $name);
    }

    /**
     * @param string $name
     */
    public function rename(string $name): void
    {
        $this->alias = $name;
    }

    /**
     * @param string $type
     * @return bool
     */
    public function is(string $type): bool
    {
        return static::class === $type;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        if ($this->alias === self::DEFAULT_RULE) {
            $result = [\class_basename($this) . ' {'];
        } else {
            $result = [\class_basename($this) . '("' . $this->alias . '") {'];
        }

        foreach ($this->children as $child) {
            foreach (\explode("\n", (string)$child) as $line) {
                $result[] = '    ' . $line;
            }
        }

        $result[] = '}';

        return \implode("\n", $result);
    }
}
