<?php

namespace PonyPanic\Hydrator;

interface HydratorInterface
{
    public static function hydrate(array $data, mixed $object = null): mixed;
}