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
 * Handles `<media:rating>` or `<itunes:explicit>` tags as defined in Media RSS and iTunes RSS respectively
 *
 * Used by {@see SimplePie_Enclosure::get_rating()} and {@see SimplePie_Enclosure::get_ratings()}
 *
 * This class can be overloaded with {@see SimplePie::set_rating_class()}
 *
 * @package SimplePie
 * @subpackage API
 */
class SimplePie_Rating
{
	/**
	 * Rating scheme
	 *
	 * @var string
	 * @see get_scheme()
	 */
	var $scheme;

	/**
	 * Rating value
	 *
	 * @var string
	 * @see get_value()
	 */
	var $value;

	/**
	 * Constructor, used to input the data
	 *
	 * For documentation on all the parameters, see the corresponding
	 * properties and their accessors
	 */
	public function __construct($scheme = null, $value = null)
	{
		$this->scheme = $scheme;
		$this->value = $value;
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
	 * Get the organizational scheme for the rating
	 *
	 * @return string|null
	 */
	public function get_scheme()
	{
		if ($this->scheme !== null)
		{
			return $this->scheme;
		}
		else
		{
			return null;
		}
	}

	/**
	 * Get the value of the rating
	 *
	 * @return string|null
	 */
	public function get_value()
	{
		if ($this->value !== null)
		{
			return $this->value;
		}
		else
		{
			return null;
		}
	}
}
