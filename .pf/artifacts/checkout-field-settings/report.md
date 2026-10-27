# Отчёт: настройки полей checkout Почты России

## Результат

- В `forms/radicalmart/method.xml` добавлена отдельная группа настроек checkout-полей.
- Для `recipient_name`, `index_to`, `region_to`, `place_to`, `street_to`, `house_to` и `room_to` добавлены параметры `field_*` с состояниями `required`, `not_required`, `hidden` по образцу standard shipping.
- Значения по умолчанию: первые шесть полей — `required`, `room_to` — `not_required`.
- Добавлены локализованные названия группы и трёх состояний на русском и английском языках.
- В `AbstractFormPreparer` добавлена общая обработка настройки: скрытое поле удаляется, остальным выставляется атрибут `required`.
- Каждый из семи существующих field preparer traits вызывает общую обработку со своим именем поля и значением по умолчанию.
- Checkout layout проверяет соответствующий `field_*` и не выводит поле при значении `hidden`.
- Добавлена защита layout от отсутствующего объекта `$shipping`, как в standard shipping.

## Проверки

- В `method.xml` подтверждены семь уникальных настроек, корректные defaults и точный набор значений `required/not_required/hidden`.
- Подтверждена полная связка каждой настройки с trait и условием layout.
- Обе INI-локализации успешно разобраны и содержат новые ключи.
- `method.xml` и `checkout.xml` успешно разобраны как XML.
- `php -l` успешно выполнен для общего preparer, checkout preparer, семи traits и checkout layout.

## Архитектура

Сохранены текущие точки расширения Joomla: настройки shipping method, отдельные field preparer traits и plugin checkout layout. Общая логика состояния поля вынесена в существующий `AbstractFormPreparer`, чтобы не дублировать удаление и установку обязательности в семи traits.
