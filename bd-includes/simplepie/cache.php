<?php
/**
 * SimplePie
 *
 * A PHP-Based RSS and Atom Feed Framework.
 * Takes the hard work out of managing a complete RSS/Atom solution.
 *
 * @package SimplePie
 */

/**
 * Used to create cache objects
 *
 * This class can be overloaded with {@see SimplePie::set_cache_class()},
 * although the preferred way is to create your own handler
 * via {@see register()}
 *
 * @package SimplePie
 * @subpackage Caching
 */
class SimplePie_Cache
{
	/**
	 * Cache handler classes
	 *
	 * These receive 3 parameters to their constructor, as documented in
	 * {@see register()}
	 * @var array
	 */
	protected static $handlers = array(
		'mysql' => 'SimplePie_Cache_MySQL',
		'memcache' => 'SimplePie_Cache_Memcache',
	);

	/**
	 * Don't call the constructor. Please.
	 */
	private function __construct() { }

	/**
	 * Create a new SimplePie_Cache object
	 *
	 * @param string $location URL location (scheme is used to determine handler)
	 * @param string $filename Unique identifier for cache object
	 * @param string $extension 'spi' or 'spc'
	 * @return SimplePie_Cache_Base Type of object depends on scheme of `$location`
	 */
	public static function get_handler($location, $filename, $extension)
	{
		$type = explode(':', $location, 2);
		$type = $type[0];
		if (!empty(self::$handlers[$type]))
		{
			$class = self::$handlers[$type];
			return new $class($location, $filename, $extension);
		}

		return new SimplePie_Cache_File($location, $filename, $extension);
	}

	/**
	 * Create a new SimplePie_Cache object
	 *
	 * @deprecated Use {@see get_handler} instead
	 */
	public function create($location, $filename, $extension)
	{
		trigger_error('Cache::create() has been replaced with Cache::get_handler(). Switch to the registry system to use this.', E_USER_DEPRECATED);
		return self::get_handler($location, $filename, $extension);
	}

	/**
	 * Register a handler
	 *
	 * @param string $type DSN type to register for
	 * @param string $class Name of handler class. Must implement SimplePie_Cache_Base
	 */
	public static function register($type, $class)
	{
		self::$handlers[$type] = $class;
	}

	/**
	 * Parse a URL into an array
	 *
	 * @param string $url
	 * @return array
	 */
	public static function parse_URL($url)
	{
		$params = parse_url($url);
		$params['extras'] = array();
		if (isset($params['query']))
		{
			parse_str($params['query'], $params['extras']);
		}
		return $params;
	}
}
