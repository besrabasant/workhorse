<?php

namespace Workhorse\InfraStructure\Concerns;

use StoutLogic\AcfBuilder\FieldBuilder;
use StoutLogic\AcfBuilder\FlexibleContentBuilder;
use Workhorse\Contracts\CanSetLocationExplicitly;
use Workhorse\Contracts\UsesAcfBlockLocation;

/**
 * @mixin CanSetLocationExplicitly
 * @mixin UsesAcfBlockLocation
 */
trait ResolvesAcfBlockLocation {

	/** @var string */
	protected $block_location;

	/**
	 * @param FieldBuilder | FlexibleContentBuilder | mixed $builder
	 */
	public function setLocation( $builder ): void {
		$builder->setLocation( 'block', '==', 'acf/' . $this->block_location );
	}

	/**
	 * @param string $location
	 */
	public function useAcfBlockLocation( string $location ): void {
		$this->block_location = $location;
	}

}