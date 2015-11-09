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
 * Handles `<media:text>` captions as defined in Media RSS.
 *
 * Used by {@see SimplePie_Enclosure::get_caption()} and {@see SimplePie_Enclosure::get_captions()}
 *
 * This class can be overloaded with {@see SimplePie::set_caption_class()}
 *
 * @package SimplePie
 * @subpackage API
 */
class SimplePie_Caption
{
	/**
	 * Content type
	 *
	 * @var string
	 * @see get_type()
	 */
	var $type;

	/**
	 * Language
	 *
	 * @var string
	 * @see get_language()
	 */
	var $lang;

	/**
	 * Start time
	 *
	 * @var string
	 * @see get_starttime()
	 */
	var $startTime;

	/**
	 * End time
	 *
	 * @var string
	 * @see get_endtime()
	 */
	var $endTime;

	/**
	 * Caption text
	 *
	 * @var string
	 * @see get_text()
	 */
	var $text;

	/**
	 * Constructor, used to input the data
	 *
	 * For documentation on all the parameters, see the corresponding
	 * properties and their accessors
	 */
	public function __construct($type = null, $lang = null, $startTime = null, $endTime = null, $text = null)
	{
		$this->type = $type;
		$this->lang = $lang;
		$this->startTime = $startTime;
		$this->endTime = $endTime;
		$this->text = $text;
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
	 * Get the end time
	 *
	 * @return string|null Time in the format 'hh:mm:ss.SSS'
	 */
	public function get_endtime()
	{
		if ($this->endTime !== null)
		{
			return $this->endTime;
		}
		else
		{
			return null;
		}
	}

	/**
	 * Get the language
	 *
	 * @link http://tools.ietf.org/html/rfc3066
	 * @return string|null Language code as per RFC 3066
	 */
	public function get_language()
	{
		if ($this->lang !== null)
		{
			return $this->lang;
		}
		else
		{
			return null;
		}
	}

	/**
	 * Get the start time
	 *
	 * @return string|null Time in the format 'hh:mm:ss.SSS'
	 */
	public function get_starttime()
	{
		if ($this->startTime !== null)
		{
			return $this->startTime;
		}
		else
		{
			return null;
		}
	}

	/**
	 * Get the text of the caption
	 *
	 * @return string|null
	 */
	public function get_text()
	{
		if ($this->text !== null)
		{
			return $this->text;
		}
		else
		{
			return null;
		}
	}

	/**
	 * Get the content type (not MIME type)
	 *
	 * @return string|null Either 'text' or 'html'
	 */
	public function get_type()
	{
		if ($this->type !== null)
		{
			return $this->type;
		}
		else
		{
			return null;
		}
	}
}

