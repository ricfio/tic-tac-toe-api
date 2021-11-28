#STORAGE_PATH=./temp
STORAGE_PATH=/tmp/tic-tac-toe-api

.PHONY: help
help : Makefile
	@echo "Usage:  make [TARGET]"
	@echo
	@echo "Targets:"
	@sed -n 's/^## HELP://p' $<
	@echo

## HELP:  install            Install packages (composer install)
.PHONY: install
install: install-packages install-tools

.PHONY: install-packages
install-packages:
	@cd . \
	&& composer clearcache \
	&& rm composer.lock \
	&& rm -rf vendor/* \
	&& composer install

.PHONY: install-tools
install-tools:
	@cd ./tools/php-cs-fixer \
	&& composer clearcache \
	&& rm composer.lock \
	&& rm -rf vendor/* \
	&& composer install

## HELP:  backup             Backup codebase (*_YYYYMMDD_HHMM.tar.gz)
.PHONY: backup
backup: archive=`pwd`_`date +'%Y%m%d_%H%M'`.tar.gz
backup:
	@tar -czf $(archive) --exclude=tools --exclude=var/* --exclude=vendor/* *
	@ls -l `pwd`*.tar.gz

## HELP:  clear              Clear cache and temporary storage
.PHONY: clear
clear: clear-cache clear-storage composer-dump-autoload

.PHONY: clear-cache
clear-cache:
	@rm -fr var/cache/*

.PHONY: clear-storage
clear-storage:
	@rm -f $(STORAGE_PATH)/*

.PHONY: composer-dump-autoload
composer-dump-autoload:
	composer dump-autoload

## HELP:  check              Check codebase (php-cs-fixer, psalm, phparkitect)
.PHONY: check
check: php-cs-fixer psalm

.PHONY: php-cs-fixer
php-cs-fixer:
	./tools/php-cs-fixer/vendor/bin/php-cs-fixer fix --dry-run --verbose --diff --config=.php-cs-fixer.php

.PHONY: psalm
psalm:
	./vendor/bin/psalm --show-info=true

.PHONY: phparkitect
phparkitect:
	./vendor/bin/phparkitect check

.PHONY: phpmd
phpmd:
#	./vendor/bin/phpmd src,tests ansi cleancode,codesize,controversial,design,naming,unusedcode --exclude src/Kernel.php
	./vendor/bin/phpmd src ansi phpmd.xml --exclude src/Kernel.php

.PHONY: phpcbf
phpcbf:
	./vendor/bin/phpcbf -s

.PHONY: deptrac
deptrac:
	./vendor/bin/deptrac --formatter=graphviz

.PHONY: phpstan
phpstan:
	./vendor/bin/phpstan analyse src tests --memory-limit 256M

## HELP:  start              Start server
.PHONY: start
start: start-server-php

.PHONY: start-server-php
start-server-php:
	php -S localhost:8000 -t public/

.PHONY: start-server-symfony
start-server-symfony:
	symfony server:start

## HELP:  stop               Stop server
.PHONY: stop
stop: stop-server-php

.PHONY: stop-server-php
stop-server-php:
	killall php

.PHONY: stop-server-symfony
stop-server-symfony:
	symfony server:stop

## HELP:  test               Test codebase (phpunit)
.PHONY: test
test: test-phpunit
	
.PHONY: test-phpunit
test-phpunit:
	@./vendor/bin/phpunit

## HELP:  update             Update packages (composer update)
.PHONY: update
update: update-packages update-tools

.PHONY: update-packages
update-packages:
	@cd . && composer update

.PHONY: update-tools
update-tools:
	@cd ./tools/php-cs-fixer && composer update
