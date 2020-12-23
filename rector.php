<?php

declare( strict_types = 1 );

use Rector\Core\Configuration\Option;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function ( ContainerConfigurator $containerConfigurator ): void {
	// get parameters
	$parameters = $containerConfigurator->parameters();

	$parameters->set(
		Option::PATHS,
		[
			__DIR__ . '/src',
		]
	);

	// Define what rule sets will be applied
	$parameters->set(
		Option::SETS,
		[
			SetList::DOWNGRADE_PHP80,
		]
	);

	$parameters->set( Option::PHP_VERSION_FEATURES, '7.4' );
};
