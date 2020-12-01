.PHONY: ci cs test phpunit psalm phpstan

ci: phpstan phpunit psalm
cs: phpstan psalm
test: phpunit

phpunit:
	php8.0 ./vendor/bin/phpunit -c phpunit.xml.dist

coverage-html:
	php8.0 ./vendor/bin/phpunit -c phpunit.xml.dist --coverage-html=./build/coverage/html

psalm:
	php8.0 ./vendor/bin/psalm

phpstan:
	php8.0 ./vendor/bin/phpstan analyse -c phpstan.neon --no-progress
