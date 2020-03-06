COMPOSE_FILES := -f docker-compose.yml

start: up
.PHONY: start

stop: kill
.PHONY: stop

up:
	docker-compose $(COMPOSE_FILES) up -d
.PHONY: up

kill:
	docker-compose $(COMPOSE_FILES) kill
	docker-compose $(COMPOSE_FILES) rm -v --force
.PHONY: kill

build:
	docker-compose $(COMPOSE_FILES) build --force-rm
.PHONY: build

build-no-cache:
	docker-compose $(COMPOSE_FILES) build --no-cache --force-rm
.PHONY: build-no-cache

console:
	docker-compose $(COMPOSE_FILES) exec php bash
.PHONY: console

code-quality-test:
	./ci/test/code-quality-test.sh
.PHONY: code-quality-test

test: cache code-quality-test suite
.PHONY: test
