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
 * Base for cache objects
 *
 * Classes to be used with {@see SimplePie_Cache::register()} are expected
 * to implement this interface.
 *
 * @package SimplePie
 * @subpackage Caching
 */
interface SimplePie_Cache_Base
{
	/**
	 * Feed cache type
	 *
	 * @var string
	 */
	const TYPE_FEED = 'spc';

	/**
	 * Image cache type
	 *
	 * @var string
	 */
	const TYPE_IMAGE = 'spi';

	/**
	 * Create a new cache object
	 *
	 * @param string $location Location string (from SimplePie::$cache_location)
	 * @param string $name Unique ID for the cache
	 * @param string $type Either TYPE_FEED for SimplePie data, or TYPE_IMAGE for image data
	 */
	public function __construct($location, $name, $type);

	/**
	 * Save data to the cache
	 *
	 * @param array|SimplePie $data Data to store in the cache. If passed a SimplePie object, only cache the $data property
	 * @return bool Successfulness
	 */
	public function save($data);

	/**
	 * Retrieve the data saved to the cache
	 *
	 * @return array Data for SimplePie::$data
	 */
	public function load();

	/**
	 * Retrieve the last modified time for the cache
	 *
	 * @return int Timestamp
	 */
	public function mtime();

	/**
	 * Set the last modified time to the current time
	 *
	 * @return bool Success status
	 */
	public function touch();

	/**
	 * Remove the cache
	 *
	 * @return bool Success status
	 */
	public function unlink();
}
