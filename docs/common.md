# Общая информация о системе

## Системные требования
- операционная система семейства Linux или иная с поддержкой разделяемой памяти;
- systemd (рекомендуется);
- PHP 7.2 или выше;
- веб-сервер (рекомендуется nginx с PHP режиме fpm);
- сервер баз данных PostgreSQL-10 и выше.

Рекомендуемые версии ПО доступны в Ubuntu 18.04 LTS и новее без необходимости подключать сторонние репозитории. Возможна установка на одноплатные компьютеры Raspbian Pi 2/3 и др., которые поддерживаются Ubuntu.

В качестве серверной операционной системы могут быть применены и иные дистрибутивы и ОС.
При этом следует учитывать минимально-требуемые версии ПО, обеспечить запуск демона и таймера
через имеющуюся систему инициализации.

## Компоненты системы
Система состоит из 3 частей:
- веб-интерфейс;
- демоны для работы с протоколами устройств (имеются пример юнита для запуска через systemd);
- пользовательский скрипт, выполняемый ежеминутно (имеется пример скрипта и юнитов для systemd).

Веб-сервер используется для непосредственного взаимодействия с сервером умного дома. Кроме этого, он может быть использован для получения информации от пользовательских устройств, если они умеют передавать информацию непосредственно по протоколу http/https.

Для устройств, которые взаимодействуют по протоколам, отличным от http/https используются демоны. Задача демона прослушивать необходимые порты на сервере для приёма информации от устройств. Принятая информация обрабатывается демоном, создаётся объект необходимого класса для взаимодействия с устройством и помещается в оперативную память. Если от устройства приняты данные показаний датчиков, срабатывании датчиков и иных событий, производится http запрос на веб-сервер с целью обработки этой информации системой умный дом.

## Информация о разработке
Разработка проекта ведётся на актуальной версии Gentoo. Тестирование проекта ведётся на виртуальной машине с актуальной версией Ubuntu 18.04 LTS. После тестирования использую проект для своего сервера умного дома на базе Rasberry Pi 3B с актуальной версией Ubuntu 18.04 LTS.

Для установки системы на Ubuntu 18.04 LTS не требуется подключать дополнительных репозиториев или собирать что-то из исходного кода. Достаточно установить требуемые пакеты из основного репозитория.