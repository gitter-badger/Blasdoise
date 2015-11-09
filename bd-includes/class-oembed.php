<?php
/**
 * API for fetching the HTML to embed remote content based on a provided URL.
 * Used internally by the {@link BD_Embed} class, but is designed to be generic.
 *
 * @package Blasdoise
 * @subpackage oEmbed
 */

/**
 * oEmbed class.
 *
 * @package Blasdoise
 * @subpackage oEmbed
 * @since 1.0.0
 */
class BD_oEmbed {
	public $providers = array();
	/**
	 * @static
	 * @var array
	 */
	public static $early_providers = array();

	private $compat_methods = array( '_fetch_with_format', '_parse_json', '_parse_xml', '_parse_body' );

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$providers = array(
			'#http://((m|www)\.)?youtube\.com/watch.*#i'          => array( 'http://www.youtube.com/oembed',                      true  ),
			'#https://((m|www)\.)?youtube\.com/watch.*#i'         => array( 'http://www.youtube.com/oembed?scheme=https',         true  ),
			'#http://((m|www)\.)?youtube\.com/playlist.*#i'       => array( 'http://www.youtube.com/oembed',                      true  ),
			'#https://((m|www)\.)?youtube\.com/playlist.*#i'      => array( 'http://www.youtube.com/oembed?scheme=https',         true  ),
			'#http://youtu\.be/.*#i'                              => array( 'http://www.youtube.com/oembed',                      true  ),
			'#https://youtu\.be/.*#i'                             => array( 'http://www.youtube.com/oembed?scheme=https',         true  ),
			'http://blip.tv/*'                                    => array( 'http://blip.tv/oembed/',                             false ),
			'#https?://(.+\.)?vimeo\.com/.*#i'                    => array( 'http://vimeo.com/api/oembed.{format}',               true  ),
			'#https?://(www\.)?dailymotion\.com/.*#i'             => array( 'http://www.dailymotion.com/services/oembed',         true  ),
			'http://dai.ly/*'                                     => array( 'http://www.dailymotion.com/services/oembed',         false ),
			'#https?://(www\.)?flickr\.com/.*#i'                  => array( 'https://www.flickr.com/services/oembed/',            true  ),
			'#https?://flic\.kr/.*#i'                             => array( 'https://www.flickr.com/services/oembed/',            true  ),
			'#https?://(.+\.)?smugmug\.com/.*#i'                  => array( 'http://api.smugmug.com/services/oembed/',            true  ),
			'#https?://(www\.)?hulu\.com/watch/.*#i'              => array( 'http://www.hulu.com/api/oembed.{format}',            true  ),
			'http://i*.photobucket.com/albums/*'                  => array( 'http://photobucket.com/oembed',                      false ),
			'http://gi*.photobucket.com/groups/*'                 => array( 'http://photobucket.com/oembed',                      false ),
			'#https?://(www\.)?scribd\.com/doc/.*#i'              => array( 'http://www.scribd.com/services/oembed',              true  ),
			'#https?://blasdoise.tv/.*#i'                         => array( 'http://blasdoise.tv/oembed/',                        true  ),
			'#https?://(.+\.)?polldaddy\.com/.*#i'                => array( 'https://polldaddy.com/oembed/',                      true  ),
			'#https?://poll\.fm/.*#i'                             => array( 'https://polldaddy.com/oembed/',                      true  ),
			'#https?://(www\.)?funnyordie\.com/videos/.*#i'       => array( 'http://www.funnyordie.com/oembed',                   true  ),
			'#https?://(www\.)?twitter\.com/.+?/status(es)?/.*#i' => array( 'https://api.twitter.com/1/statuses/oembed.{format}', true  ),
			'#https?://vine.co/v/.*#i'                            => array( 'https://vine.co/oembed.{format}',                    true  ),
 			'#https?://(www\.)?soundcloud\.com/.*#i'              => array( 'http://soundcloud.com/oembed',                       true  ),
			'#https?://(.+?\.)?slideshare\.net/.*#i'              => array( 'https://www.slideshare.net/api/oembed/2',            true  ),
			'#https?://instagr(\.am|am\.com)/p/.*#i'              => array( 'https://api.instagram.com/oembed',                   true  ),
			'#https?://(www\.)?rdio\.com/.*#i'                    => array( 'http://www.rdio.com/api/oembed/',                    true  ),
			'#https?://rd\.io/x/.*#i'                             => array( 'http://www.rdio.com/api/oembed/',                    true  ),
			'#https?://(open|play)\.spotify\.com/.*#i'            => array( 'https://embed.spotify.com/oembed/',                  true  ),
			'#https?://(.+\.)?imgur\.com/.*#i'                    => array( 'http://api.imgur.com/oembed',                        true  ),
			'#https?://(www\.)?meetu(\.ps|p\.com)/.*#i'           => array( 'http://api.meetup.com/oembed',                       true  ),
			'#https?://(www\.)?issuu\.com/.+/docs/.+#i'           => array( 'http://issuu.com/oembed_bd',                         true  ),
			'#https?://(www\.)?collegehumor\.com/video/.*#i'      => array( 'http://www.collegehumor.com/oembed.{format}',        true  ),
			'#https?://(www\.)?mixcloud\.com/.*#i'                => array( 'http://www.mixcloud.com/oembed',                     true  ),
			'#https?://(www\.|embed\.)?ted\.com/talks/.*#i'       => array( 'http://www.ted.com/talks/oembed.{format}',           true  ),
			'#https?://(www\.)?(animoto|video214)\.com/play/.*#i' => array( 'http://animoto.com/oembeds/create',                  true  ),
			'#https?://(.+)\.tumblr\.com/post/.*#i'               => array( 'https://www.tumblr.com/oembed/1.0',                  true  ),
			'#https?://(www\.)?kickstarter\.com/projects/.*#i'    => array( 'https://www.kickstarter.com/services/oembed',        true  ),
			'#https?://kck\.st/.*#i'                              => array( 'https://www.kickstarter.com/services/oembed',        true  ),
			'#https?://cloudup\.com/.*#i'                         => array( 'https://cloudup.com/oembed', true ),
		);

		if ( ! empty( self::$early_providers['add'] ) ) {
			foreach ( self::$early_providers['add'] as $format => $data ) {
				$providers[ $format ] = $data;
			}
		}

		if ( ! empty( self::$early_providers['remove'] ) ) {
			foreach ( self::$early_providers['remove'] as $format ) {
				unset( $providers[ $format ] );
			}
		}

		self::$early_providers = array();

		/**
		 * Filter the list of oEmbed providers.
		 *
		 * Discovery is disabled for users lacking the unfiltered_html capability.
		 * Only providers in this array will be used for those users.
		 *
		 * @since 1.0.0
		 *
		 * @param array $providers An array of popular oEmbed providers.
		 */
		$this->providers = apply_filters( 'oembed_providers', $providers );

		// Fix any embeds that contain new lines in the middle of the HTML which breaks bdautop().
		add_filter( 'oembed_dataparse', array($this, '_strip_newlines'), 10, 3 );
	}

	/**
	 * Make private/protected methods readable for backwards compatibility.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param callable $name      Method to call.
	 * @param array    $arguments Arguments to pass when calling.
	 * @return mixed|bool Return value of the callback, false otherwise.
	 */
	public function __call( $name, $arguments ) {
		if ( in_array( $name, $this->compat_methods ) ) {
			return call_user_func_array( array( $this, $name ), $arguments );
		}
		return false;
	}

	/**
	 * Takes a URL and returns the corresponding oEmbed provider's URL, if there is one.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @see BD_oEmbed::discover()
	 *
	 * @param string        $url  The URL to the content.
	 * @param string|array  $args Optional provider arguments.
	 * @return false|string False on failure, otherwise the oEmbed provider URL.
	 */
	public function get_provider( $url, $args = '' ) {

		$provider = false;

		if ( !isset($args['discover']) )
			$args['discover'] = true;

		foreach ( $this->providers as $matchmask => $data ) {
			list( $providerurl, $regex ) = $data;

			// Turn the asterisk-type provider URLs into regex
			if ( !$regex ) {
				$matchmask = '#' . str_replace( '___wildcard___', '(.+)', preg_quote( str_replace( '*', '___wildcard___', $matchmask ), '#' ) ) . '#i';
				$matchmask = preg_replace( '|^#http\\\://|', '#https?\://', $matchmask );
			}

			if ( preg_match( $matchmask, $url ) ) {
				$provider = str_replace( '{format}', 'json', $providerurl ); // JSON is easier to deal with than XML
				break;
			}
		}

		if ( !$provider && $args['discover'] )
			$provider = $this->discover( $url );

		return $provider;
	}

	/**
	 * Add an oEmbed provider just-in-time when bd_oembed_add_provider() is called
	 * before the 'plugins_loaded' hook.
	 *
	 * The just-in-time addition is for the benefit of the 'oembed_providers' filter.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @see bd_oembed_add_provider()
	 *
	 * @param string $format   Format of URL that this provider can handle. You can use
	 *                         asterisks as wildcards.
	 * @param string $provider The URL to the oEmbed provider..
	 * @param bool   $regex    Optional. Whether the $format parameter is in a regex format.
	 *                         Default false.
	 */
	public static function _add_provider_early( $format, $provider, $regex = false ) {
		if ( empty( self::$early_providers['add'] ) ) {
			self::$early_providers['add'] = array();
		}

		self::$early_providers['add'][ $format ] = array( $provider, $regex );
	}

	/**
	 * Remove an oEmbed provider just-in-time when bd_oembed_remove_provider() is called
	 * before the 'plugins_loaded' hook.
	 *
	 * The just-in-time removal is for the benefit of the 'oembed_providers' filter.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @see bd_oembed_remove_provider()
	 *
	 * @param string $format The format of URL that this provider can handle. You can use
	 *                       asterisks as wildcards.
	 */
	public static function _remove_provider_early( $format ) {
		if ( empty( self::$early_providers['remove'] ) ) {
			self::$early_providers['remove'] = array();
		}

		self::$early_providers['remove'][] = $format;
	}

	/**
	 * The do-it-all function that takes a URL and attempts to return the HTML.
	 *
	 * @see BD_oEmbed::fetch()
	 * @see BD_oEmbed::data2html()
	 *
	 * @param string $url The URL to the content that should be attempted to be embedded.
	 * @param array $args Optional arguments. Usually passed from a shortcode.
	 * @return false|string False on failure, otherwise the UNSANITIZED (and potentially unsafe) HTML that should be used to embed.
	 */
	public function get_html( $url, $args = '' ) {
		$provider = $this->get_provider( $url, $args );

		if ( !$provider || false === $data = $this->fetch( $provider, $url, $args ) )
			return false;

		/**
		 * Filter the HTML returned by the oEmbed provider.
		 *
		 * @since 1.0.0
		 *
		 * @param string $data The returned oEmbed HTML.
		 * @param string $url  URL of the content to be embedded.
		 * @param array  $args Optional arguments, usually passed from a shortcode.
		 */
		return apply_filters( 'oembed_result', $this->data2html( $data, $url ), $url, $args );
	}

	/**
	 * Attempts to discover link tags at the given URL for an oEmbed provider.
	 *
	 * @param string $url The URL that should be inspected for discovery `<link>` tags.
	 * @return false|string False on failure, otherwise the oEmbed provider URL.
	 */
	public function discover( $url ) {
		$providers = array();

		/**
		 * Filter oEmbed remote get arguments.
		 *
		 * @since 1.0.0
		 *
		 * @see BD_Http::request()
		 *
		 * @param array  $args oEmbed remote get arguments.
		 * @param string $url  URL to be inspected.
		 */
		$args = apply_filters( 'oembed_remote_get_args', array(), $url );

		// Fetch URL content
		$request = bd_safe_remote_get( $url, $args );
		if ( $html = bd_remote_retrieve_body( $request ) ) {

			/**
			 * Filter the link types that contain oEmbed provider URLs.
			 *
			 * @since 1.0.0
			 *
			 * @param array $format Array of oEmbed link types. Accepts 'application/json+oembed',
			 *                      'text/xml+oembed', and 'application/xml+oembed' (incorrect,
			 *                      used by at least Vimeo).
			 */
			$linktypes = apply_filters( 'oembed_linktypes', array(
				'application/json+oembed' => 'json',
				'text/xml+oembed' => 'xml',
				'application/xml+oembed' => 'xml',
			) );

			// Strip <body>
			$html = substr( $html, 0, stripos( $html, '</head>' ) );

			// Do a quick check
			$tagfound = false;
			foreach ( $linktypes as $linktype => $format ) {
				if ( stripos($html, $linktype) ) {
					$tagfound = true;
					break;
				}
			}

			if ( $tagfound && preg_match_all( '#<link([^<>]+)/?>#iU', $html, $links ) ) {
				foreach ( $links[1] as $link ) {
					$atts = shortcode_parse_atts( $link );

					if ( !empty($atts['type']) && !empty($linktypes[$atts['type']]) && !empty($atts['href']) ) {
						$providers[$linktypes[$atts['type']]] = htmlspecialchars_decode( $atts['href'] );

						// Stop here if it's JSON (that's all we need)
						if ( 'json' == $linktypes[$atts['type']] )
							break;
					}
				}
			}
		}

		// JSON is preferred to XML
		if ( !empty($providers['json']) )
			return $providers['json'];
		elseif ( !empty($providers['xml']) )
			return $providers['xml'];
		else
			return false;
	}

	/**
	 * Connects to a oEmbed provider and returns the result.
	 *
	 * @param string $provider The URL to the oEmbed provider.
	 * @param string $url The URL to the content that is desired to be embedded.
	 * @param array $args Optional arguments. Usually passed from a shortcode.
	 * @return false|object False on failure, otherwise the result in the form of an object.
	 */
	public function fetch( $provider, $url, $args = '' ) {
		$args = bd_parse_args( $args, bd_embed_defaults( $url ) );

		$provider = add_query_arg( 'maxwidth', (int) $args['width'], $provider );
		$provider = add_query_arg( 'maxheight', (int) $args['height'], $provider );
		$provider = add_query_arg( 'url', urlencode($url), $provider );

		/**
		 * Filter the oEmbed URL to be fetched.
		 *
		 * @since 1.0.0
		 *
		 * @param string $provider URL of the oEmbed provider.
		 * @param string $url      URL of the content to be embedded.
		 * @param array  $args     Optional arguments, usually passed from a shortcode.
		 */
		$provider = apply_filters( 'oembed_fetch_url', $provider, $url, $args );

		foreach( array( 'json', 'xml' ) as $format ) {
			$result = $this->_fetch_with_format( $provider, $format );
			if ( is_bd_error( $result ) && 'not-implemented' == $result->get_error_code() )
				continue;
			return ( $result && ! is_bd_error( $result ) ) ? $result : false;
		}
		return false;
	}

	/**
	 * Fetches result from an oEmbed provider for a specific format and complete provider URL
	 *
	 * @since 1.0.0
	 * @access private
	 * @param string $provider_url_with_args URL to the provider with full arguments list (url, maxheight, etc.)
	 * @param string $format Format to use
	 * @return false|object|BD_Error False on failure, otherwise the result in the form of an object.
	 */
	private function _fetch_with_format( $provider_url_with_args, $format ) {
		$provider_url_with_args = add_query_arg( 'format', $format, $provider_url_with_args );

		/** This filter is documented in bd-includes/class-oembed.php */
		$args = apply_filters( 'oembed_remote_get_args', array(), $provider_url_with_args );

		$response = bd_safe_remote_get( $provider_url_with_args, $args );
		if ( 501 == bd_remote_retrieve_response_code( $response ) )
			return new BD_Error( 'not-implemented' );
		if ( ! $body = bd_remote_retrieve_body( $response ) )
			return false;
		$parse_method = "_parse_$format";
		return $this->$parse_method( $body );
	}

	/**
	 * Parses a json response body.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @param string $response_body
	 * @return object|false
	 */
	private function _parse_json( $response_body ) {
		$data = json_decode( trim( $response_body ) );
		return ( $data && is_object( $data ) ) ? $data : false;
	}

	/**
	 * Parses an XML response body.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @param string $response_body
	 * @return object|false
	 */
	private function _parse_xml( $response_body ) {
		if ( ! function_exists( 'libxml_disable_entity_loader' ) )
			return false;

		$loader = libxml_disable_entity_loader( true );
		$errors = libxml_use_internal_errors( true );

		$return = $this->_parse_xml_body( $response_body );

		libxml_use_internal_errors( $errors );
		libxml_disable_entity_loader( $loader );

		return $return;
	}

	/**
	 * Helper function for parsing an XML response body.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @param string $response_body
	 * @return object|false
	 */
	private function _parse_xml_body( $response_body ) {
		if ( ! function_exists( 'simplexml_import_dom' ) || ! class_exists( 'DOMDocument' ) )
			return false;

		$dom = new DOMDocument;
		$success = $dom->loadXML( $response_body );
		if ( ! $success )
			return false;

		if ( isset( $dom->doctype ) )
			return false;

		foreach ( $dom->childNodes as $child ) {
			if ( XML_DOCUMENT_TYPE_NODE === $child->nodeType )
				return false;
		}

		$xml = simplexml_import_dom( $dom );
		if ( ! $xml )
			return false;

		$return = new stdClass;
		foreach ( $xml as $key => $value ) {
			$return->$key = (string) $value;
		}

		return $return;
	}

	/**
	 * Converts a data object from {@link BD_oEmbed::fetch()} and returns the HTML.
	 *
	 * @param object $data A data object result from an oEmbed provider.
	 * @param string $url The URL to the content that is desired to be embedded.
	 * @return false|string False on error, otherwise the HTML needed to embed.
	 */
	public function data2html( $data, $url ) {
		if ( ! is_object( $data ) || empty( $data->type ) )
			return false;

		$return = false;

		switch ( $data->type ) {
			case 'photo':
				if ( empty( $data->url ) || empty( $data->width ) || empty( $data->height ) )
					break;
				if ( ! is_string( $data->url ) || ! is_numeric( $data->width ) || ! is_numeric( $data->height ) )
					break;

				$title = ! empty( $data->title ) && is_string( $data->title ) ? $data->title : '';
				$return = '<a href="' . esc_url( $url ) . '"><img src="' . esc_url( $data->url ) . '" alt="' . esc_attr($title) . '" width="' . esc_attr($data->width) . '" height="' . esc_attr($data->height) . '" /></a>';
				break;

			case 'video':
			case 'rich':
				if ( ! empty( $data->html ) && is_string( $data->html ) )
					$return = $data->html;
				break;

			case 'link':
				if ( ! empty( $data->title ) && is_string( $data->title ) )
					$return = '<a href="' . esc_url( $url ) . '">' . esc_html( $data->title ) . '</a>';
				break;

			default:
				$return = false;
		}

		/**
		 * Filter the returned oEmbed HTML.
		 *
		 * Use this filter to add support for custom data types, or to filter the result.
		 *
		 * @since 1.0.0
		 *
		 * @param string $return The returned oEmbed HTML.
		 * @param object $data   A data object result from an oEmbed provider.
		 * @param string $url    The URL of the content to be embedded.
		 */
		return apply_filters( 'oembed_dataparse', $return, $data, $url );
	}

	/**
	 * Strip any new lines from the HTML.
	 *
	 * @access public
	 * @param string $html Existing HTML.
	 * @param object $data Data object from BD_oEmbed::data2html()
	 * @param string $url The original URL passed to oEmbed.
	 * @return string Possibly modified $html
	 */
	public function _strip_newlines( $html, $data, $url ) {
		if ( false === strpos( $html, "\n" ) ) {
			return $html;
		}

		$count = 1;
		$found = array();
		$token = '__PRE__';
		$search = array( "\t", "\n", "\r", ' ' );
		$replace = array( '__TAB__', '__NL__', '__CR__', '__SPACE__' );
		$tokenized = str_replace( $search, $replace, $html );

		preg_match_all( '#(<pre[^>]*>.+?</pre>)#i', $tokenized, $matches, PREG_SET_ORDER );
		foreach ( $matches as $i => $match ) {
			$tag_html = str_replace( $replace, $search, $match[0] );
			$tag_token = $token . $i;

			$found[ $tag_token ] = $tag_html;
			$html = str_replace( $tag_html, $tag_token, $html, $count );
		}

		$replaced = str_replace( $replace, $search, $html );
		$stripped = str_replace( array( "\r\n", "\n" ), '', $replaced );
		$pre = array_values( $found );
		$tokens = array_keys( $found );

		return str_replace( $tokens, $pre, $stripped );
	}
}

/**
 * Returns the initialized {@link BD_oEmbed} object
 *
 * @since 1.0.0
 * @access private
 *
 * @staticvar BD_oEmbed $bd_oembed
 *
 * @return BD_oEmbed object.
 */
function _bd_oembed_get_object() {
	static $bd_oembed = null;

	if ( is_null( $bd_oembed ) ) {
		$bd_oembed = new BD_oEmbed();
	}
	return $bd_oembed;
}
