# Пользовательские поля в order_site.xml

## Изменение

В `forms/radicalmart/order_site.xml` добавлены пользовательские поля Почты России:

- `recipient_name`;
- `index_to` (существующее поле сохранено);
- `region_to`;
- `place_to`;
- `street_to`;
- `house_to`;
- `room_to`.

Первые шесть полей обязательны, `room_to` необязательное. Существующее поле `mail_type` и его обработчик перезагрузки формы сохранены. Для `index_to` также сохранены `onchange`, `hide_default` и `hide_none`.

## Проверка

- `order_site.xml` и `checkout.xml` успешно прошли `xmllint --noout`;
- в `order_site.xml` найдено 7 текстовых пользовательских полей;
- найдено 6 обязательных текстовых полей и одно необязательное `room_to`;
- поле `mail_type` осталось в форме;
- каждое из семи пользовательских имён присутствует одновременно в `order_site.xml` и `checkout.xml`.
