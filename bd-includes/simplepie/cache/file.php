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
 * Caches data to the filesystem
 *
 * @package SimplePie
 * @subpackage Caching
 */
class SimplePie_Cache_File implements SimplePie_Cache_Base
{
	/**
	 * Location string
	 *
	 * @see SimplePie::$cache_location
	 * @var string
	 */
	protected $location;

	/**
	 * Filename
	 *
	 * @var string
	 */
	protected $filename;

	/**
	 * File extension
	 *
	 * @var string
	 */
	protected $extension;

	/**
	 * File path
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
		$this->location = $location;
		$this->filename = $name;
		$this->extension = $type;
		$this->name = "$this->location/$this->filename.$this->extension";
	}

	/**
	 * Save data to the cache
	 *
	 * @param array|SimplePie $data Data to store in the cache. If passed a SimplePie object, only cache the $data property
	 * @return bool Successfulness
	 */
	public function save($data)
	{
		if (file_exists($this->name) && is_writeable($this->name) || file_exists($this->location) && is_writeable($this->location))
		{
			if ($data instanceof SimplePie)
			{
				$data = $data->data;
			}

			$data = serialize($data);
			return (bool) file_put_contents($this->name, $data);
		}
		return false;
	}

	/**
	 * Retrieve the data saved to the cache
	 *
	 * @return array Data for SimplePie::$data
	 */
	public function load()
	{
		if (file_exists($this->name) && is_readable($this->name))
		{
			return unserialize(file_get_contents($this->name));
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
		if (file_exists($this->name))
		{
			return filemtime($this->name);
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
		if (file_exists($this->name))
		{
			return touch($this->name);
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
		if (file_exists($this->name))
		{
			return unlink($this->name);
		}
		return false;
	}
}
