Модуль "my.currencyrates" для Bitrix

Описание:

Модуль "my.currencyrates" предназначен для интеграции с API ЦБ РФ для получения курсов валют, их отображения в административной панели и на публичных страницах сайта. Модуль использует D7 API Bitrix, а также включает два компонента для работы с курсами валют: фильтр и список.

Функциональность:

- Получение ежедневных курсов валют с помощью API ЦБ РФ.
- Автоматическое обновление данных курсов валют с помощью агента.
- Управление данными валют в административной панели.
- Два компонента: currencyrates.filter и currencyrates.list для публичного отображения и фильтрации курсов.
- Поддержка постраничной навигации и фильтрации данных.

Требования:

- Bitrix: версия 20 и выше.
- PHP: версия 7.1 и выше.
- SOAP-расширение PHP для работы с API ЦБ РФ.

Установка:

1. Установка через "local/modules"

    1.1. Скачайте или клонируйте репозиторий в директорию "/local/modules/my.currencyrates".

         git clone https://example.com/repo/my.currencyrates.git /local/modules/my.currencyrates

    1.2. Перейдите в административную панель Bitrix:

         http://yourdomain.com/bitrix/admin/partner_modules.php

    1.3. Найдите модуль "my.currencyrates" и нажмите "Установить".

2. Установка агента:

    После установки модуля агент для автоматического обновления данных курсов валют должен быть настроен. Агент запускается раз в сутки для получения новых данных.

    Агент можно проверить и настроить через административную панель Bitrix:

    2.1. Перейдите в "Настройки" → "Инструменты" → "Агенты".

    2.2. Найдите агента модуля "my.currencyrates" или создайте вручную, если необходимо:

         \Mycompany\CurrencyRates\Agent::updateRates();

Использование:

1. Компоненты:

   Модуль предоставляет два компонента для публичной части сайта:

   1.1. Компонент "currencyrates.filter":

    ```php
        <?php
        //Этот компонент отображает фильтр для выбора валюты и диапазона дат.

        //Пример использования компонента в публичной части:

        $APPLICATION->IncludeComponent(
            "mycompany:currencyrates.filter",
            "default",
            []
        );
        ?>
    ```

   1.2. Компонент "currencyrates.list":

    ```php
        <?php
        //Этот компонент отображает список курсов валют с поддержкой постраничной навигации и фильтрации.

        //Пример использования компонента в публичной части:

        $APPLICATION->IncludeComponent(
            "mycompany:currencyrates.list",
            "default",
            [
                "PAGE_SIZE" => 10, // Количество элементов на странице
            ]
        );
        ?>
    ```

2. Административная панель:

   Модуль предоставляет интерфейс для управления курсами валют в административной панели. Чтобы попасть на страницу управления курсами:

   2.1. В административной части сайта перейдите в "Сервисы" → "Курсы валют".

   2.2. На этой странице можно:
        - Просматривать курсы валют.
        - Редактировать существующие записи.
        - Удалять курсы валют.

Архитектура модуля:

1. Основные классы:

   - CBRApi: Класс для работы с API ЦБ РФ. Он получает курсы валют и предоставляет методы для их обработки.
   - CurrencyRateTable: ORM класс для работы с таблицей курсов валют в базе данных.
   - Agent: Класс агента, который автоматически обновляет курсы валют раз в сутки.

2. Структура файлов:

<div>
    <pre>
/local/modules/my.currencyrates/
├── admin/                          # Административные файлы
│   ├── currencyrates_admin.php     # Основной файл для административного интерфейса
│   ├── currencyrates_edit.php      # Файл редактирования элементов, получаемых модулем
│   ├── currencyrates_settings.php  # Файл редактирования настроек модуля
│   ├── menu.php                    # Файл для регистрации меню в административном интерфейсе
├── components/
│   ├── currencyrates.filter/       # Компонент фильтра
│   │   └── templates/default/      # Шаблон компонента фильтра
│   ├── currencyrates.list/         # Компонент списка курсов валют
│   │   └── templates/default/      # Шаблон компонента списка
├── install/                        # Файлы установки и удаления модуля
│   ├── version.php                 # Версия модуля
│   └── index.php                   # Класс установки и удаления модуля
├── lang/                           # Языковые файлы
├── lib/                            # Библиотеки и классы
│   ├── agent.php                   # Класс агента
│   ├── cbr_api.php                 # Класс интеграции с API ЦБ РФ
│   └── currencyrate.php            # ORM-класс для работы с базой данных
    </pre>
</div>


3. Таблица базы данных:

   При установке модуля создается таблица для хранения курсов валют.

   Пример структуры таблицы:

   Поле     | Тип         | Описание               
   -------- | ----------- | ------------------------
   ID       | INT         | Уникальный идентификатор
   CODE     | VARCHAR(3)  | Код валюты (ISO)       
   DATE     | DATETIME    | Дата курса             
   COURSE   | FLOAT       | Курс валюты            

Настройка:

1. В настройках модуля можно указать, какие валюты следует отслеживать.

2. Настройки хранятся в административной панели.

Пример настройки:

1. Перейдите в "Сервисы" → "Курсы валют" → "Настройки".

2. Укажите валюты, которые необходимо отслеживать.

Обновление:

Для обновления модуля загрузите новую версию в директорию "/local/modules/my.currencyrates" и выполните команду обновления в административной панели Bitrix.

Удаление модуля:

1. Перейдите в административную панель Bitrix: "Marketplace" → "Установленные решения".

2. Найдите модуль "my.currencyrates" и нажмите "Удалить".

3. Все данные о курсах валют, а также таблица базы данных будут удалены.

Лицензия:

Этот модуль распространяется под лицензией MIT. Подробности можно найти в файле LICENSE.
