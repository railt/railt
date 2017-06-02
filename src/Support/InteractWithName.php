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

        // Remove non-writable special chars
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
     * @param null|string $prefix
     * @return string
     */
    protected function formatDescription(string $suffix, ?string $prefix = null): string
    {
        if ($prefix === null) {
            $prefix = Str::ucfirst(Str::snake($this->getName(), ' '));
        }

        return $prefix . ($suffix ? ' ' . trim($suffix) : '');
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
        $isAnonymous = Str::startsWith(static::class, 'class@anonymous');

        if (! $isAnonymous) {
            return $this->formatName(static::class);
        }

        try {
            $reflection = new \ReflectionClass(static::class);

            if ($reflection->getParentClass()) {
                return $this->formatName($reflection->getParentClass()->getShortName());
            }
        } catch (\ReflectionException $e) {
            // Do nothing if Houston have a problem
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

    /**
     * @return bool
     */
    public function hasName(): bool
    {
        return $this->name !== null;
    }

    /**
     * @return bool
     */
    public function hasDescription(): bool
    {
        return $this->description !== null;
    }
}
