# Fields

> No description provided yet ='(

```php
class UserType extends AbstractObjectType
{
    public function getFields(Fields $field): iterable
    {
        yield 'comments' => $field->hasMany(CommentType::class);
    }
}
```
