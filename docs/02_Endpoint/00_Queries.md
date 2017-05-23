# Queries

> No description provided yet ='(

```php
use Serafim\Railgun\Types\Schemas\TypeDefinition;

class UserQuery extends AbstractQuery
{
    public function getType(TypeDefinition $schema): TypeDefinitionInterface
    {
        return $schema->typeOf(User::class);
    }

    public function resolve($value, array $arguments = [])
    {
        return [ 
            'comments' => [
                ['id' => 1, 'content' => 'first content'],
                ['id' => 2, 'content' => 'second content'],
                ['id' => 3, 'content' => 'third content'],
            ]
        ];
    }
}
```
