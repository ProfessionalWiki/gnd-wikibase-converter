# GND Wikibase Converter

PHP library that provides services to convert [GND] items in PICA+ format to
a structure ready for import into [Wikibase].

GND Wikibase Converter was created by [Professional.Wiki] for the [German National Library].

[![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/ProfessionalWiki/gnd-wikibase-converter/ci.yml?branch=master)](https://github.com/ProfessionalWiki/gnd-wikibase-converter/actions?query=workflow%3ACI)
[![Type Coverage](https://shepherd.dev/github/ProfessionalWiki/gnd-wikibase-converter/coverage.svg)](https://shepherd.dev/github/ProfessionalWiki/gnd-wikibase-converter)
[![codecov](https://codecov.io/gh/ProfessionalWiki/gnd-wikibase-converter/branch/master/graph/badge.svg?token=GnOG3FF16Z)](https://codecov.io/gh/ProfessionalWiki/gnd-wikibase-converter)
[![Latest Stable Version](https://poser.pugx.org/dnb/wikibase-converter/version.png)](https://packagist.org/packages/dnb/wikibase-converter)
[![Download count](https://poser.pugx.org/dnb/wikibase-converter/d/total.png)](https://packagist.org/packages/dnb/wikibase-converter)

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

## Usage

PICA+ JSON to Wikibase-like data structure

```php
use DNB\WikibaseConverter\PicaConverter;
$gndItem = PicaConverter::newWithDefaultMapping()->picaJsonToGndItem( $string );

$gndItem->getPropertyIds();
$gndItem->getStatementsForProperty( 'P123' );
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
