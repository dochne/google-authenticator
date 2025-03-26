SHELL = /bin/bash
### https://makefiletutorial.com/

docker := docker run -it --rm -v $(PWD):/app -w /app php:8.2-cli-alpine
composer := $(docker) php composer.phar

bash:
	$(docker) bash

composer-download:
	test -f composer.phar || wget https://getcomposer.org/download/latest-stable/composer.phar

composer-i:
	@make composer-download
	$(composer) install --prefer-dist --no-scripts

composer-u:
	$(composer) update --prefer-dist --no-scripts $(name)

cs-fix:
	$(composer) cs-fix

cs-check:
	$(composer) cs-check

phpstan:
	$(composer) phpstan

phpunit:
	$(composer) phpunit

test:
	$(composer) cs-check
	$(composer) phpstan
	$(composer) phpunit
