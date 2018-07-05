# Система "умный дом"
Проект на стадии разработки. Система имеет ограниченный фукнционал.

## Цели проекта
Разработка простого сервера умного дома на языке PHP:
- поддержка оборудования, которое имеется у автора проекта;
- максимально простой и понятный код на PHP;
- возможность запуска сервера на одноплатных ПК.

Идея создания данного проекта родилась после использования проекта [Majordomo](https://github.com/sergejey/majordomo), внутри которого очень много устаревшего и потенциально опасного кода (например, не используются подготовленные выражения при работе с БД). Однако, если вы не готовы разбираться в исходном коде моего проекта и писать код самостоятельно, то рекомендую использовать именно [Majordomo](https://github.com/sergejey/majordomo), т.к. он имеет широкий спектр поддерживаемого оборудования и техническую поддержку, в том числе платную.

На данный момент проект не ставит своими целями:
- создание клона [Majordomo](https://github.com/sergejey/majordomo);
- создание удобного для конечного пользователя продукта с простой установкой и настройкой без необходимости вникать в программный код;
- поддержки всех имеющихся версий PHP: минимально PHP 7.0, возможно повышение версии в процессе разработки;
- поддержки конфигураций PHP, которые не имеют в своём составе необходимых для работы модулей;
- поддержки работы сервера на семействе ОС Windows.

## Системные требования
- PHP 7.0 или выше;
- поддержка разделяемой памяти (соберите PHP с опцией --enable-sysvshm);
- веб-сервер (рекомендуется nginx с PHP режиме fpm);
- сервер баз данных MariaDB (возможно использовать MySQL, но его работа не тестируется).

## Информация о разработке
Разработка проекта ведётся на актуальной версии Gentoo. Тестирование проекта ведётся на виртуальной машине с актуалной версией Debian 9. После тестирования использую проект для своего сервера умного дома на базе Rasberry Pi 3B с актуальной версией Raspbian.

Для установки системы на Debian и Raspbian не требуется подключать дополнительных репозиториев или собирать что-то из исходного кода. Достаточно установить требуемые пакеты из основного репозитория.

## Уже реализовано
В текущем состоянии проект имеет следующий функционал:
- Созданы демоны для получения данных с оборудования Xiaomi и Yeelight.
- Сервис обработки веб-запросов от демонов с данными и их обработка.
- Сбор данных с датчиков температуры, влажности, давления и других данных в числовом виде.
- Сбор данных с цифровых датчиков с записью лога срабатываний.
- Минималистичный черновой интерфейс для демонстрации работоспособности проекта.
- Отобажение списка устройств, которые передают данные модулям.
- Пока не настраиваемая визуализация в виде графиков температуры, влажности и атмосферного давления.

## Компоненты системы
Система состоит из 3 частей:
- Веб-интерфейс;
- демоны для работы с протоколами устройств (имеется шаблон скрипта запуска для systemd и шаблон крипта для классической системы инициализации);
- пользовательский скрипт, выполняемый ежеминутно (имеется пример скрипта).
Веб-сервер используется для непосредственного взаимодействия с сервером умного дома. Кроме этого, он может быть использован для получения информации от пользовательских устройств если они умеют передавать информацию непосредственно по протоколу http/https.

Для устройств, которые возаимодействуют по протоколам, отличным от http/https используются демоны. Задача демона прослушивать необходимые порты на сервере для приёма информации от устройств. Принятая информация обрабатывается демоном, создаётся объект необходимого класса для взаимодействия с устройством и помещается в оперативную память. Если от устройства приняты данные о показаниях датчиков, срабатывании датчиков и иных событий, производится http запрос на веб-сервер с целью обработки этой инфомации исстемой умный дом.

Для создания пользовательских скриптов подготовлен файл с набором фукнций. Также он подключает автозагрузку классов. Таким образом внутри пользовательского скрипта можно получить объект, связанный с любым из имеющихся устройств и посредством его осуществлять отправку команд на устройство или получить текущее состояние датчиков устройства. В настоящее время поддерживается только один пользовательский скрипт, который выполняется ежеминутно. Для организации его запуска используется штатный диспетчер задач - cron. Для удобства добавления задачи создан bash скрипт, который запускает соответствующий скрипт PHP с ограничением его времени работы 30 секунд.

В примеере пользовательского крипта выполняются следующие задачи:
- включение/выключение ночного режима с выдачей соответствующего голосового оповещения;
- озвучивание каждый час текущего времени (если не включен ночной режим или режим охраны);
- получение и озвучивание каждый час текущей температуры воздуха с сайта OpenWeatherMap;
- озвучивание пользовательского сообщения в определённое время (для примера, оповещение о завершении турнира в игре C.A.T.S.).

В скрипте можно использовать переменные:
- $night - признак включенного ночного режима;
- $security - признак включенного режима охраны;
- $mute - признак отключенного голосового оповещения (включен ночной режим или режим охраны);
- $minute - текущая минута;
- $hour - текущий час;
- $time - текущее время (ЧЧ:ММ).

Имеются следующие функции:
- say($text) - произнести сообщение с помощью TTS;
- getDevice($name) - получить объект для устройства с уникальным именем $name;
- getVar($name) - получить строковую переменную из базы данных с именем $name (поскольку используется PHP, то в переменной могут храниться и числа);
- setVar($name,$value) - установить значение переменной в базе даннхы с именем $name равным $value;
- getObject($name) - аналог getVar(), но при получении данных производит десериализацию данных;
- setObject($name,$object) - аналог setVar, но при вставке использует сериализацию данных;
- getJson($name) - аналог getVar(), но при получении данных производит декодирование данных из формата JSON;
- setJson($name,$object) - аналог setVar, но при вставке кодирует данные в формат JSON.

### Поддержка умного дома Xiaomi
Поддерживается следующее оборудование:
- Xiaomi Gateway - шлюз Xiaomi;
- Xiaomi Temperature And Humidity Sensor - датчик температуры и влажности;
- Aqara Temperature, Humidity And Pressure Sensor датчик температуры, влажности и атмосферного давления;
- Xiaomi Motion Sensor - датчик движения;
- Xiaomi Magnet Sensor - датчик открытия двери/окна.

### Поддержка светильников Yeelight
Используется режим "Управление по локальной сети". Работает обнаружение светильников и передача команд.