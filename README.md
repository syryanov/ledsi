### Установка

# Собираем и запускаем контейнеры
docker compose up -d --build

# Устанавливаем зависимости
docker exec api composer install

# Генерируем APP_KEY
docker exec api php artisan key:generate

# Устанавливаем права на storage и cache
sudo chmod -R 777 api/storage
sudo chmod -R 777 api/bootstrap/cache

# Создаём ссылку на storage
docker exec api php artisan storage:link

# Запускаем миграции
docker exec api php artisan migrate

# Создаём пользователя
docker exec -it api php artisan user:create (дальше отвечаем на вопросы)

# Запускаем приложение фронта на хстовой машине
cd app
npm run dev (можно сбилдить)

# Настройки Nginx

server {
    listen 80;
    server_name app.local;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl;
    server_name app.local;
    root /home/ваш_путь_к_рабочей_дериктории/api/public;
    index index.php;

    ssl_certificate /ваш_путь_к_сертификатам/app.local.pem;
    ssl_certificate_key /ваш_путь_к_сертификатам/app.local-key.pem;

    location ~ ^/(api|login|logout|sanctum) {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME /var/www/html/public$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }

    location / {
        # Тут dev порт указан, можно настроить на 3000 если сборку запускаем
        proxy_pass http://localhost:5173;
        proxy_set_header Host $host;
        proxy_set_header X-Forwarded-Proto https;
    }
}

Логику бэкенда написал в контроллерах, так быстрее. В идеале нужно всё выносить в сервисный слой.
Авторизация на Sanctum через сессионную куку. Обязателен https, без него работать не будет.