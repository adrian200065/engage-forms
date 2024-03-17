<?php

namespace engagewp\EngageFormsQueries;

use engagewp\EngageContainers\Service\Container;
use engagewp\EngageFormsQuery\Features\FeatureContainer;

/**
 * The EngageFormsQueries
 *
 * Acts as static accessor for feature container
 *
 * @return FeatureContainer
 */
function EngageFormsQueries()
{
	static $EngageFormsQueries;
	if (! $EngageFormsQueries) {
		global $wpdb;
		$EngageFormsQueries = new FeatureContainer(
			new Container(),
			$wpdb
		);
	}

	return $EngageFormsQueries;
}
