<?php

namespace Codememory\Patterns\DTO;

use Codememory\Patterns\Singleton\SingletonTrait;

/**
 *
 * Class AbstractDTO
 *
 * @package Codememory\Patterns\DTO
 *
 * @author  Codememory
 */
abstract class AbstractDTO
{

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Returns an array of data
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return array
     */
    abstract public function getTransformedData(): array;

}