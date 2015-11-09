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
 * Manages all category-related data
 *
 * Used by {@see SimplePie_Item::get_category()} and {@see SimplePie_Item::get_categories()}
 *
 * This class can be overloaded with {@see SimplePie::set_category_class()}
 *
 * @package SimplePie
 * @subpackage API
 */
class SimplePie_Category
{
	/**
	 * Category identifier
	 *
	 * @var string
	 * @see get_term
	 */
	var $term;

	/**
	 * Categorization scheme identifier
	 *
	 * @var string
	 * @see get_scheme()
	 */
	var $scheme;

	/**
	 * Human readable label
	 *
	 * @var string
	 * @see get_label()
	 */
	var $label;

	/**
	 * Constructor, used to input the data
	 *
	 * @param string $term
	 * @param string $scheme
	 * @param string $label
	 */
	public function __construct($term = null, $scheme = null, $label = null)
	{
		$this->term = $term;
		$this->scheme = $scheme;
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
	 * Get the category identifier
	 *
	 * @return string|null
	 */
	public function get_term()
	{
		if ($this->term !== null)
		{
			return $this->term;
		}
		else
		{
			return null;
		}
	}

	/**
	 * Get the categorization scheme identifier
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
	 * Get the human readable label
	 *
	 * @return string|null
	 */
	public function get_label()
	{
		if ($this->label !== null)
		{
			return $this->label;
		}
		else
		{
			return $this->get_term();
		}
	}
}

