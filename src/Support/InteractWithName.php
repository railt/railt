<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Support;

use Illuminate\Support\Str;
use Serafim\Railgun\Contracts\ContainsNameInterface;

/**
 * Trait InteractWithName
 * @package Serafim\Railgun\Support
 * @mixin ContainsNameInterface
 */
trait InteractWithName
{
    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var array
     */
    protected $suffixes = [
        'Type',
        'Query',
        'Mutation'
    ];

    /**
     * @param string $name
     * @return ContainsNameInterface
     */
    protected function rename(string $name): ContainsNameInterface
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        if ($this->name === null) {
            $this->name = $this->resolveNameFromDefinition();
        }

        return $this->name;
    }

    /**
     * @return string
     */
    private function resolveNameFromDefinition(): string
    {
        return $this->formatName(static::class);
    }

    /**
     * @param string $name
     * @return string
     */
    protected function formatName(string $name): string
    {
        $name = array_last(explode('\\', $name));

        $name = Str::studly(str_replace($this->suffixes, '', $name));

        return $name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        if ($this->description === null) {
            $this->description = $this->resolveDescription();
        }

        return $this->description;
    }

    /**
     * @param string $suffix
     * @return string
     */
    protected function formatDescription(string $suffix): string
    {
        $prefix = Str::ucfirst(Str::snake($this->getName(), ' '));

        return $prefix . ($suffix ? ' ' . $suffix : '');
    }

    /**
     * @return string
     */
    private function resolveDescription(): string
    {
        return $this->formatDescription($this->getDescriptionSuffix());
    }

    /**
     * @return string
     */
    protected function getDescriptionSuffix(): string
    {
        return 'custom definition';
    }
}
