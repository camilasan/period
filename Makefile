app_name=$(notdir $(CURDIR))
build_tools_directory=$(CURDIR)/build/tools
source_build_directory=$(CURDIR)/build/artifacts/source
source_package_name=$(source_build_directory)/$(app_name)
appstore_build_directory=$(CURDIR)/build/artifacts
appstore_package_name=$(appstore_build_directory)/$(app_name)
npm=$(shell which npm 2> /dev/null)
composer=$(shell which composer 2> /dev/null)

# Installs and updates the composer dependencies. If composer is not installed
# a copy is fetched from the web
.PHONY: composer
composer:
ifeq (, $(composer))
	@echo "No composer command available, downloading a copy from the web"
	mkdir -p $(build_tools_directory)
	curl -sS https://getcomposer.org/installer | php
	mv composer.phar $(build_tools_directory)
	php $(build_tools_directory)/composer.phar install --prefer-dist
else
	composer install --prefer-dist
endif

default: build

clean-dist:
	rm -rf node_modules/

install-deps: install-deps-js
	composer install

install-deps-nodev: install-deps-js
	composer install --no-dev

install-deps-js:
	npm ci

build: clean-dist install-deps build-js

release: clean-dist install-deps-nodev build-js

lint: lint-js lint-php

lint-js:
	npm run lint
	npm run stylelint

lint-php:
	composer run lint 1>/dev/null
	composer run cs:check

build-js: install-deps-js
	npm run build

build-js-dev: install-deps
	npm run dev

watch:
	npm run watch

test: composer
	phpunit -c tests/phpunit.xml
	phpunit -c tests/phpunit.integration.xml

test-js: install-deps
	npm run test
