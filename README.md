# GND Wikibase Converter

PHP library that provides services to convert [GND] items in PICA+ format to
a structure ready for import into [Wikibase].

GND Wikibase Converter was created by [Professional.Wiki] for the [German National Library].

## Installation

This library can be used in your projects via the [Composer dependency manager].

The package name is `dnb/wikibase-converter`. Minimal example of a `composer.json` file:

```json
{
    "require": {
        "dnb/wikibase-converter": "~1.0"
    }
}
```

## Development

Start by installing the project dependencies by executing

    composer update

You can run the tests by executing

    make test

You can run the style checks by executing

    make cs

To run all CI checks, execute

    make ci

You can also invoke PHPUnit directly to pass it arguments, as follows

    vendor/bin/phpunit --filter SomeClassNameOrFilter

## Release notes

### Version 0.1.0

Under development



[Professional.Wiki]: https://professional.wiki
[Composer dependency manager]: https://getcomposer.org/
[German National Library]: https://www.dnb.de/
[Wikibase]: https://wikibase.consulting/what-is-wikibase/
[GND]: https://www.dnb.de/EN/Professionell/Standardisierung/GND/gnd_node.html