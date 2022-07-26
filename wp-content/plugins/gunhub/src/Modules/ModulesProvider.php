<?php
/**
 * Created by PhpStorm.
 * User: PerfectSolution, Patrick Tolvstein
 * Date: 18/04/2018
 * Time: 20.12
 */

namespace GunHub\Modules;


use GunHub\Core\Provider;

/**
 * Class ModulesProvider
 *
 * @package GunHub\Modules
 */
final class ModulesProvider {

	use Provider;

	/**
	 * Instantiate modules
	 *
	 * @return array
	 */
	protected function get_modules() {
		return [
			Listing::class,
            SearchForm::class,
            Formidable::class,
            Woocommerce::class,
            ListingFrontendBuilder::class,
            HeaderButtonShortcode::class,
            ReportAbuse::class,
		];
	}
}
