ENV_FILE = --env-file ./docker/.env.docker

build:
	- chmod +x ./docker/build.sh
	- ./docker/build.sh
	- docker compose $(ENV_FILE) up -d
	- docker compose $(ENV_FILE) logs multitenancy -f

kill:
	- docker stop multitenancy nginx db
	- docker rm multitenancy nginx db
	- docker system prune -af --volumes
	- rm -r repository .docker

start:
	- docker start multitenancy nginx db

stop:
	- docker stop multitenancy nginx db

restart:
	- docker restart multitenancy nginx db

logs:
	- docker compose $(ENV_FILE) logs -f

shell:
	- docker compose $(ENV_FILE) exec multitenancy /bin/bash
-shell:
	- docker compose $(ENV_FILE) exec /bin/bash

test:
	./vendor/bin/phpunit

clean:
	- docker system prune -af --volumes

down:
	- docker compose $(ENV_FILE) down

reset: 
	- docker compose $(ENV_FILE) down --volumes --remove-orphans
	- docker system prune -af --volumes