<?php

namespace GunHub\Core;


/**
 * Trait Singleton
 *
 * @package GunHub\Core
 */
trait Singleton {

    /**
     * @var static
     */
    private static $_instance;

    /**
     * Singleton constructor.
     */
    private function __construct() {
    }

    /**
     * @return static
     */
    final public static function get_instance() {
        if ( null === self::$_instance ) {
            self::$_instance = new static();
        }

        return self::$_instance;
    }

    /**
     *
     */
    private function __clone() {
    }
}