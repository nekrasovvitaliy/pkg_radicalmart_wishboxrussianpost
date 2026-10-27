# Исправление DI provider регистрации Почты России

## Результат

- Добавлен custom service provider
  `WishboxRadicalMartRussianPostOrderRegistrationLibrary\Service\Provider\RadicalMartOrderRegistration`.
- Provider регистрирует `RadicalMartOrderRegistrationPolicy` и
  `RadicalMartOrderRegistrationService`.
- Orchestration service получил `ContainerAwareInterface` и
  `ContainerAwareTrait`, поэтому базовый сервис регистрации Почты России
  разрешается через зарегистрированный `OrderRegistrationProvider`.
- Plugin provider продолжает регистрировать базовые сервисы Почты России до
  custom provider.
- Исправлены header, closures, return types и лишняя inspection-аннотация.

## Проверки

- `php -l` для всех трёх файлов — успешно;
- namespace custom provider совпадает с import в plugin provider;
- orchestration service зарегистрирован под тем же class key, который использует
  plugin aware trait;
- каталог `src/Service/Provider` входит в устанавливаемую папку `src` библиотеки.
