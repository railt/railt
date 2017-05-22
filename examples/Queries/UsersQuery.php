<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Example\Queries;

use Illuminate\Support\Collection;
use Serafim\Railgun\AbstractQuery;
use Serafim\Railgun\Example\Models\Comment;
use Serafim\Railgun\Example\Models\User;
use Serafim\Railgun\Example\Serializers\UserSerializer;
use Serafim\Railgun\Example\Types\UserType;
use Serafim\Railgun\Types\Schemas\Arguments;
use Serafim\Railgun\Types\Schemas\TypeDefinition;
use Serafim\Railgun\Contracts\TypeDefinitionInterface;

/**
 * Class UsersQuery
 * @package Serafim\Railgun\Example\Queries
 */
class UsersQuery extends AbstractQuery
{
    /**
     * @param TypeDefinition $schema
     * @return TypeDefinitionInterface
     */
    public function getType(TypeDefinition $schema): TypeDefinitionInterface
    {
        return $schema->listOf(UserType::class);
    }

    /**
     * @param Arguments $schema
     * @return iterable
     */
    public function getArguments(Arguments $schema): iterable
    {
        yield 'name' => $schema->strings();
    }

    /**
     * @param $value
     * @param array $arguments
     * @return Collection
     */
    public function resolve($value, array $arguments = [])
    {
        $result = UserSerializer::collection($this->createFakeData());

        // users(name: ["Vasya"]) { ... }
        if ($arguments['name'] ?? false) {
            $result = $result->whereIn('name', $arguments['name']);
        }

        return $result;
    }

    /**
     * @return Collection
     */
    private function createFakeData(): Collection
    {
        $users = [
            new User('Vasya'),
            new User('Petya'),
            new User('Admin', 'admin@example.com'),
        ];

        /** @var User $user */
        foreach ($users as $user) {
            foreach (range(1, random_int(1, 10)) as $i) {
                $user->addComment(new Comment('Comment body ' . $i));
            }
        }

        return new Collection($users);
    }
}
