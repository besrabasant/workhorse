<?php

namespace Workhorse\Contracts;

use StoutLogic\AcfBuilder\FieldBuilder;
use StoutLogic\AcfBuilder\FlexibleContentBuilder;

interface CanSetLocationExplicitly {
	/**
	 * @param FieldBuilder | FlexibleContentBuilder | mixed $builder
	 */
	public function setLocation( $builder ): void;
}