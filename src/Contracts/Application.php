<?php

namespace Workhorse\Contracts;

interface Application
{
    public function setIdentifier(string  $identifier): void;

    public function getIdentifier(): string;
}
