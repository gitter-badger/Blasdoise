<?php
/**
 * Send XML response back to AJAX request.
 *
 * @package Blasdoise
 * @since 1.0.0
 */
class BD_Ajax_Response {
	/**
	 * Store XML responses to send.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public $responses = array();

	/**
	 * Constructor - Passes args to {@link BD_Ajax_Response::add()}.
	 *
	 * @since 1.0.0
	 * @see BD_Ajax_Response::add()
	 *
	 * @param string|array $args Optional. Will be passed to add() method.
	 */
	public function __construct( $args = '' ) {
		if ( !empty($args) )
			$this->add($args);
	}

	/**
	 * Append to XML response based on given arguments.
	 *
	 * The arguments that can be passed in the $args parameter are below. It is
	 * also possible to pass a BD_Error object in either the 'id' or 'data'
	 * argument. The parameter isn't actually optional, content should be given
	 * in order to send the correct response.
	 *
	 * 'what' argument is a string that is the XMLRPC response type.
	 * 'action' argument is a boolean or string that acts like a nonce.
	 * 'id' argument can be BD_Error or an integer.
	 * 'old_id' argument is false by default or an integer of the previous ID.
	 * 'position' argument is an integer or a string with -1 = top, 1 = bottom,
	 * html ID = after, -html ID = before.
	 * 'data' argument is a string with the content or message.
	 * 'supplemental' argument is an array of strings that will be children of
	 * the supplemental element.
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $args Override defaults.
	 * @return string XML response.
	 */
	public function add( $args = '' ) {
		$defaults = array(
			'what' => 'object', 'action' => false,
			'id' => '0', 'old_id' => false,
			'position' => 1,
			'data' => '', 'supplemental' => array()
		);

		$r = bd_parse_args( $args, $defaults );

		$position = preg_replace( '/[^a-z0-9:_-]/i', '', $r['position'] );
		$id = $r['id'];
		$what = $r['what'];
		$action = $r['action'];
		$old_id = $r['old_id'];
		$data = $r['data'];

		if ( is_bd_error( $id ) ) {
			$data = $id;
			$id = 0;
		}

		$response = '';
		if ( is_bd_error( $data ) ) {
			foreach ( (array) $data->get_error_codes() as $code ) {
				$response .= "<bd_error code='$code'><![CDATA[" . $data->get_error_message( $code ) . "]]></bd_error>";
				if ( ! $error_data = $data->get_error_data( $code ) ) {
					continue;
				}
				$class = '';
				if ( is_object( $error_data ) ) {
					$class = ' class="' . get_class( $error_data ) . '"';
					$error_data = get_object_vars( $error_data );
				}

				$response .= "<bd_error_data code='$code'$class>";

				if ( is_scalar( $error_data ) ) {
					$response .= "<![CDATA[$error_data]]>";
				} elseif ( is_array( $error_data ) ) {
					foreach ( $error_data as $k => $v ) {
						$response .= "<$k><![CDATA[$v]]></$k>";
					}
				}

				$response .= "</bd_error_data>";
			}
		} else {
			$response = "<response_data><![CDATA[$data]]></response_data>";
		}

		$s = '';
		if ( is_array( $r['supplemental'] ) ) {
			foreach ( $r['supplemental'] as $k => $v ) {
				$s .= "<$k><![CDATA[$v]]></$k>";
			}
			$s = "<supplemental>$s</supplemental>";
		}

		if ( false === $action ) {
			$action = $_POST['action'];
		}
		$x = '';
		$x .= "<response action='{$action}_$id'>"; // The action attribute in the xml output is formatted like a nonce action
		$x .=	"<$what id='$id' " . ( false === $old_id ? '' : "old_id='$old_id' " ) . "position='$position'>";
		$x .=		$response;
		$x .=		$s;
		$x .=	"</$what>";
		$x .= "</response>";

		$this->responses[] = $x;
		return $x;
	}

	/**
	 * Display XML formatted responses.
	 *
	 * Sets the content type header to text/xml.
	 *
	 * @since 1.0.0
	 */
	public function send() {
		header( 'Content-Type: text/xml; charset=' . get_option( 'blog_charset' ) );
		echo "<?xml version='1.0' encoding='" . get_option( 'blog_charset' ) . "' standalone='yes'?><bd_ajax>";
		foreach ( (array) $this->responses as $response )
			echo $response;
		echo '</bd_ajax>';
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
			bd_die();
		else
			die();
	}
}
