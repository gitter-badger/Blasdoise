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
 * Manages all author-related data
 *
 * Used by {@see SimplePie_Item::get_author()} and {@see SimplePie::get_authors()}
 *
 * This class can be overloaded with {@see SimplePie::set_author_class()}
 *
 * @package SimplePie
 * @subpackage API
 */
class SimplePie_Author
{
	/**
	 * Author's name
	 *
	 * @var string
	 * @see get_name()
	 */
	var $name;

	/**
	 * Author's link
	 *
	 * @var string
	 * @see get_link()
	 */
	var $link;

	/**
	 * Author's email address
	 *
	 * @var string
	 * @see get_email()
	 */
	var $email;

	/**
	 * Constructor, used to input the data
	 *
	 * @param string $name
	 * @param string $link
	 * @param string $email
	 */
	public function __construct($name = null, $link = null, $email = null)
	{
		$this->name = $name;
		$this->link = $link;
		$this->email = $email;
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
	 * Author's name
	 *
	 * @return string|null
	 */
	public function get_name()
	{
		if ($this->name !== null)
		{
			return $this->name;
		}
		else
		{
			return null;
		}
	}

	/**
	 * Author's link
	 *
	 * @return string|null
	 */
	public function get_link()
	{
		if ($this->link !== null)
		{
			return $this->link;
		}
		else
		{
			return null;
		}
	}

	/**
	 * Author's email address
	 *
	 * @return string|null
	 */
	public function get_email()
	{
		if ($this->email !== null)
		{
			return $this->email;
		}
		else
		{
			return null;
		}
	}
}

