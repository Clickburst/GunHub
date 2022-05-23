<?php

namespace GunHub\Core;

/**
 * Trait Module
 *
 * @package GunHub\Core
 */
trait Module {

    use Singleton;

    /**
     * Module constructor.
     */
    private function __construct() {
        $this->init();
    }

    /**
     * @return void
     */
    abstract public function init();
}