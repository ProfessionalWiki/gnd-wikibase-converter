.PHONY: ci cs test phpunit psalm stan

ci: test cs
cs: stan
test: phpunit

phpunit:
	php8.0 ./vendor/bin/phpunit -c phpunit.xml.dist

coverage-html:
	php8.0 ./vendor/bin/phpunit -c phpunit.xml.dist --coverage-html=./build/coverage/html

psalm:
	php8.0 ./vendor/bin/psalm

stan:
	php8.0 ./vendor/bin/phpstan analyse -c phpstan.neon --no-progress

stan-baseline:
	php8.0 ./vendor/bin/phpstan analyse -c phpstan.neon --generate-baseline