# Review DI provider регистрации Почты России

## Критические замечания

1. `services/provider.php` импортирует
   `WishboxRadicalMartRussianPostOrderRegistrationLibrary\Service\Provider\RadicalMartOrderRegistration`,
   но такого класса и каталога `Service/Provider` в библиотеке нет. Вызов
   `new RadicalMartOrderRegistrationProvider` завершится ошибкой загрузки класса.
2. Из-за отсутствующего provider контейнер не получает регистрацию
   `RadicalMartOrderRegistrationService::class`. Plugin запрашивает этот сервис
   через `RadicalMartOrderRegistrationServiceAwareTrait`, поэтому обработка
   `onRadicalMartAfterOrderSave` не сможет дойти до регистрации заказа.
3. Новый provider должен учитывать актуальный constructor сервиса: dispatcher и
   `RadicalMartOrderRegistrationPolicy`. Взаимодействие с Почтой России должно
   передаваться через зарегистрированный `OrderRegistrationService` пакета
   `WishboxRussianPostLibrary`, например setter-инъекцией предусмотренного aware
   interface.

## Некритические замечания

- В header отсутствует требуемая точка с запятой после лицензии.
- Closure-фабрики не имеют return type; фабрику plugin можно объявить `static` и
  вернуть `PluginInterface`.
- `@noinspection PhpUnusedParameterInspection` у `register()` неверен: параметр
  `$container` используется.
- Выравнивание присваивания `$plugin` не соответствует соседним строкам.

## Проверка

`php -l` проходит, но синтаксическая проверка не загружает импортированные классы
и потому не обнаруживает отсутствующий custom service provider.
