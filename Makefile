COMPOSE_FILES := -f docker-compose.yml
OS := $(shell uname)
NETWORK := $(shell docker network ls | grep todo)

start: up
.PHONY: start

stop: kill
.PHONY: stop

up:

ifeq ($(NETWORK),)
	docker network create todo
endif

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
