## Схема TypeDefinition

Дополнительно к общим правилам эта схема декларирует 
свои методы для указания отношения к другому композитному типу.

- `typeOf(string $type)`
    > Ссылка на другой тип.
    
- `listOf(string $type)`
    > Ссылка на список типов.
    
Например:

```php
class UsersQuery extends AbstractQuery
{
    public function getType(TypeDefinition $schema): TypeDefinitionInterface
    {
        return $schema->listOf(UserType::class);
    }
}
```
