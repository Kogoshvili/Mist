include .env

.PHONY: check up stop down import serve

check:
	docker --version
	docker-compose --version
	php --version

up:
	docker-compose up --detach

stop:
	docker-compose stop

down:
	docker-compose down --remove-orphans

import:
	docker exec -i mist_mysql mysql -uroot -p$(MYSQL_ROOT_PASSWORD) $(MYSQL_DATABASE) < $(DB_DUMPFILE)

serve:
	php -S localhost:8080 -t public/
