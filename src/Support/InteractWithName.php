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

/**
 * Trait InteractWithName
 * @package Serafim\Railgun\Support
 * @mixin NameableInterface
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
     * @var bool
     */
    protected $camelize = true;

    /**
     * @var bool
     */
    protected $formatName = true;

    /**
     * @var array
     */
    protected $suffixes = [
        'Type',
        'Query',
        'Mutation',
    ];

    /**
     * @param null|string $name
     * @return NameableInterface|$this
     */
    public function rename(?string $name)
    {
        $this->name = $this->formatName($name);

        return $this;
    }

    /**
     * @param string $name
     * @return string
     */
    protected function formatName(string $name): string
    {
        // Escape fully qualified class names
        $name = array_last(explode('\\', $name));

        //
        // If formatting required remove suffixes and check formatter options:
        //  - camelize = true: "Some string\n" => "SomeString"
        //  - camelize = false: "Some string\n" => "some_string"
        //
        if ($this->formatName) {
            $name = str_replace($this->suffixes, '', $name);

            return $this->camelize
                ? Str::studly($name)
                : Str::snake(preg_replace('/\W+/iu', '_', $name));
        }

        //
        // Otherwise remove non-writable special chars:
        //  - "Some string\n" => "Somestring"
        //
        return preg_replace('/\W+/iu', '', $name);
    }

    /**
     * @param null|string $description
     * @return NameableInterface|$this
     */
    public function about(?string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return NameableInterface|$this
     */
    public function inSnakeCase()
    {
        $this->formatName = true;
        $this->camelize = false;

        return $this;
    }

    /**
     * @return NameableInterface|$this
     */
    public function inCamelCase()
    {
        $this->formatName = true;
        $this->camelize = true;

        return $this;
    }

    /**
     * @return NameableInterface|$this
     */
    public function withoutNameFormatting()
    {
        $this->formatName = false;

        return $this;
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
     * @return string
     */
    private function resolveDescription(): string
    {
        return $this->formatDescription($this->getDescriptionSuffix());
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
    public function getName(): string
    {
        if ($this->name === null) {
            $this->name = $this->resolveNameFromDefinition();
        }

        return $this->name;
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    private function resolveNameFromDefinition(): string
    {
        $isAnonymous = Str::startsWith(static::class, 'class@anonymous');

        if (! $isAnonymous) {
            return $this->formatName(static::class);
        }

        $reflection = new \ReflectionClass(static::class);

        if ($reflection->getParentClass()) {
            return $this->formatName($reflection->getParentClass()->getShortName());
        }

        return $this->formatName('AnonymousClass');
    }

    /**
     * @return string
     */
    protected function getDescriptionSuffix(): string
    {
        return 'custom definition';
    }
}
