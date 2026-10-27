# Упрощение provider плагина регистрации

## Результат

Plugin provider регистрирует только собственный агрегирующий
`RadicalMartOrderRegistrationProvider` и фабрику экземпляра плагина.

Транзитивные providers перенесены в агрегирующий provider:

- `RequestFactory`;
- `RussianPostClientProvider`;
- `OrderRegistrationProvider`.

Не связанные с регистрацией заказа `TariffServiceProvider`, `TariffModeProvider`
и `MVCFactoryInterface` удалены из composition chain.

## Проверки

- `php -l` обоих provider-файлов — успешно;
- в plugin provider остался ровно один вызов `registerServiceProvider()`;
- в custom provider зарегистрированы три зависимости базового сервиса;
- imports тарифов, компонента и MVC factory отсутствуют.
