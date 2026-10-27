# Использование общего RadicalMart OrderService

## Результат

- Из плагина удалён приватный дубликат `setShippingDataValue()` и связанные Joomla MVC/Registry зависимости.
- Плагин по-прежнему обращается только к `RadicalMartOrderRegistrationService`.
- `RadicalMartOrderRegistrationService` сохраняет ошибку регистрации через `WishboxRadicalMartLibrary\Service\Order\OrderService`.
- Агрегирующий provider регистрирует `OrderServiceProvider` и внедряет `OrderService` в сервис регистрации.

## Проверки

- PHP-синтаксис трёх изменённых файлов проверен с помощью `php -l`.
- В классе плагина больше нет вызова или реализации `setShippingDataValue()`.
- Ключ `russianpost_registration_error_message` сохранён без изменения.
