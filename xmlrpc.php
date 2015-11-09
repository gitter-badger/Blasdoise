<?php
/**
 * XML-RPC protocol support for Blasdoise
 *
 * @package Blasdoise
 */

/**
 * Whether this is an XML-RPC Request
 *
 * @var bool
 */
define('XMLRPC_REQUEST', true);

// Some browser-embedded clients send cookies. We don't want them.
$_COOKIE = array();

// A bug in PHP < 5.2.2 makes $HTTP_RAW_POST_DATA not set by default,
// but we can do it ourself.
if ( !isset( $HTTP_RAW_POST_DATA ) ) {
	$HTTP_RAW_POST_DATA = file_get_contents( 'php://input' );
}

// fix for mozBlog and other cases where '<?xml' isn't on the very first line
if ( isset($HTTP_RAW_POST_DATA) )
	$HTTP_RAW_POST_DATA = trim($HTTP_RAW_POST_DATA);

/** Include the bootstrap for setting up Blasdoise environment */
include( dirname( __FILE__ ) . '/bd-load.php' );

if ( isset( $_GET['rsd'] ) ) {
header('Content-Type: text/xml; charset=' . get_option('blog_charset'), true);
?>
<?php echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>'; ?>
<rsd version="1.0" xmlns="http://archipelago.phrasewise.com/rsd">
  <service>
    <engineName>Blasdoise</engineName>
    <engineLink>http://blasdoise.com/</engineLink>
    <homePageLink><?php bloginfo_rss('url') ?></homePageLink>
    <apis>
      <api name="Blasdoise" blogID="1" preferred="true" apiLink="<?php echo site_url('xmlrpc.php', 'rpc') ?>" />
      <api name="Movable Type" blogID="1" preferred="false" apiLink="<?php echo site_url('xmlrpc.php', 'rpc') ?>" />
      <api name="MetaWeblog" blogID="1" preferred="false" apiLink="<?php echo site_url('xmlrpc.php', 'rpc') ?>" />
      <api name="Blogger" blogID="1" preferred="false" apiLink="<?php echo site_url('xmlrpc.php', 'rpc') ?>" />
      <?php
      /**
       * Add additional APIs to the Really Simple Discovery (RSD) endpoint.
	   *
       * @since 1.0.0
       */
      do_action( 'xmlrpc_rsd_apis' );
      ?>
    </apis>
  </service>
</rsd>
<?php
exit;
}

include_once(ABSPATH . 'bd-admin/includes/admin.php');
include_once(ABSPATH . BDINC . '/class-ixr.php');
include_once(ABSPATH . BDINC . '/class-bd-xmlrpc-server.php');

/**
 * Posts submitted via the XML-RPC interface get that title
 * @name post_default_title
 * @var string
 */
$post_default_title = "";

/**
 * Filter the class used for handling XML-RPC requests.
 *
 * @since 1.0.0
 *
 * @param string $class The name of the XML-RPC server class.
 */
$bd_xmlrpc_server_class = apply_filters( 'bd_xmlrpc_server_class', 'bd_xmlrpc_server' );
$bd_xmlrpc_server = new $bd_xmlrpc_server_class;

// Fire off the request
$bd_xmlrpc_server->serve_request();

exit;

/**
 * logIO() - Writes logging info to a file.
 *
 * @deprecated 1.0.0
 * @deprecated Use error_log()
 *
 * @param string $io Whether input or output
 * @param string $msg Information describing logging reason.
 */
function logIO( $io, $msg ) {
	_deprecated_function( __FUNCTION__, '1.0', 'error_log()' );
	if ( ! empty( $GLOBALS['xmlrpc_logging'] ) )
		error_log( $io . ' - ' . $msg );
}