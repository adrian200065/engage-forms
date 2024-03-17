<?php


namespace engagewp\engageforms\ef2\Services;


use engagewp\engageforms\ef2\EngageFormsV2Contract;

interface ServiceContract
{

	/**
	 * Register a service for container
	 *
	 * Return instance of class container should provide.
	 *
	 * @since  1.8.0
	 *
	 * @param EngageFormsV2Contract $container
	 *
	 * @return EngageFormsV2Contract
	 */
	public function register(EngageFormsV2Contract $container );

	/**
	 * Get identifier for service
	 *
	 * @since  1.8.0
	 *
	 * @return string
	 */
	public function getIdentifier();

	/**
	 * Is service a singleton or not?
	 *
	 * @since  1.8.0
	 *
	 * @return bool
	 */
	public function isSingleton();

}