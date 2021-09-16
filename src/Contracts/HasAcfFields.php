<?php

namespace Workhorse\Contracts;

interface HasAcfFields {
	public function acfFields(): string;

	public function registerAcfFields(): void;
}