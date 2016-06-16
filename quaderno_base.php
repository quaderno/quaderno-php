<?php
/**
* Quaderno Base
*
* @package   Quaderno PHP
* @author    Quaderno <hello@quaderno.io>
* @copyright Copyright (c) 2015, Quaderno
* @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
*/

/* General interface that implements the calls to the message coding and transport library */
abstract class QuadernoBase
{

	protected static $api_key = null;
	protected static $api_url = null;
	protected static $api_version = null;

	public static function init($key, $url, $version = null)
	{
		self::$api_key = $key;
		self::$api_url = $url;
		self::$api_version = $version;

	}

	public static function apiCall($method, $model, $id = '', $params = null, $data = null)
	{
		$url = self::$api_url.$model.($id != '' ? '/'.$id : '').'.json';
		if (isset($params)) $url .= '?'.http_build_query($params);
		return QuadernoJSON::exec($url, $method, self::$api_key, 'foo', self::$api_version, $data);
	}

	public static function ping()
	{
		return self::responseIsValid(self::apiCall('GET', 'ping'));
	}

	public static function delete($model, $id)
	{
		return self::apiCall('DELETE', $model, $id);
	}

	public static function deleteNested($parentmodel, $parentid, $model, $id)
	{
		return self::delete($parentmodel.'/'.$parentid.'/'.$model, $id);
	}

	public static function deliver($model, $id)
	{
		return self::apiCall('GET', $model, $id.'/deliver');
	}

	public static function find($model, $params = null)
	{
		return self::apiCall('GET', $model, '', $params);
	}

	public static function findByID($model, $id)
	{
		return self::apiCall('GET', $model, $id);
	}

	public static function save($model, $data, $id)
	{
		return self::apiCall(($id ? 'PUT' : 'POST'), $model, $id, null, $data);
	}

	public static function calculate($params)
	{
		return self::apiCall('GET', 'taxes', 'calculate', $params);
	}

	public static function saveNested($parentmodel, $parentid, $model, $data)
	{
		return self::save($parentmodel.'/'.$parentid.'/'.$model, $data, null);
	}

	public static function responseIsValid($response)
	{
		return isset($response) && !$response['error'] && (int)($response['http_code'] / 100) == 2;
	}
}
