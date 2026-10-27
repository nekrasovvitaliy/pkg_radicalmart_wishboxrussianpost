# Тонкий оркестратор регистрации заказа

## Результат

`RadicalMartOrderRegistrationService` оставлен только координатором одношаговой
регистрации заказа:

1. проверяет возможность регистрации через `RadicalMartOrderRegistrationPolicy`;
2. создаёт `RadicalMartOrderRegistrationDataProvider`;
3. передаёт provider в сервис регистрации Почты России.

Из класса удалены выборка заказов, batch-регистрация, построение коллекций
providers и ветка обновления двухшаговой регистрации. Namespace и import data
provider исправлены с CDEK на Russian Post.

## Проверки

- `php -l` — успешно;
- в файле отсутствуют CDEK namespace и imports;
- отсутствуют batch-зависимости и методы `registerByIds()`/`getDataProviders()`;
- публичный метод `register()` сохраняет контракт `void`.
