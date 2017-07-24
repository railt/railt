# Arguments

> No description provided yet ='(

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
