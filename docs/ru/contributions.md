# Участие в разработке

- [Баг-репорты](/ru/contributions#баг-репорты)
- [Обсуждение разработки](/ru/contributions#обсуждение-разработки)
- [Уязвимости безопасности](/ru/contributions#уязвимости-безопасности)
- [Стиль написания кода](/ru/contributions#стиль-написания-кода)
    - [PHPDoc](/ru/contributions#phpdoc)
    - [StyleCI](/ru/contributions#styleci)

## Баг-репорты

С целью активного развития библиотеки, Railt настоятельно рекомендует создавать 
[пулл-реквесты](https://github.com/SerafimArts/Railt/pulls), а не только баг-репорты. 
Баг-репорты могут быть отправлены в форме [пулл-реквеста](https://github.com/SerafimArts/Railt/pulls), 
содержащего в себе ошибку прохождения юнит-тестов.

Помните, что если вы отправляете баг-репорт, он должен содержать заголовок и чёткое описание 
проблемы. Вам также следует включить как можно больше значимой информации и примеров кода, которые 
отражают проблему. Цель баг-репорта состоит в упрощении локализации и исправления проблемы.

Также помните, что баг-репорты создаются в надежде, что другие пользователи с такой же проблемой смогут 
принять участие в её решении вместе с вами. Но не ждите, что сразу появится какая-либо активность над 
вашим репортом или другие побегут исправлять вашу проблему. Баг-репорт призван помочь вам и другим 
пользователям начать совместную работу над решением проблемы.

Исходный код Railt находится [на GitHub](https://github.com/SerafimArts/Railt).

## Обсуждение разработки

Вы можете предложить новый функционал или улучшение существующего в 
[обсуждениях](https://github.com/SerafimArts/Railt/issues) репозитория. 
Если вы предлагаете новый функционал, то, пожалуйста, будьте готовы написать по крайней мере 
часть кода, который потребуется для завершения реализации функционала.

Неформальное обсуждение ошибок, новых и существующих возможностей проходит 
в канале `LaravelRUS/offtop` в [Gitter-чате](https://gitter.im/LaravelRUS/offtop). 
Разработчик, обычно находится в канале по будням и выходным с 12:00 до 01:00 ночи 
(время московское, UTC+03:00), и иногда появляется в другое время.

## Уязвимости безопасности

Если вы обнаружили уязвимость в безопасности Railt, пожалуйста, отправьте email-письмо на 
<a href="mailto:nesk@xakep.ru">nesk@xakep.ru</a>. 
Все подобные уязвимости будут оперативно рассмотрены.

## Стиль написания кода

Railt придерживается стандартов:
- [PSR-1](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md)
- [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)
- [PSR-4](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md)
- [PSR-12](https://github.com/php-fig/fig-standards/blob/master/proposed/extended-coding-style-guide.md)


### PHPDoc

Ниже расположен пример корректного блока документации кода Railt. 
Обратите внимание на то, что описание содержит только английский язык и отделяется одной 
пустой строкой от docblock-описания.

```php
/**
 * The Response Interface
 * @package Railt\Http
 */
interface ResponseInterface extends Arrayable, Renderable
{
    /**
     * Creates a new instance of Request
     *
     * @param array $data Array of response body items
     * @param array|\Throwable[]|string[] $errors Array of response errors. 
     */
    public function __construct(array $data, array $errors = []);
}
```

### StyleCI

Не беспокойтесь, если стиль вашего кода не безупречен! [StyleCI](https://styleci.io/) 
автоматически применит стилистические правки после вливания пулл-реквеста в репозиторий Railt. 
Это позволяет нам сосредоточиться на самом коде, а не его стиле.
