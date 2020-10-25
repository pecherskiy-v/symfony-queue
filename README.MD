# Структура
 - api [описание апи свагер + http запрос] 
 - Docker [настройки контейнера]
    - nginx
    - Rabbit-Mq
    - php-7.4-fpm
        - api endpoin
        - queue worker
 - EventsGenerator [генераитор обращений к апи, консолльная командаа]
 - Worker [прилождение Symfony 4.4]
    - принимает события и складывает их в очередь 
    - обработчик котрый получает события из очререди и обрабатывает их
    
# Звапуск
1. докер контейнер для запуска прилодения и необходимой переферии.
    
    в папке Docker в .env описаны переменные окружения запуска
    
    нас интересует worker_count - количество реклик обработчика 
    (напряму влияет на скорость разбора очереди.)
    
    ```
    cd .\Docker\
    docker-compose up -d
    ```
   * запуск контейнра относительно долгий.
     от 1 до 2 минут, так как воркеры зависимы от RabbitMq.
     контейнры воркеров ожидаю полного запсука и инициализации RabbitMq.
   
2. запуск генератора событий/сообщений

    в папке EventsGenerator в .env
    
    |   название    | пример значения              |  описание |
    |---------------|------------------------------|------------------------------------------|
    | USER_COUNT    |      10                      | кол-во генерируемых пользовтелйе         |
    | MESSAGE_COUNT |      5                       | количество сообщений каждого пользовтеля |
    | SERVICE_URL   | 'http://mvc.local/event/new' | адрес апи                                |
    | SEND_DEBUG    |      false                   | отладочная информация отправки запроса   |
    | DEBUG         |      dalse                   | общая отладочная информация              |

    ```
     php .\EventsGenerator\src\run.php
    ```

# Log приложения
1. апи не соталяет файлового лога
    папк var прилождения смонтирована в озу tmpfs
2. воркеры пишут лог по адресу .\Worker\var\log\dev.log
3. также лог можно увидеть средтвами docker
   
   ```
   docker logs CONTAINER
   ```
   напрмиер
   ```
   docker logs rabbitmq
   ```
   
# Бону
в контейнрах реализован проброс переменных xDebug

    PHP_XDEBUG_IDEKEY=PSTORM
    PHP_XDEBUG_DEFAULT_ENABLE=0
    PHP_XDEBUG_REMOTE_ENABLE=0
    PHP_XDEBUG_REMOTE_AUTOSTART=0
    PHP_XDEBUG_REMOTE_CONNECT_BACK=0
    PHP_XDEBUG_PROFILER_ENABLE=0
    PHP_XDEBUG_REMOTE_HOST=host.docker.internal
    PHP_XDEBUG_REMOTE_PORT=9006
    PHP_XDEBUG_REMOTE_MODE=req
    PHP_IDE_CONFIG=serverName=MVC-CLI
    
переменные можно изменить в docker-compose.yml, можно прописать подстановку из хоста