.PHONY: help backup clear clear-cache clear-storage composer-dump-autoload
.PHONY: install update 
.PHONY: check php-cs-fixer psalm phparkitect phpmd phpcbf deptrac phpstan 
.PHONY: start start-server-php start-server-symfony 
.PHONY: stop stop-server-php stop-server-symfony
.PHONY: test test-phpunit
.DEFAULT_GOAL := help

#STORAGE_PATH=./temp
STORAGE_PATH=/tmp/tic-tac-toe-api

help:
	@awk 'BEGIN {FS = ":.*#"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n\n"} /^[a-zA-Z0-9_-]+:.*?#/ { printf "  \033[36m%-27s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST); printf "\n"

install: ## Install packages (composer install)
install: install-packages install-tools

install-packages:
	@cd . \
	&& composer clearcache \
	&& rm composer.lock \
	&& rm -rf vendor/* \
	&& composer install

install-tools:
	@cd ./tools/php-cs-fixer \
	&& composer clearcache \
	&& rm composer.lock \
	&& rm -rf vendor/* \
	&& composer install

backup: ## Backup codebase (*_YYYYMMDD_HHMM.tar.gz)
backup: archive=`pwd`_`date +'%Y%m%d_%H%M'`.tar.gz
backup:
	@tar -czf $(archive) --exclude=tools --exclude=var/* --exclude=vendor/* *
	@ls -l `pwd`*.tar.gz

clear: ## Clear cache and temporary storage
clear: clear-cache clear-storage composer-dump-autoload

clear-cache:
	@rm -fr var/cache/*

clear-storage:
	@rm -f $(STORAGE_PATH)/*

composer-dump-autoload:
	composer dump-autoload

check: ## Check codebase (php-cs-fixer, psalm, phpmd)
check: php-cs-fixer psalm phpmd

php-cs-fixer:
	./tools/php-cs-fixer/vendor/bin/php-cs-fixer fix --dry-run --verbose --diff --config=.php-cs-fixer.php

psalm:
	./vendor/bin/psalm --show-info=true

phpmd:
	./vendor/bin/phpmd src,tests ansi phpmd.xml --exclude src/Kernel.php

phparkitect:
	./vendor/bin/phparkitect check

phpcbf:
	./vendor/bin/phpcbf -s

deptrac:
	./vendor/bin/deptrac --formatter=graphviz

phpstan:
	./vendor/bin/phpstan analyse src tests --memory-limit 256M

start: ## Start server
start: start-server-php

start-server-php:
	php -S localhost:8000 -t public/

start-server-symfony:
	symfony server:start

stop: ## Stop server
stop: stop-server-php

stop-server-php:
	killall php

stop-server-symfony:
	symfony server:stop

test: ## Test codebase (phpunit)
test: test-phpunit
	
test-phpunit:
	@./vendor/bin/phpunit

update: ## Update packages (composer update)
update: update-packages update-tools

update-packages:
	@cd . && composer update

update-tools:
	@cd ./tools/php-cs-fixer && composer update
