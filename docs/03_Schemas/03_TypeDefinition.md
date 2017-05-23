# TypeDefinition

> No description provided yet ='(

```php
class UsersQuery extends AbstractQuery
{
    public function getType(TypeDefinition $schema): TypeDefinitionInterface
    {
        return $schema->listOf(UserType::class);
    }
}
```
