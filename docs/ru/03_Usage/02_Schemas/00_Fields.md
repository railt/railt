## Схема Fields

Дополнительно к общим правилам эта схема декларирует 
свои методы для указания отношений к другим композитным типам.

- `field(string $type)`
    > Ссылка на другой тип.
    
- `hasMany(string $type)`
    > Ссылка на список типов.
    
Например:

```php
class UserType extends AbstractObjectType
{
    public function getFields(Fields $field): iterable
    {
        yield 'comments' => $field->hasMany(CommentType::class);
    }
}
```
