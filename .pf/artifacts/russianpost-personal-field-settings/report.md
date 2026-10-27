# Применение настроек полей в personal-форме

## Изменения

- `PersonalShippingMethodFormPreparer` подключает семь существующих трейтов полей Почты России и вызывает их в `prepare()`;
- состояние `hidden` удаляет поле из personal-формы;
- состояния `required` и `not_required` устанавливают соответствующий атрибут `required`;
- из `onGetPersonalMethodForm()` удалена устаревшая обработка `country`, `city`, `zip` и остальных полей старого набора;
- personal-layout переведён на `recipient_name`, `index_to`, `region_to`, `place_to`, `street_to`, `house_to`, `room_to`;
- personal-layout проверяет соответствующий параметр `field_*` перед выводом каждого поля.

## Архитектура

Сохранена существующая архитектура проекта: XML определяет поля, preparer traits применяют состояние параметров, layout отвечает за условный вывод. Joomla PHP-правки выполнены с сохранением существующих заголовков, PHPDoc и соглашений об именовании.

## Проверка

- `php -l` успешно выполнен для preparer, plugin extension и personal-layout;
- `personal.xml` и `method.xml` успешно прошли `xmllint --noout`;
- подтверждены импорты, подключения и вызовы всех семи трейтов;
- подтверждены семь проверок `field_*` и семь вызовов `renderField()` в personal-layout;
- старые точные имена параметров и полей отсутствуют в personal-layout и обработчике события.
