<?php
/**
 * Created by PhpStorm.
 * User: PerfectSolution, Patrick Tolvstein
 * Date: 02/05/2018
 * Time: 08.57
 */

namespace GunHub\Core;

/**
 * Trait Provider
 *
 * @package GunHub\Core
 */
trait Provider {
	use Module;

	/**
	 * @return void
	 */
	public function init() {
		foreach ($this->get_modules() as $module) {
			$module::get_instance();
		}
	}

	/**
	 * Return an array of modules
	 * @return array
	 */
	abstract protected function get_modules();
}