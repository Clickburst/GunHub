<?php
/**
 * Created by PhpStorm.
 * User: PerfectSolution, Patrick Tolvstein
 * Date: 18/04/2018
 * Time: 20.12
 */

namespace GunHub\Infrastructure;

use GunHub\Core\Provider;


/**
 * Class ModulesProvider
 *
 * @package GunHub\Modules
 */
final class InfrastructureProvider {

    use Provider;

    /**
     * Instantiate modules
     *
     * @return array
     */
    protected function get_modules() {
        return [
            Listing::class,
            ListingCategory::class,
            ListingCondition::class,
            ListingCaliber::class,
            ListingState::class,
        ];
    }
}
