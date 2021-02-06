.PHONY: ci cs test phpunit psalm stan

ci: test cs
cs: stan psalm
test: phpunit

phpunit:
	php ./vendor/bin/phpunit -c phpunit.xml.dist

coverage-html:
	php ./vendor/bin/phpunit -c phpunit.xml.dist --coverage-html=./build/coverage/html

psalm:
	php ./vendor/bin/psalm

psalm-baseline:
	php ./vendor/bin/psalm --set-baseline=psalm-baseline.xml

stan:
	php ./vendor/bin/phpstan analyse -c phpstan.neon --no-progress

stan-baseline:
	php ./vendor/bin/phpstan analyse -c phpstan.neon --generate-baseline

rector-dry-run:
	php ./vendor/bin/rector process --dry-run