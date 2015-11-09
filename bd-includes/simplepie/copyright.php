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
 * Manages `<media:copyright>` copyright tags as defined in Media RSS
 *
 * Used by {@see SimplePie_Enclosure::get_copyright()}
 *
 * This class can be overloaded with {@see SimplePie::set_copyright_class()}
 *
 * @package SimplePie
 * @subpackage API
 */
class SimplePie_Copyright
{
	/**
	 * Copyright URL
	 *
	 * @var string
	 * @see get_url()
	 */
	var $url;

	/**
	 * Attribution
	 *
	 * @var string
	 * @see get_attribution()
	 */
	var $label;

	/**
	 * Constructor, used to input the data
	 *
	 * For documentation on all the parameters, see the corresponding
	 * properties and their accessors
	 */
	public function __construct($url = null, $label = null)
	{
		$this->url = $url;
		$this->label = $label;
	}

	/**
	 * String-ified version
	 *
	 * @return string
	 */
	public function __toString()
	{
		// There is no $this->data here
		return md5(serialize($this));
	}

	/**
	 * Get the copyright URL
	 *
	 * @return string|null URL to copyright information
	 */
	public function get_url()
	{
		if ($this->url !== null)
		{
			return $this->url;
		}
		else
		{
			return null;
		}
	}

	/**
	 * Get the attribution text
	 *
	 * @return string|null
	 */
	public function get_attribution()
	{
		if ($this->label !== null)
		{
			return $this->label;
		}
		else
		{
			return null;
		}
	}
}

