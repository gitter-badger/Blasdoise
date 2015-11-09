<?php
/**
 * About This Version administration panel.
 *
 * @package Blasdoise
 * @subpackage Administration
 */

/** Blasdoise Administration Bootstrap */
require_once( dirname( __FILE__ ) . '/admin.php' );

bd_enqueue_style( 'bd-mediaelement' );
bd_enqueue_script( 'bd-mediaelement' );
bd_localize_script( 'mediaelement', '_bdmejsSettings', array(
	'pluginPath' => includes_url( 'js/mediaelement/', 'relative' ),
	'pauseOtherPlayers' => ''
) );

$title = __( 'About' );

list( $display_version ) = explode( '-', $bd_version );

include( ABSPATH . 'bd-admin/admin-header.php' );

?>
	<div class="wrap about-wrap">
            <h1><?php printf( __( 'Welcome to Blasdoise %s' ), $display_version ); ?></h1>

            <div class="about-text">
                <?php printf( __( 'Thank you for updating! Blasdoise %s helps you focus on your writing, and the new default theme lets you show it off in style.' ), $display_version ); ?>
            </div>

            <div class="bd-badge"><?php printf( __( 'Version %s' ), $display_version ); ?></div>

            <h2 class="nav-tab-wrapper">
                <a href="about.php" class="nav-tab nav-tab-active">
                    <?php _e( 'About Blasdoise' ); ?>
                </a><a href="http://blasdoise.com/category/themes/" class="nav-tab">
                    <?php _e( 'Shop Themes' ); ?>
                </a><a href="http://blasdoise.com/category/languages/" class="nav-tab">
                    <?php _e( 'Shop Languages' ); ?>
                </a><a href="http://blasdoise.com/category/plugins/" class="nav-tab">
                    <?php _e( 'Shop Plugins' ); ?>
                </a>
            </h2>

            <div class="changelog headline-feature">
                <h2><?php _e( 'Introducing Fairy Theme' ); ?></h2>
                <div class="featured-image">
                    <img src="<?php echo esc_url( admin_url( 'images/theme.png' ) ); ?>">
                </div>
                <div class="feature-section">
                    <p class="about-descrfileiption"><?php _e( 'Our default theme, Fairy, is a blog-focused theme designed for clarity.' ); ?></p>
                    <p><?php printf( __( 'Fairy has flawless language support, with help from <a href="%s">Google&#8217;s Noto font family</a>. The straightforward typography is readable on any screen size. Your content always takes center stage, whether viewed on a phone, tablet, laptop, or desktop computer.' ), __( 'http://google.com/get/noto/' ) ); ?></p>
                </div>
                <div class="clear"></div>
            </div>

            <div class="changelog headline-credits">
                <p class="about-description"><?php _e( 'Blasdoise is created by a team of passionate individuals.' ); ?></p>
                    <ul class="bdpeople-group " id="bdpeople-group-project-leaders">
                        <li class="bd-person"><a href="http://blasdoise.com/author/ardymada/"><img src="<?php echo esc_url( admin_url( 'images/contributors/ardymada.jpg' ) ); ?>" class="gravatar" alt="Ardy Mada"></a><a class="web" href="http://blasdoise.com/author/ardymada/"><?php _e( 'Ardy Mada' ); ?></a>
                            <span class="title"><?php _e( 'Developer' ); ?></span>
                        </li><li class="bd-person"><a href="http://blasdoise.com/author/fajarkhaidir/"><img src="<?php echo esc_url( admin_url( 'images/contributors/fajarkhaidir.jpg' ) ); ?>" class="gravatar" alt="Fajar Khaidir"></a><a class="web" href="http://blasdoise.com/author/fajarkhaidir/"><?php _e( 'Fajar Khaidir' ); ?></a>
                            <span class="title"><?php _e( 'Designer' ); ?></span>
                        </li><li class="bd-person"><a href="http://blasdoise.com/author/asepkurniawan/"><img src="<?php echo esc_url( admin_url( 'images/contributors/asepkurniawan.jpg' ) ); ?>" class="gravatar" alt="Asep Kurniawan"></a><a class="web" href="http://blasdoise.com/author/asepkurniawan/"><?php _e( 'Asep Kurniawan' ); ?></a>
                            <span class="title"><?php _e( 'Developer' ); ?></span>
                        </li><li class="bd-person"><a href="http://blasdoise.com/author/muhamadmukhliz/"><img src="<?php echo esc_url( admin_url( 'images/contributors/muhamadmukhliz.jpg' ) ); ?>" class="gravatar" alt="Muhamad Muhkhliz"></a><a class="web" href="http://blasdoise.com/author/muhamadmukhliz/"><?php _e( 'Muhamad Muhkhliz' ); ?></a>
                            <span class="title"><?php _e( 'Designer' ); ?></span>
                        </li><li class="bd-person"><a href="http://blasdoise.com/author/fauziseptiankoto/"><img src="<?php echo esc_url( admin_url( 'images/contributors/fauziseptiankoto.jpg' ) ); ?>" class="gravatar" alt="Fauzi Septian Koto"></a><a class="web" href="http://blasdoise.com/author/fauziseptiankoto/"><?php _e( 'Fauzi Septian Koto' ); ?></a>
                            <span class="title"><?php _e( 'Translator' ); ?></span>
                        </li><li class="bd-person"><a href="http://blasdoise.com/author/robbisaepudin/"><img src="<?php echo esc_url( admin_url( 'images/contributors/robbisaepudin.jpg' ) ); ?>" class="gravatar" alt="Robbi Saepudin"></a><a class="web" href="http://blasdoise.com/author/robbisaepudin/"><?php _e( 'Robbi Saepudin' ); ?></a>
                            <span class="title"><?php _e( 'Developer' ); ?></span>
                        </li><li class="bd-person"><a href="http://blasdoise.com/author/farrasstyakusuma/"><img src="<?php echo esc_url( admin_url( 'images/contributors/farrasstyakusuma.jpg' ) ); ?>" class="gravatar" alt="Farras Stya Kusuma"></a><a class="web" href="http://blasdoise.com/author/farrasstyakusuma/"><?php _e( 'Farras Stya Kusuma' ); ?></a>
                            <span class="title"><?php _e( 'Designer' ); ?></span>
                        </li><li class="bd-person"><a href="http://blasdoise.com/author/aszenal/"><img src="<?php echo esc_url( admin_url( 'images/contributors/aszenal.jpg' ) ); ?>" class="gravatar" alt="As Zenal"></a><a class="web" href="http://blasdoise.com/author/aszenal/"><?php _e( 'As Zenal' ); ?></a>
                            <span class="title"><?php _e( 'Developer' ); ?></span>
                        </li><li class="bd-person"><a href="http://blasdoise.com/author/achmadmufid/"><img src="<?php echo esc_url( admin_url( 'images/contributors/achmadmufid.jpg' ) ); ?>" class="gravatar" alt="Achmad Mufid"></a><a class="web" href="http://blasdoise.com/author/achmadmufid/"><?php _e( 'Achmad Mufid' ); ?></a>
                            <span class="title"><?php _e( 'Designer' ); ?></span>
                        </li><li class="bd-person"><a href="http://blasdoise.com/author/hanzmalkian/"><img src="<?php echo esc_url( admin_url( 'images/contributors/hanzmalkian.jpg' ) ); ?>" class="gravatar" alt="Johan S Bachtiar"></a><a class="web" href="http://blasdoise.com/author/hanzmalkian/"><?php _e( 'Hanz Malkian' ); ?></a>
                            <span class="title"><?php _e( 'Developer and Designer' ); ?></span>
                        </li><li class="bd-person"><a href="http://blasdoise.com/author/ibnusina/"><img src="<?php echo esc_url( admin_url( 'images/contributors/ibnusina.jpg' ) ); ?>" class="gravatar" alt="Ibnu Sina"></a><a class="web" href="http://blasdoise.com/author/ibnusina/"><?php _e( 'Ibnu Sina' ); ?></a>
                            <span class="title"><?php _e( 'Developer and Designer' ); ?></span>
                        </li>
                    </ul>
                <p class="about-description"><?php _e( 'And some external libraries that make it perfect.' ); ?></p>
                
                <p class="bd-credits-list">
                    <a href="http://backbonejs.org/">Backbone.js</a>, <a href="http://getbootstrap.com/">Bootstrap</a>, <a href="http://squirrelmail.org/">Class POP3</a>, <a href="http://plugins.jquery.com/color/">Color Animations</a>, <a href="http://gravatar.com">Gravatar</a>, <a href="http://pear.horde.org/">Horde Text Diff</a>, <a href="http://plugins.jquery.com/project/hoverIntent">hoverIntent</a>, <a href="http://odyniec.net/projects/imgareaselect/">imgAreaSelect</a>, <a href="http://github.com/Automattic/Iris">Iris</a>, <a href="http://jquery.com/">jQuery</a>, <a href="http://jqueryui.com/">jQuery UI</a>, <a href="http://github.com/tzuryby/jquery.hotkeys">jQuery Hotkeys</a>, <a href="http://benalman.com/projects/jquery-misc-plugins/">jQuery serializeObject</a>, <a href="http://plugins.jquery.com/query-object/">jQuery.query</a>, <a href="http://plugins.jquery.com/project/suggest">jQuery.suggest</a>, <a href="http://touchpunch.furf.com/">jQuery UI Touch Punch</a>, <a href="http://github.com/douglascrockford/JSON-js">json2</a>, <a href="http://masonry.desandro.com/">Masonry</a>, <a href="http://mediaelementjs.com/">MediaElement.js</a>, <a href="http://phpconcept.net/pclzip/">PclZip</a>, <a href="http://phpclasses.org/browse/package/1743.html">PemFTP</a>, <a href="http://openwall.com/phpass/">phpass</a>, <a href="http://code.google.com/a/apache-extras.org/p/phpmailer/">PHPMailer</a>, <a href="http://plupload.com/">Plupload</a>, <a href="http://simplepie.org/">SimplePie</a>, <a href="http://scripts.incutio.com/xmlrpc/">The Incutio XML-RPC Library</a>, <a href="http://codylindley.com/thickbox/">Thickbox</a>, <a href="http://tinymce.com/">TinyMCE</a>, <a href="http://underscorejs.org/">Underscore.js</a>, <a href="http://github.com/dropbox/zxcvbn">zxcvbn</a>.
                </p>

                <p class="clear"><?php printf( __( 'Want to be involved in the development of this project? <a href="%s">Let&#8217;s get involved and join in the Support Forums!</a>' ), __( 'http://blasdoise.com/support/' ) ); ?></p>
            </div>

        <hr />

            <p class="about-description"><?php printf( __( 'Blasdoise is Free and open source software, built by a distributed community of mostly volunteer developers. Blasdoise comes with some awesome, worldview-changing rights courtesy of its <a href="%s">license</a>, the GPL.' ), __( site_url( 'license.txt' ) ) ); ?></p>
                <ol start="1">
                    <li><p><?php _e( 'You have the freedom to run the program, for any purpose.' ); ?></p></li>
                    <li><p><?php _e( 'You have access to the source code, the freedom to study how the program works, and the freedom to change it to make it do what you wish.' ); ?></p></li>
                    <li><p><?php _e( 'You have the freedom to redistribute copies of the original program so you can help your neighbor.' ); ?></p></li>
                    <li><p><?php _e( 'You have the freedom to distribute copies of your modified versions to others. By doing this you can give the whole community a chance to benefit from your changes.' ); ?></p></li>
                </ol>
            <p><?php _e( 'Blasdoise grows when people like you tell their friends about it, and we&#8217;re flattered every time someone spreads the good word, just make sure to check out our trademark guidelines. Every plugin and theme in Blasdoise directory is 100% GPL or a similarly free and compatible license, so you can feel safe finding plugins and themes there. If you get a plugin or theme from another source, make sure to ask them if it&#8217;s GPL first. If they don&#8217;t respect the Blasdoise license, we don&#8217;t recommend them. Don&#8217;t you wish all software came with these freedoms? So do we!' ); ?></p>
	</div>
<?php

include( ABSPATH . 'bd-admin/admin-footer.php' );

// These are strings we may use to describe maintenance/security releases, where we aim for no new strings.
return;

_n_noop( 'Maintenance Release', 'Maintenance Releases' );
_n_noop( 'Security Release', 'Security Releases' );
_n_noop( 'Maintenance and Security Release', 'Maintenance and Security Releases' );

/* translators: 1: Blasdoise version number. */
_n_noop( '<strong>Version %1$s</strong> addressed a security issue.',
         '<strong>Version %1$s</strong> addressed some security issues.' );

/* translators: 1: Blasdoise version number, 2: plural number of bugs. */
_n_noop( '<strong>Version %1$s</strong> addressed %2$s bug.',
         '<strong>Version %1$s</strong> addressed %2$s bugs.' );

/* translators: 1: Blasdoise version number, 2: plural number of bugs. Singular security issue. */
_n_noop( '<strong>Version %1$s</strong> addressed a security issue and fixed %2$s bug.',
         '<strong>Version %1$s</strong> addressed a security issue and fixed %2$s bugs.' );

/* translators: 1: Blasdoise version number, 2: plural number of bugs. More than one security issue. */
_n_noop( '<strong>Version %1$s</strong> addressed some security issues and fixed %2$s bug.',
         '<strong>Version %1$s</strong> addressed some security issues and fixed %2$s bugs.' );
