docker-compose -p ddd-blog up --build -d

docker exec -it ddd-blog-backend-1 bash

docker-compose -p ddd-blog up -d rabbitmq

php bin/console messenger:consume async -vv