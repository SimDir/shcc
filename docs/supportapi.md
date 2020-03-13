# Поддерживаемые сервисы (API)

## Яндекс SpeechKit

Синтез речи (требуется регистрация на сервисе и ключ API);

## OpenWeather

Прогноз погоды для указанного города или местоположения (требуется регистрация на сайте openweathermap.org и ключ API);

## Умный дом Яндекса

Управление умным домом через голосового ассистента Алиса.

Поддержка начального уровня. На данный момент для настройки ассистента необходимо редактировать базу данных внучную.

Частично реализовано взаимодействие с умным дом от Яндекс. Вы можете создать свой приватный навык в Яндексе и привязать светильник Yeelight к виртуальному устройству Яндекс (создаётся через управление БД). Доступно управление питанием, яркостью, цветом и цветовой температурой.

Реализация API Умного дома Яндекс требует особой настройки веб-сервера.