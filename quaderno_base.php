<?php
/**
* Quaderno Base
*
* @package   Quaderno PHP
* @author    Quaderno <support@quaderno.io>
* @copyright Copyright (c) 2021, Quaderno
* @license   https://opensource.org/licenses/MIT The MIT License
*/

/* General interface that implements the calls to the message coding and transport library */
abstract class QuadernoBase
{
	protected static $api_key = null;
	protected static $api_url = null;
	protected static $api_version = null;

	/**
	 * @param string $key
	 * @param string $url
	 * @param string|null $version
	 *
	 * @return void
	 */
	public static function init($key, $url, $version = null)
	{
		self::$api_key = $key;
		self::$api_url = $url;
		self::$api_version = $version;
	}

	/**
	 * @param string $method
	 * @param string $model
	 * @param string $id
	 * @param array|null $params
	 * @param array|null $data
	 *
	 * @return array
	 */
	public static function apiCall($method, $model, $id = '', $params = null, $data = null)
	{
		$url = self::$api_url . $model . ($id != '' ? '/' . $id : '') . '.json';
		if (isset($params)) $url .= '?'.http_build_query($params);
		return QuadernoJSON::exec($url, $method, self::$api_key, 'foo', self::$api_version, $data);
	}

	/**
	 * @return bool
	 */
	public static function ping()
	{
		return self::responseIsValid(self::apiCall('GET', 'ping'));
	}

	/**
	 * @param string $model
	 * @param string $id
	 *
	 * @return array
	 */
	public static function delete($model, $id)
	{
		return self::apiCall('DELETE', $model, $id);
	}

	/**
	 * @param string $parentmodel
	 * @param string $parentid
	 * @param string $model
	 * @param string $id
	 *
	 * @return array
	 */
	public static function deleteNested($parentmodel, $parentid, $model, $id)
	{
		return self::delete($parentmodel . '/' . $parentid . '/' . $model, $id);
	}

	/**
	 * @param string $model
	 * @param string $id
	 *
	 * @return array
	 */
	public static function deliver($model, $id)
	{
		return self::apiCall('GET', $model, $id . '/deliver');
	}

	/**
	 * @param string $model
	 * @param array|null $params
	 *
	 * @return array
	 */
	public static function find($model, $params = null)
	{
		return self::apiCall('GET', $model, '', $params);
	}

	/**
	 * @param string $model
	 * @param string $id
	 *
	 * @return array
	 */
	public static function findByID($model, $id)
	{
		return self::apiCall('GET', $model, $id);
	}

	/**
	 * @param string $model
	 * @param array|null $data
	 * @param string $id
	 *
	 * @return array
	 */
	public static function save($model, $data, $id)
	{
		return self::apiCall(($id ? 'PUT' : 'POST'), $model, $id, null, $data);
	}

	/**
	 * @param string $id
	 * @param string $model
	 * @param string $gateway
	 *
	 * @return array
	 */
	public static function retrieve($id, $model, $gateway = 'stripe')
	{
		return self::apiCall('GET', $gateway . '/' . $model, $id);
	}

	/**
	 * @param string $parentmodel
	 * @param string $parentid
	 * @param string $model
	 * @param array|null $data
	 *
	 * @return array
	 */
	public static function saveNested($parentmodel, $parentid, $model, $data)
	{
		return self::save($parentmodel . '/' . $parentid . '/' . $model, $data, null);
	}

	/**
	 * @param array $response
	 *
	 * @return bool
	 */
	public static function responseIsValid($response)
	{
		return isset($response) && !$response['error'] && (int)($response['http_code'] / 100) == 2;
	}

	/**
	 * @return null|string
	 */
	public static function getApiKey()
	{
		return self::$api_key;
	}

	/**
	 * @return null|string
	 */
	public static function getApiUrl()
	{
		return self::$api_url;
	}

	/**
	 * @return null|string
	 */
	public static function getApiVersion()
	{
		return self::$api_version;
	}
}
