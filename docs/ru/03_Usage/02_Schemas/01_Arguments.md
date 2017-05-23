## Схема Arguments

Дополнительно к общим правилам эта схема декларирует 
свои методы для указания отношений к другим композитным типам.

- `typeOf(string $type)`
    > Ссылка на другой тип.
    
- `listOf(string $type)`
    > Ссылка на список типов.
    
Например:

```php
class UserMutation extends AbstractMutation
{
    public function getArguments(Arguments $schema): iterable
    {
        yield 'id' => $schema->id();
        yield 'new_user' => $schema->typeOf(UserType::class);
    }
}
```
