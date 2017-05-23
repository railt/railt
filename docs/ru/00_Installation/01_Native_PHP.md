# Использование «Чистого PHP»

## Настройки

Ну так давайте включим свет! Для начала нам нужно создать свою точку 
взаимодействия (aka Endpoint). Давайте её создадим и добавим туда ссылки на
запросы (queries), мутаторы (mutations) и другие «магические» вещи.

```php
use Serafim\Railgun\Endpoint;

$endpoint = new Endpoint('test');

$endpoint->query('example', new ExampleQuery());
```

## Отправка запроса

Как только у нас есть приложение, мы можем обрабатывать входящий запрос
И отправить соответствующий ответ клеинту, позволяющий им использовать 
наше замечательное приложение, которое мы подготовили для них.

```php
use Serafim\Railgun\Requests\Factory;

$response = $endpoint->request(Factory::create());
```

## Получение ответа

Наконец, отправьте наш очень важный ответ в формате json и
закройте соединение.

```php
header('Content-Type: application/json');

echo json_encode($response);
```
