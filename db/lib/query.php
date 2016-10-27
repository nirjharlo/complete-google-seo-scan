<?php
/**
 *
 * @package: onpage-seo-checker/db/lib/
 * on: 11.07.2016
 * @since 2.5
 * @called_in: ONPAGE_
 *
 * Database queries.
 *
 */
if ( ! defined( 'ABSPATH' ) ) exit;



/**
 *
 * Define the base class for menu and settings
 *
 */
if ( ! class_exists( 'ONSEOCHECK_QUERY' ) ) {

	class ONSEOCHECK_QUERY {



		private $type;



		public function __construct( $type ) {

			$this->type = $type;
		}
	}
} ?>
