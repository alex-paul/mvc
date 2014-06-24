<?php

/**
 * Class ServiceLocator
 *
 * @author Alexandru Paul <rainelf@gmail.com>
 * @version 1.0
 *
 * Class that implements the service locator design pattern. Registers and serves services when they are requested.
 *
 */
class ServiceLocator {

    /**
     * Array containing the set of services that have been registered.
     *
     * @var array $aServices
     */
	public static $aServices = array ();

    /**
     * Method that registers a service that will be served when requested via getService method. The service should have
     * a unique name, otherwise an exeption will the thrown.
     *
     * @param string $sServiceName - name of the service that is to be registered
     * @param $obj - the object representing the service
     * @throws Exception
     * @return void
     */
    public static function registerService($sServiceName, $obj)
	{		
		if (isset($sServiceName) && is_object($obj)) {
			if (is_array(self::$aServices) && !array_key_exists($sServiceName, self :: $aServices)) {
				self::$aServices[$sServiceName] = $obj;
			} else {
		          throw new \Exception('Service ' . $sServiceName . ' already registered.');
			}
		} else {
			throw new \Exception('Invalid parameters provided for service registering.');
		}
	}

    /**
     * Method that returns a requested service. If the requested service was not found, the method throws an error.
     *
     * @param $sServiceName - the requested service name
     * @return mixed
     * @throws Exception
     */
    public static function getService($sServiceName)
	{
		if (isset($sServiceName) && is_string($sServiceName)) {
			if (is_array(self::$aServices) && array_key_exists($sServiceName, self :: $aServices)) {
				return self::$aServices[$sServiceName];
			}
		}
		
		/* service not found */
		throw new \Exception('Service ' . $sServiceName . ' not found.');
		
	}
}
