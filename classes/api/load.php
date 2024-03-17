<?php

/**
 * Loads the Engage Forms REST API
 *
 * @package Engage_Forms
 * @author    Josh Pollock <Josh@EngageWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 EngageWP LLC
 */
class Engage_Forms_API_Load {

	/**
	 * Array of route objects for this collection
	 *
	 * @since 1.4.4
	 *
	 * @var array
	 */
	protected $routes;

	/**
	 * Namespace for this route collection
	 *
	 * @since 1.4.4
	 *
	 * @var string
	 */
	protected $namespace;

	/**
	 * Engage_Forms_API_Load constructor.
	 *
	 * @since 1.4.4
	 *
	 * @param string $namespace Namespace for this route collection
	 */
	public function __construct( $namespace ) {
		$this->namespace = $namespace;
		$this->routes = array();

	}

	/**
	 * Add a route to this collection
	 *
	 * @since 1.4.4
	 *
	 * @param Engage_Forms_API_Route $route
	 */
	public function add_route( Engage_Forms_API_Route $route ){
		$this->routes[] = $route;
	}

	/**
	 * Initialize routes for this namespace
	 *
	 * @since 1.4.4
	 *
	 * @return bool True if loading happened, false if not
	 */
	public function init_routes(){
		if( ! empty( $this->routes ) && ! did_action( "engage_forms_rest_api_init_$this->namespace" ) ){
			/** @var Engage_Forms_API_Route $route */
			foreach ( $this->routes as $route ){
				$route->add_routes( $this->namespace );
			}

			/**
			 * Runs after Engage Forms REST API is loaded
			 *
			 * Dynamic part of hook is the namespace, so may run for each version
			 *
			 * @since 1.4.4
			 *
			 * @param array $routes Route objects that were added.
			 */
			do_action( "engage_forms_rest_api_init_$this->namespace", $this->routes );

			return true;

		}

		return false;

	}

}