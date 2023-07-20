<?php

declare(strict_types=1);

namespace Railt\Http\Exception;

use Railt\Contracts\Http\Error\CategoryInterface;

enum Category implements CategoryInterface
{
    #[CategoryInfo(isClientSafe: true)]
    case REQUEST;

    #[CategoryInfo(isClientSafe: false)]
    case SERVER;

    private function getCategoryInfo(): CategoryInfo
    {
        /**
         * Local identity map for {@see CategoryInfo} metadata objects.
         *
         * @var array<non-empty-string, CategoryInfo> $memory
         */
        static $memory = [];

        if (isset($memory[$this->name])) {
            return $memory[$this->name];
        }

        $attributes = (new \ReflectionEnumUnitCase(self::class, $this->name))
            ->getAttributes(CategoryInfo::class);

        if (isset($attributes[0])) {
            return $memory[$this->name] = $attributes[0]->newInstance();
        }

        return new CategoryInfo();
    }

    public function getName(): string
    {
        $info = $this->getCategoryInfo();

        return $info->name ?? $this->name;
    }

    public function isClientSafe(): bool
    {
        $info = $this->getCategoryInfo();

        return $info->isClientSafe;
    }
}
