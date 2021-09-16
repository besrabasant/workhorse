<?php

namespace Workhorse\InfraStructure\Concerns;

use Illuminate\Support\Str;
use Workhorse\Contracts\HasAcfFields;
use Workhorse\Contracts\UsesAcfBlockLocation;
use Workhorse\InfraStructure\AcfBlocks;
use Workhorse\InfraStructure\AcfFieldGroup;

/**
 * @mixin HasAcfFields
 * @mixin AcfBlocks
 */
trait RegistersAcfField {

	public function registerAcfFields(): void {

		$fieldGroupClass = $this->acfFields();

		/** @var UsesAcfBlockLocation|AcfFieldGroup $fieldGroup */
		$fieldGroup = new $fieldGroupClass;

		$fieldGroup->useAcfBlockLocation( Str::slug( $this->name() ) );

		$fieldGroup->register();
	}
}