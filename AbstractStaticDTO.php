<?php

namespace Codememory\Patterns\DTO;

/**
 * Class AbstractStaticDTO
 *
 * @package Codememory\Patterns\DTO
 *
 * @author  Codememory
 */
abstract class AbstractStaticDTO
{

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Set the object to be transformed
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param object $data
     *
     * @return static
     */
    abstract public static function create(object $data): static;

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Returns an array of transformed data
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return array
     */
    abstract public static function transform(): array;

}