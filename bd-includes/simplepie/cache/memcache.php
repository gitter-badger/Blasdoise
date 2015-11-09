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
 * Caches data to memcache
 *
 * Registered for URLs with the "memcache" protocol
 *
 * For example, `memcache://localhost:11211/?timeout=3600&prefix=sp_` will
 * connect to memcache on `localhost` on port 11211. All tables will be
 * prefixed with `sp_` and data will expire after 3600 seconds
 *
 * @package SimplePie
 * @subpackage Caching
 * @uses Memcache
 */
class SimplePie_Cache_Memcache implements SimplePie_Cache_Base
{
	/**
	 * Memcache instance
	 *
	 * @var Memcache
	 */
	protected $cache;

	/**
	 * Options
	 *
	 * @var array
	 */
	protected $options;

	/**
	 * Cache name
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Create a new cache object
	 *
	 * @param string $location Location string (from SimplePie::$cache_location)
	 * @param string $name Unique ID for the cache
	 * @param string $type Either TYPE_FEED for SimplePie data, or TYPE_IMAGE for image data
	 */
	public function __construct($location, $name, $type)
	{
		$this->options = array(
			'host' => '127.0.0.1',
			'port' => 11211,
			'extras' => array(
				'timeout' => 3600, // one hour
				'prefix' => 'simplepie_',
			),
		);
		$parsed = SimplePie_Cache::parse_URL($location);
		$this->options['host'] = empty($parsed['host']) ? $this->options['host'] : $parsed['host'];
		$this->options['port'] = empty($parsed['port']) ? $this->options['port'] : $parsed['port'];
		$this->options['extras'] = array_merge($this->options['extras'], $parsed['extras']);
		$this->name = $this->options['extras']['prefix'] . md5("$name:$type");

		$this->cache = new Memcache();
		$this->cache->addServer($this->options['host'], (int) $this->options['port']);
	}

	/**
	 * Save data to the cache
	 *
	 * @param array|SimplePie $data Data to store in the cache. If passed a SimplePie object, only cache the $data property
	 * @return bool Successfulness
	 */
	public function save($data)
	{
		if ($data instanceof SimplePie)
		{
			$data = $data->data;
		}
		return $this->cache->set($this->name, serialize($data), MEMCACHE_COMPRESSED, (int) $this->options['extras']['timeout']);
	}

	/**
	 * Retrieve the data saved to the cache
	 *
	 * @return array Data for SimplePie::$data
	 */
	public function load()
	{
		$data = $this->cache->get($this->name);

		if ($data !== false)
		{
			return unserialize($data);
		}
		return false;
	}

	/**
	 * Retrieve the last modified time for the cache
	 *
	 * @return int Timestamp
	 */
	public function mtime()
	{
		$data = $this->cache->get($this->name);

		if ($data !== false)
		{
			// essentially ignore the mtime because Memcache expires on it's own
			return time();
		}

		return false;
	}

	/**
	 * Set the last modified time to the current time
	 *
	 * @return bool Success status
	 */
	public function touch()
	{
		$data = $this->cache->get($this->name);

		if ($data !== false)
		{
			return $this->cache->set($this->name, $data, MEMCACHE_COMPRESSED, (int) $this->duration);
		}

		return false;
	}

	/**
	 * Remove the cache
	 *
	 * @return bool Success status
	 */
	public function unlink()
	{
		return $this->cache->delete($this->name, 0);
	}
}
