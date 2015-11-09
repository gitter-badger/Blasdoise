<?php
/**
 * Blasdoise core upgrade functionality.
 *
 * @package Blasdoise
 * @subpackage Administration
 * @since 1.0.0
 */

/**
 * Stores files to be deleted.
 *
 * @since 1.0.0
 * @global array $_old_files
 * @var array
 * @name $_old_files
 */
global $_old_files;

$_old_files = array(
'bd-admin/import-b2.php',
'bd-admin/import-blogger.php',
'bd-admin/import-greymatter.php',
'bd-admin/import-livejournal.php',
'bd-admin/import-mt.php',
'bd-admin/import-rss.php',
'bd-admin/import-textpattern.php',
'bd-admin/quicktags.js',
'bd-images/fade-butt.png',
'bd-images/get-firefox.png',
'bd-images/header-shadow.png',
'bd-images/smilies',
'bd-images/bd-small.png',
'bd-images/bdminilogo.png',
'bd.php',
'bd-includes/js/tinymce/plugins/inlinepopups/readme.txt',
'bd-admin/edit-form-ajax-cat.php',
'bd-admin/execute-pings.php',
'bd-admin/inline-uploading.php',
'bd-admin/link-categories.php',
'bd-admin/list-manipulation.js',
'bd-admin/list-manipulation.php',
'bd-includes/comment-functions.php',
'bd-includes/feed-functions.php',
'bd-includes/functions-compat.php',
'bd-includes/functions-formatting.php',
'bd-includes/functions-post.php',
'bd-includes/js/dbx-key.js',
'bd-includes/js/tinymce/plugins/autosave/langs/cs.js',
'bd-includes/js/tinymce/plugins/autosave/langs/sv.js',
'bd-includes/links.php',
'bd-includes/pluggable-functions.php',
'bd-includes/template-functions-author.php',
'bd-includes/template-functions-category.php',
'bd-includes/template-functions-general.php',
'bd-includes/template-functions-links.php',
'bd-includes/template-functions-post.php',
'bd-includes/bd-l10n.php',
'bd-admin/cat-js.php',
'bd-admin/import/b2.php',
'bd-includes/js/autosave-js.php',
'bd-includes/js/list-manipulation-js.php',
'bd-includes/js/bd-ajax-js.php',
'bd-admin/admin-db.php',
'bd-admin/cat.js',
'bd-admin/categories.js',
'bd-admin/custom-fields.js',
'bd-admin/dbx-admin-key.js',
'bd-admin/edit-comments.js',
'bd-admin/install-rtl.css',
'bd-admin/install.css',
'bd-admin/upgrade-schema.php',
'bd-admin/upload-functions.php',
'bd-admin/upload-rtl.css',
'bd-admin/upload.css',
'bd-admin/upload.js',
'bd-admin/users.js',
'bd-admin/widgets-rtl.css',
'bd-admin/widgets.css',
'bd-admin/xfn.js',
'bd-includes/js/tinymce/license.html',
'bd-admin/css/upload.css',
'bd-admin/images/box-bg-left.gif',
'bd-admin/images/box-bg-right.gif',
'bd-admin/images/box-bg.gif',
'bd-admin/images/box-butt-left.gif',
'bd-admin/images/box-butt-right.gif',
'bd-admin/images/box-butt.gif',
'bd-admin/images/box-head-left.gif',
'bd-admin/images/box-head-right.gif',
'bd-admin/images/box-head.gif',
'bd-admin/images/heading-bg.gif',
'bd-admin/images/login-bkg-bottom.gif',
'bd-admin/images/login-bkg-tile.gif',
'bd-admin/images/notice.gif',
'bd-admin/images/toggle.gif',
'bd-admin/includes/upload.php',
'bd-admin/js/dbx-admin-key.js',
'bd-admin/js/link-cat.js',
'bd-admin/profile-update.php',
'bd-admin/templates.php',
'bd-includes/images/wlw/WpComments.png',
'bd-includes/images/wlw/WpIcon.png',
'bd-includes/images/wlw/WpWatermark.png',
'bd-includes/js/dbx.js',
'bd-includes/js/fat.js',
'bd-includes/js/list-manipulation.js',
'bd-includes/js/tinymce/langs/en.js',
'bd-includes/js/tinymce/plugins/autosave/editor_plugin_src.js',
'bd-includes/js/tinymce/plugins/autosave/langs',
'bd-includes/js/tinymce/plugins/directionality/images',
'bd-includes/js/tinymce/plugins/directionality/langs',
'bd-includes/js/tinymce/plugins/inlinepopups/css',
'bd-includes/js/tinymce/plugins/inlinepopups/images',
'bd-includes/js/tinymce/plugins/inlinepopups/jscripts',
'bd-includes/js/tinymce/plugins/paste/images',
'bd-includes/js/tinymce/plugins/paste/jscripts',
'bd-includes/js/tinymce/plugins/paste/langs',
'bd-includes/js/tinymce/plugins/spellchecker/classes/HttpClient.class.php',
'bd-includes/js/tinymce/plugins/spellchecker/classes/TinyGoogleSpell.class.php',
'bd-includes/js/tinymce/plugins/spellchecker/classes/TinyPspell.class.php',
'bd-includes/js/tinymce/plugins/spellchecker/classes/TinyPspellShell.class.php',
'bd-includes/js/tinymce/plugins/spellchecker/css/spellchecker.css',
'bd-includes/js/tinymce/plugins/spellchecker/images',
'bd-includes/js/tinymce/plugins/spellchecker/langs',
'bd-includes/js/tinymce/plugins/spellchecker/tinyspell.php',
'bd-includes/js/tinymce/plugins/blasdoise/images',
'bd-includes/js/tinymce/plugins/blasdoise/langs',
'bd-includes/js/tinymce/plugins/blasdoise/blasdoise.css',
'bd-includes/js/tinymce/plugins/bdhelp',
'bd-includes/js/tinymce/themes/advanced/css',
'bd-includes/js/tinymce/themes/advanced/images',
'bd-includes/js/tinymce/themes/advanced/jscripts',
'bd-includes/js/tinymce/themes/advanced/langs',
'bd-includes/js/tinymce/tiny_mce_gzip.php',
'bd-admin/bookmarklet.php',
'bd-includes/js/jquery/jquery.dimensions.min.js',
'bd-includes/js/tinymce/plugins/blasdoise/popups.css',
'bd-includes/js/bd-ajax.js',
'bd-admin/css/press-this-ie-rtl.css',
'bd-admin/css/press-this-ie.css',
'bd-admin/css/upload-rtl.css',
'bd-admin/edit-form.php',
'bd-admin/images/comment-pill.gif',
'bd-admin/images/comment-stalk-classic.gif',
'bd-admin/images/comment-stalk-fresh.gif',
'bd-admin/images/comment-stalk-rtl.gif',
'bd-admin/images/del.png',
'bd-admin/images/gear.png',
'bd-admin/images/media-button-gallery.gif',
'bd-admin/images/media-buttons.gif',
'bd-admin/images/postbox-bg.gif',
'bd-admin/images/tab.png',
'bd-admin/images/tail.gif',
'bd-admin/js/forms.js',
'bd-admin/js/upload.js',
'bd-admin/link-import.php',
'bd-includes/images/audio.png',
'bd-includes/images/css.png',
'bd-includes/images/default.png',
'bd-includes/images/doc.png',
'bd-includes/images/exe.png',
'bd-includes/images/html.png',
'bd-includes/images/js.png',
'bd-includes/images/pdf.png',
'bd-includes/images/swf.png',
'bd-includes/images/tar.png',
'bd-includes/images/text.png',
'bd-includes/images/video.png',
'bd-includes/images/zip.png',
'bd-includes/js/tinymce/tiny_mce_config.php',
'bd-includes/js/tinymce/tiny_mce_ext.js',
'bd-admin/js/users.js',
'bd-includes/js/swfupload/plugins/swfupload.documentready.js',
'bd-includes/js/swfupload/plugins/swfupload.graceful_degradation.js',
'bd-includes/js/swfupload/swfupload_f9.swf',
'bd-includes/js/tinymce/plugins/autosave',
'bd-includes/js/tinymce/plugins/paste/css',
'bd-includes/js/tinymce/utils/mclayer.js',
'bd-includes/js/tinymce/blasdoise.css',
'bd-admin/import/btt.php',
'bd-admin/import/jkw.php',
'bd-admin/js/page.dev.js',
'bd-admin/js/page.js',
'bd-admin/js/set-post-thumbnail-handler.dev.js',
'bd-admin/js/set-post-thumbnail-handler.js',
'bd-admin/js/slug.dev.js',
'bd-admin/js/slug.js',
'bd-includes/gettext.php',
'bd-includes/js/tinymce/plugins/blasdoise/js',
'bd-includes/streams.php',
'README.txt',
'htaccess.dist',
'index-install.php',
'bd-admin/css/mu-rtl.css',
'bd-admin/css/mu.css',
'bd-admin/images/site-admin.png',
'bd-admin/includes/mu.php',
'bd-admin/bdmu-admin.php',
'bd-admin/bdmu-blogs.php',
'bd-admin/bdmu-edit.php',
'bd-admin/bdmu-options.php',
'bd-admin/bdmu-themes.php',
'bd-admin/bdmu-upgrade-site.php',
'bd-admin/bdmu-users.php',
'bd-includes/images/blasdoise-mu.png',
'bd-includes/bdmu-default-filters.php',
'bd-includes/bdmu-functions.php',
'bdmu-settings.php',
'bd-admin/categories.php',
'bd-admin/edit-category-form.php',
'bd-admin/edit-page-form.php',
'bd-admin/edit-pages.php',
'bd-admin/images/admin-header-footer.png',
'bd-admin/images/browse-happy.gif',
'bd-admin/images/ico-add.png',
'bd-admin/images/ico-close.png',
'bd-admin/images/ico-edit.png',
'bd-admin/images/ico-viewpage.png',
'bd-admin/images/fav-top.png',
'bd-admin/images/screen-options-left.gif',
'bd-admin/images/bd-logo-vs.gif',
'bd-admin/images/bd-logo.gif',
'bd-admin/import',
'bd-admin/js/bd-gears.dev.js',
'bd-admin/js/bd-gears.js',
'bd-admin/options-misc.php',
'bd-admin/page-new.php',
'bd-admin/page.php',
'bd-admin/rtl.css',
'bd-admin/rtl.dev.css',
'bd-admin/update-links.php',
'bd-admin/bd-admin.css',
'bd-admin/bd-admin.dev.css',
'bd-includes/js/codepress',
'bd-includes/js/codepress/engines/khtml.js',
'bd-includes/js/codepress/engines/older.js',
'bd-includes/js/jquery/autocomplete.dev.js',
'bd-includes/js/jquery/autocomplete.js',
'bd-includes/js/jquery/interface.js',
'bd-includes/js/scriptaculous/prototype.js',
'bd-includes/js/tinymce/bd-tinymce.js',
'bd-admin/edit-attachment-rows.php',
'bd-admin/edit-link-categories.php',
'bd-admin/edit-link-category-form.php',
'bd-admin/edit-post-rows.php',
'bd-admin/images/button-grad-active-vs.png',
'bd-admin/images/button-grad-vs.png',
'bd-admin/images/fav-arrow-vs-rtl.gif',
'bd-admin/images/fav-arrow-vs.gif',
'bd-admin/images/fav-top-vs.gif',
'bd-admin/images/list-vs.png',
'bd-admin/images/screen-options-right-up.gif',
'bd-admin/images/screen-options-right.gif',
'bd-admin/images/visit-site-button-grad-vs.gif',
'bd-admin/images/visit-site-button-grad.gif',
'bd-admin/link-category.php',
'bd-admin/sidebar.php',
'bd-includes/classes.php',
'bd-includes/js/tinymce/blank.htm',
'bd-includes/js/tinymce/plugins/media/css/content.css',
'bd-includes/js/tinymce/plugins/media/img',
'bd-includes/js/tinymce/plugins/safari',
'bd-admin/images/logo-login.gif',
'bd-admin/images/star.gif',
'bd-admin/js/list-table.dev.js',
'bd-admin/js/list-table.js',
'bd-includes/default-embeds.php',
'bd-includes/js/tinymce/plugins/blasdoise/img/help.gif',
'bd-includes/js/tinymce/plugins/blasdoise/img/more.gif',
'bd-includes/js/tinymce/plugins/blasdoise/img/toolbars.gif',
'bd-includes/js/tinymce/themes/advanced/img/fm.gif',
'bd-includes/js/tinymce/themes/advanced/img/sflogo.png',
'bd-admin/css/colors-classic-rtl.css',
'bd-admin/css/colors-classic-rtl.dev.css',
'bd-admin/css/colors-fresh-rtl.css',
'bd-admin/css/colors-fresh-rtl.dev.css',
'bd-admin/css/dashboard-rtl.dev.css',
'bd-admin/css/dashboard.dev.css',
'bd-admin/css/global-rtl.css',
'bd-admin/css/global-rtl.dev.css',
'bd-admin/css/global.css',
'bd-admin/css/global.dev.css',
'bd-admin/css/install-rtl.dev.css',
'bd-admin/css/login-rtl.dev.css',
'bd-admin/css/login.dev.css',
'bd-admin/css/ms.css',
'bd-admin/css/ms.dev.css',
'bd-admin/css/nav-menu-rtl.css',
'bd-admin/css/nav-menu-rtl.dev.css',
'bd-admin/css/nav-menu.css',
'bd-admin/css/nav-menu.dev.css',
'bd-admin/css/plugin-install-rtl.css',
'bd-admin/css/plugin-install-rtl.dev.css',
'bd-admin/css/plugin-install.css',
'bd-admin/css/plugin-install.dev.css',
'bd-admin/css/press-this-rtl.dev.css',
'bd-admin/css/press-this.dev.css',
'bd-admin/css/theme-editor-rtl.css',
'bd-admin/css/theme-editor-rtl.dev.css',
'bd-admin/css/theme-editor.css',
'bd-admin/css/theme-editor.dev.css',
'bd-admin/css/theme-install-rtl.css',
'bd-admin/css/theme-install-rtl.dev.css',
'bd-admin/css/theme-install.css',
'bd-admin/css/theme-install.dev.css',
'bd-admin/css/widgets-rtl.dev.css',
'bd-admin/css/widgets.dev.css',
'bd-admin/includes/internal-linking.php',
'bd-includes/images/admin-bar-sprite-rtl.png',
'bd-includes/js/jquery/ui.button.js',
'bd-includes/js/jquery/ui.core.js',
'bd-includes/js/jquery/ui.dialog.js',
'bd-includes/js/jquery/ui.draggable.js',
'bd-includes/js/jquery/ui.droppable.js',
'bd-includes/js/jquery/ui.mouse.js',
'bd-includes/js/jquery/ui.position.js',
'bd-includes/js/jquery/ui.resizable.js',
'bd-includes/js/jquery/ui.selectable.js',
'bd-includes/js/jquery/ui.sortable.js',
'bd-includes/js/jquery/ui.tabs.js',
'bd-includes/js/jquery/ui.widget.js',
'bd-includes/js/l10n.dev.js',
'bd-includes/js/l10n.js',
'bd-includes/js/tinymce/plugins/bdlink/css',
'bd-includes/js/tinymce/plugins/bdlink/img',
'bd-includes/js/tinymce/plugins/bdlink/js',
'bd-includes/js/tinymce/themes/advanced/img/bdicons.png',
'bd-includes/js/tinymce/themes/advanced/skins/bd_theme/img/butt2.png',
'bd-includes/js/tinymce/themes/advanced/skins/bd_theme/img/button_bg.png',
'bd-includes/js/tinymce/themes/advanced/skins/bd_theme/img/down_arrow.gif',
'bd-includes/js/tinymce/themes/advanced/skins/bd_theme/img/fade-butt.png',
'bd-includes/js/tinymce/themes/advanced/skins/bd_theme/img/separator.gif',
// Don't delete, yet: 'bd-rss.php',
// Don't delete, yet: 'bd-rdf.php',
// Don't delete, yet: 'bd-rss2.php',
// Don't delete, yet: 'bd-commentsrss2.php',
// Don't delete, yet: 'bd-atom.php',
// Don't delete, yet: 'bd-feed.php',
'bd-admin/images/gray-star.png',
'bd-admin/images/logo-login.png',
'bd-admin/images/star.png',
'bd-admin/index-extra.php',
'bd-admin/network/index-extra.php',
'bd-admin/user/index-extra.php',
'bd-admin/images/screenshots/admin-flyouts.png',
'bd-admin/images/screenshots/coediting.png',
'bd-admin/images/screenshots/drag-and-drop.png',
'bd-admin/images/screenshots/help-screen.png',
'bd-admin/images/screenshots/media-icon.png',
'bd-admin/images/screenshots/new-feature-pointer.png',
'bd-admin/images/screenshots/welcome-screen.png',
'bd-includes/css/editor-buttons.css',
'bd-includes/css/editor-buttons.dev.css',
'bd-includes/js/tinymce/plugins/paste/blank.htm',
'bd-includes/js/tinymce/plugins/blasdoise/css',
'bd-includes/js/tinymce/plugins/blasdoise/editor_plugin.dev.js',
'bd-includes/js/tinymce/plugins/blasdoise/img/embedded.png',
'bd-includes/js/tinymce/plugins/blasdoise/img/more_bug.gif',
'bd-includes/js/tinymce/plugins/blasdoise/img/page_bug.gif',
'bd-includes/js/tinymce/plugins/bddialogs/editor_plugin.dev.js',
'bd-includes/js/tinymce/plugins/bdeditimage/css/editimage-rtl.css',
'bd-includes/js/tinymce/plugins/bdeditimage/editor_plugin.dev.js',
'bd-includes/js/tinymce/plugins/bdfullscreen/editor_plugin.dev.js',
'bd-includes/js/tinymce/plugins/bdgallery/editor_plugin.dev.js',
'bd-includes/js/tinymce/plugins/bdgallery/img/gallery.png',
'bd-includes/js/tinymce/plugins/bdlink/editor_plugin.dev.js',
// Don't delete, yet: 'bd-pass.php',
// Don't delete, yet: 'bd-register.php',
'bd-admin/gears-manifest.php',
'bd-admin/includes/manifest.php',
'bd-admin/images/archive-link.png',
'bd-admin/images/blue-grad.png',
'bd-admin/images/button-grad-active.png',
'bd-admin/images/button-grad.png',
'bd-admin/images/ed-bg-vs.gif',
'bd-admin/images/ed-bg.gif',
'bd-admin/images/fade-butt.png',
'bd-admin/images/fav-arrow-rtl.gif',
'bd-admin/images/fav-arrow.gif',
'bd-admin/images/fav-vs.png',
'bd-admin/images/fav.png',
'bd-admin/images/gray-grad.png',
'bd-admin/images/loading-publish.gif',
'bd-admin/images/logo-ghost.png',
'bd-admin/images/logo.gif',
'bd-admin/images/menu-arrow-frame-rtl.png',
'bd-admin/images/menu-arrow-frame.png',
'bd-admin/images/menu-arrows.gif',
'bd-admin/images/menu-bits-rtl-vs.gif',
'bd-admin/images/menu-bits-rtl.gif',
'bd-admin/images/menu-bits-vs.gif',
'bd-admin/images/menu-bits.gif',
'bd-admin/images/menu-dark-rtl-vs.gif',
'bd-admin/images/menu-dark-rtl.gif',
'bd-admin/images/menu-dark-vs.gif',
'bd-admin/images/menu-dark.gif',
'bd-admin/images/required.gif',
'bd-admin/images/screen-options-toggle-vs.gif',
'bd-admin/images/screen-options-toggle.gif',
'bd-admin/images/toggle-arrow-rtl.gif',
'bd-admin/images/toggle-arrow.gif',
'bd-admin/images/upload-classic.png',
'bd-admin/images/upload-fresh.png',
'bd-admin/images/white-grad-active.png',
'bd-admin/images/white-grad.png',
'bd-admin/images/widgets-arrow-vs.gif',
'bd-admin/images/widgets-arrow.gif',
'bd-admin/images/bdspin_dark.gif',
'bd-includes/images/upload.png',
'bd-includes/js/prototype.js',
'bd-includes/js/scriptaculous',
'bd-admin/css/bd-admin-rtl.dev.css',
'bd-admin/css/bd-admin.dev.css',
'bd-admin/css/media-rtl.dev.css',
'bd-admin/css/media.dev.css',
'bd-admin/css/colors-classic.dev.css',
'bd-admin/css/customize-controls-rtl.dev.css',
'bd-admin/css/customize-controls.dev.css',
'bd-admin/css/ie-rtl.dev.css',
'bd-admin/css/ie.dev.css',
'bd-admin/css/install.dev.css',
'bd-admin/css/colors-fresh.dev.css',
'bd-includes/js/customize-base.dev.js',
'bd-includes/js/json2.dev.js',
'bd-includes/js/comment-reply.dev.js',
'bd-includes/js/customize-preview.dev.js',
'bd-includes/js/bdlink.dev.js',
'bd-includes/js/tw-sack.dev.js',
'bd-includes/js/bd-list-revisions.dev.js',
'bd-includes/js/autosave.dev.js',
'bd-includes/js/admin-bar.dev.js',
'bd-includes/js/quicktags.dev.js',
'bd-includes/js/bd-ajax-response.dev.js',
'bd-includes/js/bd-pointer.dev.js',
'bd-includes/js/hoverIntent.dev.js',
'bd-includes/js/colorpicker.dev.js',
'bd-includes/js/bd-lists.dev.js',
'bd-includes/js/customize-loader.dev.js',
'bd-includes/js/jquery/jquery.table-hotkeys.dev.js',
'bd-includes/js/jquery/jquery.color.dev.js',
'bd-includes/js/jquery/jquery.color.js',
'bd-includes/js/jquery/jquery.hotkeys.dev.js',
'bd-includes/js/jquery/jquery.form.dev.js',
'bd-includes/js/jquery/suggest.dev.js',
'bd-admin/js/xfn.dev.js',
'bd-admin/js/set-post-thumbnail.dev.js',
'bd-admin/js/comment.dev.js',
'bd-admin/js/theme.dev.js',
'bd-admin/js/cat.dev.js',
'bd-admin/js/password-strength-meter.dev.js',
'bd-admin/js/user-profile.dev.js',
'bd-admin/js/theme-preview.dev.js',
'bd-admin/js/post.dev.js',
'bd-admin/js/media-upload.dev.js',
'bd-admin/js/word-count.dev.js',
'bd-admin/js/plugin-install.dev.js',
'bd-admin/js/edit-comments.dev.js',
'bd-admin/js/media-gallery.dev.js',
'bd-admin/js/custom-fields.dev.js',
'bd-admin/js/custom-background.dev.js',
'bd-admin/js/common.dev.js',
'bd-admin/js/inline-edit-tax.dev.js',
'bd-admin/js/gallery.dev.js',
'bd-admin/js/utils.dev.js',
'bd-admin/js/widgets.dev.js',
'bd-admin/js/bd-fullscreen.dev.js',
'bd-admin/js/nav-menu.dev.js',
'bd-admin/js/dashboard.dev.js',
'bd-admin/js/link.dev.js',
'bd-admin/js/user-suggest.dev.js',
'bd-admin/js/postbox.dev.js',
'bd-admin/js/tags.dev.js',
'bd-admin/js/image-edit.dev.js',
'bd-admin/js/media.dev.js',
'bd-admin/js/customize-controls.dev.js',
'bd-admin/js/inline-edit-post.dev.js',
'bd-admin/js/categories.dev.js',
'bd-admin/js/editor.dev.js',
'bd-includes/js/tinymce/plugins/bdeditimage/js/editimage.dev.js',
'bd-includes/js/tinymce/plugins/bddialogs/js/popup.dev.js',
'bd-includes/js/tinymce/plugins/bddialogs/js/bddialog.dev.js',
'bd-includes/js/plupload/handlers.dev.js',
'bd-includes/js/plupload/bd-plupload.dev.js',
'bd-includes/js/swfupload/handlers.dev.js',
'bd-includes/js/jcrop/jquery.jcrop.dev.js',
'bd-includes/js/jcrop/jquery.jcrop.js',
'bd-includes/js/jcrop/jquery.jcrop.css',
'bd-includes/js/imgareaselect/jquery.imgareaselect.dev.js',
'bd-includes/css/bd-pointer.dev.css',
'bd-includes/css/editor.dev.css',
'bd-includes/css/jquery-ui-dialog.dev.css',
'bd-includes/css/admin-bar-rtl.dev.css',
'bd-includes/css/admin-bar.dev.css',
'bd-includes/js/jquery/ui/jquery.effects.clip.min.js',
'bd-includes/js/jquery/ui/jquery.effects.scale.min.js',
'bd-includes/js/jquery/ui/jquery.effects.blind.min.js',
'bd-includes/js/jquery/ui/jquery.effects.core.min.js',
'bd-includes/js/jquery/ui/jquery.effects.shake.min.js',
'bd-includes/js/jquery/ui/jquery.effects.fade.min.js',
'bd-includes/js/jquery/ui/jquery.effects.explode.min.js',
'bd-includes/js/jquery/ui/jquery.effects.slide.min.js',
'bd-includes/js/jquery/ui/jquery.effects.drop.min.js',
'bd-includes/js/jquery/ui/jquery.effects.highlight.min.js',
'bd-includes/js/jquery/ui/jquery.effects.bounce.min.js',
'bd-includes/js/jquery/ui/jquery.effects.pulsate.min.js',
'bd-includes/js/jquery/ui/jquery.effects.transfer.min.js',
'bd-includes/js/jquery/ui/jquery.effects.fold.min.js',
'bd-admin/images/screenshots/captions-1.png',
'bd-admin/images/screenshots/captions-2.png',
'bd-admin/images/screenshots/flex-header-1.png',
'bd-admin/images/screenshots/flex-header-2.png',
'bd-admin/images/screenshots/flex-header-3.png',
'bd-admin/images/screenshots/flex-header-media-library.png',
'bd-admin/images/screenshots/theme-customizer.png',
'bd-admin/images/screenshots/twitter-embed-1.png',
'bd-admin/images/screenshots/twitter-embed-2.png',
'bd-admin/js/utils.js',
'bd-admin/options-privacy.php',
'bd-app.php',
'bd-includes/class-bd-atom-server.php',
'bd-includes/js/tinymce/themes/advanced/skins/bd_theme/ui.css',
'bd-includes/js/swfupload/swfupload-all.js',
'bd-admin/js/revisions-js.php',
'bd-admin/images/screenshots',
'bd-admin/js/categories.js',
'bd-admin/js/categories.min.js',
'bd-admin/js/custom-fields.js',
'bd-admin/js/custom-fields.min.js',
'bd-admin/js/cat.js',
'bd-admin/js/cat.min.js',
'bd-includes/js/tinymce/plugins/bdeditimage/js/editimage.min.js',
'bd-includes/js/tinymce/themes/advanced/skins/bd_theme/img/page_bug.gif',
'bd-includes/js/tinymce/themes/advanced/skins/bd_theme/img/more_bug.gif',
'bd-includes/js/thickbox/tb-close-2x.png',
'bd-includes/js/thickbox/tb-close.png',
'bd-includes/images/bdmini-blue-2x.png',
'bd-includes/images/bdmini-blue.png',
'bd-admin/css/colors-fresh.css',
'bd-admin/css/colors-classic.css',
'bd-admin/css/colors-fresh.min.css',
'bd-admin/css/colors-classic.min.css',
'bd-admin/js/about.min.js',
'bd-admin/js/about.js',
'bd-admin/images/arrows-dark-vs-2x.png',
'bd-admin/images/bd-logo-vs.png',
'bd-admin/images/arrows-dark-vs.png',
'bd-admin/images/bd-logo.png',
'bd-admin/images/arrows-pr.png',
'bd-admin/images/arrows-dark.png',
'bd-admin/images/press-this.png',
'bd-admin/images/press-this-2x.png',
'bd-admin/images/arrows-vs-2x.png',
'bd-admin/images/welcome-icons.png',
'bd-admin/images/bd-logo-2x.png',
'bd-admin/images/stars-rtl-2x.png',
'bd-admin/images/arrows-dark-2x.png',
'bd-admin/images/arrows-pr-2x.png',
'bd-admin/images/menu-shadow-rtl.png',
'bd-admin/images/arrows-vs.png',
'bd-admin/images/about-search-2x.png',
'bd-admin/images/bubble_bg-rtl-2x.gif',
'bd-admin/images/bd-badge-2x.png',
'bd-admin/images/blasdoise-logo-2x.png',
'bd-admin/images/bubble_bg-rtl.gif',
'bd-admin/images/bd-badge.png',
'bd-admin/images/menu-shadow.png',
'bd-admin/images/about-globe-2x.png',
'bd-admin/images/welcome-icons-2x.png',
'bd-admin/images/stars-rtl.png',
'bd-admin/images/bd-logo-vs-2x.png',
'bd-admin/images/about-updates-2x.png',
'bd-admin/css/colors.css',
'bd-admin/css/colors.min.css',
'bd-admin/css/colors-rtl.css',
'bd-admin/css/colors-rtl.min.css',
'bd-admin/css/media-rtl.min.css',
'bd-admin/css/media.min.css',
'bd-admin/css/farbtastic-rtl.min.css',
'bd-admin/images/lock-2x.png',
'bd-admin/images/lock.png',
'bd-admin/js/theme-preview.js',
'bd-admin/js/theme-install.min.js',
'bd-admin/js/theme-install.js',
'bd-admin/js/theme-preview.min.js',
'bd-includes/js/plupload/plupload.html4.js',
'bd-includes/js/plupload/plupload.html5.js',
'bd-includes/js/plupload/changelog.txt',
'bd-includes/js/plupload/plupload.silverlight.js',
'bd-includes/js/plupload/plupload.flash.js',
'bd-includes/js/plupload/plupload.js',
'bd-includes/js/tinymce/plugins/spellchecker',
'bd-includes/js/tinymce/plugins/inlinepopups',
'bd-includes/js/tinymce/plugins/media/js',
'bd-includes/js/tinymce/plugins/media/css',
'bd-includes/js/tinymce/plugins/blasdoise/img',
'bd-includes/js/tinymce/plugins/bddialogs/js',
'bd-includes/js/tinymce/plugins/bdeditimage/img',
'bd-includes/js/tinymce/plugins/bdeditimage/js',
'bd-includes/js/tinymce/plugins/bdeditimage/css',
'bd-includes/js/tinymce/plugins/bdgallery/img',
'bd-includes/js/tinymce/plugins/bdfullscreen/css',
'bd-includes/js/tinymce/plugins/paste/js',
'bd-includes/js/tinymce/themes/advanced',
'bd-includes/js/tinymce/tiny_mce.js',
'bd-includes/js/tinymce/mark_loaded_src.js',
'bd-includes/js/tinymce/bd-tinymce-schema.js',
'bd-includes/js/tinymce/plugins/media/editor_plugin.js',
'bd-includes/js/tinymce/plugins/media/editor_plugin_src.js',
'bd-includes/js/tinymce/plugins/media/media.htm',
'bd-includes/js/tinymce/plugins/bdview/editor_plugin_src.js',
'bd-includes/js/tinymce/plugins/bdview/editor_plugin.js',
'bd-includes/js/tinymce/plugins/directionality/editor_plugin.js',
'bd-includes/js/tinymce/plugins/directionality/editor_plugin_src.js',
'bd-includes/js/tinymce/plugins/blasdoise/editor_plugin.js',
'bd-includes/js/tinymce/plugins/blasdoise/editor_plugin_src.js',
'bd-includes/js/tinymce/plugins/bddialogs/editor_plugin_src.js',
'bd-includes/js/tinymce/plugins/bddialogs/editor_plugin.js',
'bd-includes/js/tinymce/plugins/bdeditimage/editimage.html',
'bd-includes/js/tinymce/plugins/bdeditimage/editor_plugin.js',
'bd-includes/js/tinymce/plugins/bdeditimage/editor_plugin_src.js',
'bd-includes/js/tinymce/plugins/fullscreen/editor_plugin_src.js',
'bd-includes/js/tinymce/plugins/fullscreen/fullscreen.htm',
'bd-includes/js/tinymce/plugins/fullscreen/editor_plugin.js',
'bd-includes/js/tinymce/plugins/bdlink/editor_plugin_src.js',
'bd-includes/js/tinymce/plugins/bdlink/editor_plugin.js',
'bd-includes/js/tinymce/plugins/bdgallery/editor_plugin_src.js',
'bd-includes/js/tinymce/plugins/bdgallery/editor_plugin.js',
'bd-includes/js/tinymce/plugins/tabfocus/editor_plugin.js',
'bd-includes/js/tinymce/plugins/tabfocus/editor_plugin_src.js',
'bd-includes/js/tinymce/plugins/bdfullscreen/editor_plugin.js',
'bd-includes/js/tinymce/plugins/bdfullscreen/editor_plugin_src.js',
'bd-includes/js/tinymce/plugins/paste/editor_plugin.js',
'bd-includes/js/tinymce/plugins/paste/pasteword.htm',
'bd-includes/js/tinymce/plugins/paste/editor_plugin_src.js',
'bd-includes/js/tinymce/plugins/paste/pastetext.htm',
'bd-includes/js/tinymce/langs/bd-langs.php',
'bd-includes/js/jquery/ui/jquery.ui.accordion.min.js',
'bd-includes/js/jquery/ui/jquery.ui.autocomplete.min.js',
'bd-includes/js/jquery/ui/jquery.ui.button.min.js',
'bd-includes/js/jquery/ui/jquery.ui.core.min.js',
'bd-includes/js/jquery/ui/jquery.ui.datepicker.min.js',
'bd-includes/js/jquery/ui/jquery.ui.dialog.min.js',
'bd-includes/js/jquery/ui/jquery.ui.draggable.min.js',
'bd-includes/js/jquery/ui/jquery.ui.droppable.min.js',
'bd-includes/js/jquery/ui/jquery.ui.effect-blind.min.js',
'bd-includes/js/jquery/ui/jquery.ui.effect-bounce.min.js',
'bd-includes/js/jquery/ui/jquery.ui.effect-clip.min.js',
'bd-includes/js/jquery/ui/jquery.ui.effect-drop.min.js',
'bd-includes/js/jquery/ui/jquery.ui.effect-explode.min.js',
'bd-includes/js/jquery/ui/jquery.ui.effect-fade.min.js',
'bd-includes/js/jquery/ui/jquery.ui.effect-fold.min.js',
'bd-includes/js/jquery/ui/jquery.ui.effect-highlight.min.js',
'bd-includes/js/jquery/ui/jquery.ui.effect-pulsate.min.js',
'bd-includes/js/jquery/ui/jquery.ui.effect-scale.min.js',
'bd-includes/js/jquery/ui/jquery.ui.effect-shake.min.js',
'bd-includes/js/jquery/ui/jquery.ui.effect-slide.min.js',
'bd-includes/js/jquery/ui/jquery.ui.effect-transfer.min.js',
'bd-includes/js/jquery/ui/jquery.ui.effect.min.js',
'bd-includes/js/jquery/ui/jquery.ui.menu.min.js',
'bd-includes/js/jquery/ui/jquery.ui.mouse.min.js',
'bd-includes/js/jquery/ui/jquery.ui.position.min.js',
'bd-includes/js/jquery/ui/jquery.ui.progressbar.min.js',
'bd-includes/js/jquery/ui/jquery.ui.resizable.min.js',
'bd-includes/js/jquery/ui/jquery.ui.selectable.min.js',
'bd-includes/js/jquery/ui/jquery.ui.slider.min.js',
'bd-includes/js/jquery/ui/jquery.ui.sortable.min.js',
'bd-includes/js/jquery/ui/jquery.ui.spinner.min.js',
'bd-includes/js/jquery/ui/jquery.ui.tabs.min.js',
'bd-includes/js/jquery/ui/jquery.ui.tooltip.min.js',
'bd-includes/js/jquery/ui/jquery.ui.widget.min.js',
'bd-admin/js/bd-fullscreen.js',
'bd-admin/js/bd-fullscreen.min.js',
'bd-includes/js/tinymce/bd-mce-help.php',
'bd-includes/js/tinymce/plugins/bdfullscreen',
);

/**
 * Stores new files in bd-content to copy
 *
 * The contents of this array indicate any new bundled plugins/themes which
 * should be installed with the Blasdoise Upgrade. These items will not be
 * re-installed in future upgrades, this behaviour is controlled by the
 * introduced version present here being older than the current installed version.
 *
 * The content of this array should follow the following format:
 * Filename (relative to bd-content) => Introduced version
 * Directories should be noted by suffixing it with a trailing slash (/)
 *
 * @since 1.0.0
 * @global array $_new_bundled_files
 * @var array
 * @name $_new_bundled_files
 */
global $_new_bundled_files;

$_new_bundled_files = array(
	'plugins/hello-dolly/'     => '1.0',
	'themes/fairy/'            => '1.0',
);

/**
 * Upgrade the core of Blasdoise.
 *
 * This will create a .maintenance file at the base of the Blasdoise directory
 * to ensure that people can not access the web site, when the files are being
 * copied to their locations.
 *
 * The files in the {@link $_old_files} list will be removed and the new files
 * copied from the zip file after the database is upgraded.
 *
 * The files in the {@link $_new_bundled_files} list will be added to the installation
 * if the version is greater than or equal to the old version being upgraded.
 *
 * The steps for the upgrader for after the new release is downloaded and
 * unzipped is:
 *   1. Test unzipped location for select files to ensure that unzipped worked.
 *   2. Create the .maintenance file in current Blasdoise base.
 *   3. Copy new Blasdoise directory over old Blasdoise files.
 *   4. Upgrade Blasdoise to new version.
 *     4.1. Copy all files/folders other than bd-content
 *     4.2. Copy any language files to BD_LANG_DIR (which may differ from BD_CONTENT_DIR
 *     4.3. Copy any new bundled themes/plugins to their respective locations
 *   5. Delete new Blasdoise directory path.
 *   6. Delete .maintenance file.
 *   7. Remove old files.
 *   8. Delete 'update_core' option.
 *
 * There are several areas of failure. For instance if PHP times out before step
 * 6, then you will not be able to access any portion of your site. Also, since
 * the upgrade will not continue where it left off, you will not be able to
 * automatically remove old files and remove the 'update_core' option. This
 * isn't that bad.
 *
 * If the copy of the new Blasdoise over the old fails, then the worse is that
 * the new Blasdoise directory will remain.
 *
 * If it is assumed that every file will be copied over, including plugins and
 * themes, then if you edit the default theme, you should rename it, so that
 * your changes remain.
 *
 * @since 1.0.0
 *
 * @global BD_Filesystem_Base $bd_filesystem
 * @global array              $_old_files
 * @global array              $_new_bundled_files
 * @global bddb               $bddb
 * @global string             $bd_version
 * @global string             $required_php_version
 * @global string             $required_mysql_version
 *
 * @param string $from New release unzipped path.
 * @param string $to   Path to old Blasdoise installation.
 * @return BD_Error|null BD_Error on failure, null on success.
 */
function update_core($from, $to) {
	global $bd_filesystem, $_old_files, $_new_bundled_files, $bddb;

	@set_time_limit( 300 );

	/**
	 * Filter feedback messages displayed during the core update process.
	 *
	 * The filter is first evaluated after the zip file for the latest version
	 * has been downloaded and unzipped. It is evaluated five more times during
	 * the process:
	 *
	 * 1. Before Blasdoise begins the core upgrade process.
	 * 2. Before Maintenance Mode is enabled.
	 * 3. Before Blasdoise begins copying over the necessary files.
	 * 4. Before Maintenance Mode is disabled.
	 * 5. Before the database is upgraded.
	 *
	 * @since 1.0.0
	 *
	 * @param string $feedback The core update feedback messages.
	 */
	apply_filters( 'update_feedback', __( 'Verifying the unpacked files&#8230;' ) );

	// Sanity check the unzipped distribution.
	$distro = '';
	$roots = array( '/blasdoise/', '/blasdoise-mu/' );
	foreach ( $roots as $root ) {
		if ( $bd_filesystem->exists( $from . $root . 'readme.html' ) && $bd_filesystem->exists( $from . $root . 'bd-includes/version.php' ) ) {
			$distro = $root;
			break;
		}
	}
	if ( ! $distro ) {
		$bd_filesystem->delete( $from, true );
		return new BD_Error( 'insane_distro', __('The update could not be unpacked') );
	}


	/**
	 * Import $bd_version, $required_php_version, and $required_mysql_version from the new version
	 * $bd_filesystem->bd_content_dir() returned unslashed
	 *
	 * @global string $bd_version
	 * @global string $required_php_version
	 * @global string $required_mysql_version
	 */
	global $bd_version, $required_php_version, $required_mysql_version;

	$versions_file = trailingslashit( $bd_filesystem->bd_content_dir() ) . 'upgrade/version-current.php';
	if ( ! $bd_filesystem->copy( $from . $distro . 'bd-includes/version.php', $versions_file ) ) {
		$bd_filesystem->delete( $from, true );
		return new BD_Error( 'copy_failed_for_version_file', __( 'The update cannot be installed because we will be unable to copy some files. This is usually due to inconsistent file permissions.' ), 'bd-includes/version.php' );
	}

	$bd_filesystem->chmod( $versions_file, FS_CHMOD_FILE );
	require( BD_CONTENT_DIR . '/upgrade/version-current.php' );
	$bd_filesystem->delete( $versions_file );

	$php_version    = phpversion();
	$mysql_version  = $bddb->db_version();
	$old_bd_version = $bd_version; // The version of Blasdoise we're updating from
	$development_build = ( false !== strpos( $old_bd_version . $bd_version, '-' )  ); // a dash in the version indicates a Development release
	$php_compat     = version_compare( $php_version, $required_php_version, '>=' );
	if ( file_exists( BD_CONTENT_DIR . '/db.php' ) && empty( $bddb->is_mysql ) )
		$mysql_compat = true;
	else
		$mysql_compat = version_compare( $mysql_version, $required_mysql_version, '>=' );

	if ( !$mysql_compat || !$php_compat )
		$bd_filesystem->delete($from, true);

	if ( !$mysql_compat && !$php_compat )
		return new BD_Error( 'php_mysql_not_compatible', sprintf( __('The update cannot be installed because Blasdoise %1$s requires PHP version %2$s or higher and MySQL version %3$s or higher. You are running PHP version %4$s and MySQL version %5$s.'), $bd_version, $required_php_version, $required_mysql_version, $php_version, $mysql_version ) );
	elseif ( !$php_compat )
		return new BD_Error( 'php_not_compatible', sprintf( __('The update cannot be installed because Blasdoise %1$s requires PHP version %2$s or higher. You are running version %3$s.'), $bd_version, $required_php_version, $php_version ) );
	elseif ( !$mysql_compat )
		return new BD_Error( 'mysql_not_compatible', sprintf( __('The update cannot be installed because Blasdoise %1$s requires MySQL version %2$s or higher. You are running version %3$s.'), $bd_version, $required_mysql_version, $mysql_version ) );

	/** This filter is documented in bd-admin/includes/update-core.php */
	apply_filters( 'update_feedback', __( 'Preparing to install the latest version&#8230;' ) );

	// Don't copy bd-content, we'll deal with that below
	// We also copy version.php last so failed updates report their old version
	$skip = array( 'bd-content', 'bd-includes/version.php' );
	$check_is_writable = array();

	// Check to see which files don't really need updating
	if ( function_exists( 'get_core_checksums' ) ) {
		// Find the local version of the working directory
		$working_dir_local = BD_CONTENT_DIR . '/upgrade/' . basename( $from ) . $distro;

		$checksums = get_core_checksums( $bd_version, isset( $bd_local_package ) ? $bd_local_package : 'en_US' );
		if ( is_array( $checksums ) && isset( $checksums[ $bd_version ] ) )
			$checksums = $checksums[ $bd_version ];
		if ( is_array( $checksums ) ) {
			foreach( $checksums as $file => $checksum ) {
				if ( 'bd-content' == substr( $file, 0, 10 ) )
					continue;
				if ( ! file_exists( ABSPATH . $file ) )
					continue;
				if ( ! file_exists( $working_dir_local . $file ) )
					continue;
				if ( md5_file( ABSPATH . $file ) === $checksum )
					$skip[] = $file;
				else
					$check_is_writable[ $file ] = ABSPATH . $file;
			}
		}
	}

	// If we're using the direct method, we can predict write failures that are due to permissions.
	if ( $check_is_writable && 'direct' === $bd_filesystem->method ) {
		$files_writable = array_filter( $check_is_writable, array( $bd_filesystem, 'is_writable' ) );
		if ( $files_writable !== $check_is_writable ) {
			$files_not_writable = array_diff_key( $check_is_writable, $files_writable );
			foreach ( $files_not_writable as $relative_file_not_writable => $file_not_writable ) {
				// If the writable check failed, chmod file to 0644 and try again, same as copy_dir().
				$bd_filesystem->chmod( $file_not_writable, FS_CHMOD_FILE );
				if ( $bd_filesystem->is_writable( $file_not_writable ) )
					unset( $files_not_writable[ $relative_file_not_writable ] );
			}

			// Store package-relative paths (the key) of non-writable files in the BD_Error object.
			$error_data = version_compare( $old_bd_version, '1.0-beta', '>' ) ? array_keys( $files_not_writable ) : '';

			if ( $files_not_writable )
				return new BD_Error( 'files_not_writable', __( 'The update cannot be installed because we will be unable to copy some files. This is usually due to inconsistent file permissions.' ), implode( ', ', $error_data ) );
		}
	}

	/** This filter is documented in bd-admin/includes/update-core.php */
	apply_filters( 'update_feedback', __( 'Enabling Maintenance mode&#8230;' ) );
	// Create maintenance file to signal that we are upgrading
	$maintenance_string = '<?php $upgrading = ' . time() . '; ?>';
	$maintenance_file = $to . '.maintenance';
	$bd_filesystem->delete($maintenance_file);
	$bd_filesystem->put_contents($maintenance_file, $maintenance_string, FS_CHMOD_FILE);

	/** This filter is documented in bd-admin/includes/update-core.php */
	apply_filters( 'update_feedback', __( 'Copying the required files&#8230;' ) );
	// Copy new versions of BD files into place.
	$result = _copy_dir( $from . $distro, $to, $skip );
	if ( is_bd_error( $result ) )
		$result = new BD_Error( $result->get_error_code(), $result->get_error_message(), substr( $result->get_error_data(), strlen( $to ) ) );

	// Since we know the core files have copied over, we can now copy the version file
	if ( ! is_bd_error( $result ) ) {
		if ( ! $bd_filesystem->copy( $from . $distro . 'bd-includes/version.php', $to . 'bd-includes/version.php', true /* overwrite */ ) ) {
			$bd_filesystem->delete( $from, true );
			$result = new BD_Error( 'copy_failed_for_version_file', __( 'The update cannot be installed because we will be unable to copy some files. This is usually due to inconsistent file permissions.' ), 'bd-includes/version.php' );
		}
		$bd_filesystem->chmod( $to . 'bd-includes/version.php', FS_CHMOD_FILE );
	}

	// Check to make sure everything copied correctly, ignoring the contents of bd-content
	$skip = array( 'bd-content' );
	$failed = array();
	if ( isset( $checksums ) && is_array( $checksums ) ) {
		foreach ( $checksums as $file => $checksum ) {
			if ( 'bd-content' == substr( $file, 0, 10 ) )
				continue;
			if ( ! file_exists( $working_dir_local . $file ) )
				continue;
			if ( file_exists( ABSPATH . $file ) && md5_file( ABSPATH . $file ) == $checksum )
				$skip[] = $file;
			else
				$failed[] = $file;
		}
	}

	// Some files didn't copy properly
	if ( ! empty( $failed ) ) {
		$total_size = 0;
		foreach ( $failed as $file ) {
			if ( file_exists( $working_dir_local . $file ) )
				$total_size += filesize( $working_dir_local . $file );
		}

		// If we don't have enough free space, it isn't worth trying again.
		// Unlikely to be hit due to the check in unzip_file().
		$available_space = @disk_free_space( ABSPATH );
		if ( $available_space && $total_size >= $available_space ) {
			$result = new BD_Error( 'disk_full', __( 'There is not enough free disk space to complete the update.' ) );
		} else {
			$result = _copy_dir( $from . $distro, $to, $skip );
			if ( is_bd_error( $result ) )
				$result = new BD_Error( $result->get_error_code() . '_retry', $result->get_error_message(), substr( $result->get_error_data(), strlen( $to ) ) );
		}
	}

	// Custom Content Directory needs updating now.
	// Copy Languages
	if ( !is_bd_error($result) && $bd_filesystem->is_dir($from . $distro . 'bd-content/languages') ) {
		if ( BD_LANG_DIR != ABSPATH . BDINC . '/languages' || @is_dir(BD_LANG_DIR) )
			$lang_dir = BD_LANG_DIR;
		else
			$lang_dir = BD_CONTENT_DIR . '/languages';

		if ( !@is_dir($lang_dir) && 0 === strpos($lang_dir, ABSPATH) ) { // Check the language directory exists first
			$bd_filesystem->mkdir($to . str_replace(ABSPATH, '', $lang_dir), FS_CHMOD_DIR); // If it's within the ABSPATH we can handle it here, otherwise they're out of luck.
			clearstatcache(); // for FTP, Need to clear the stat cache
		}

		if ( @is_dir($lang_dir) ) {
			$bd_lang_dir = $bd_filesystem->find_folder($lang_dir);
			if ( $bd_lang_dir ) {
				$result = copy_dir($from . $distro . 'bd-content/languages/', $bd_lang_dir);
				if ( is_bd_error( $result ) )
					$result = new BD_Error( $result->get_error_code() . '_languages', $result->get_error_message(), substr( $result->get_error_data(), strlen( $bd_lang_dir ) ) );
			}
		}
	}

	/** This filter is documented in bd-admin/includes/update-core.php */
	apply_filters( 'update_feedback', __( 'Disabling Maintenance mode&#8230;' ) );
	// Remove maintenance file, we're done with potential site-breaking changes
	$bd_filesystem->delete( $maintenance_file );

	// An empty fairy directory was created upon for some users, preventing installation of Fairy.
	if ( '1.0' == $old_bd_version ) {
		if ( is_dir( BD_CONTENT_DIR . '/themes/fairy' ) && ! file_exists( BD_CONTENT_DIR . '/themes/fairy/style.css' )  ) {
			$bd_filesystem->delete( $bd_filesystem->bd_themes_dir() . 'fairy/' );
		}
	}

	// Copy New bundled plugins & themes
	// This gives us the ability to install new plugins & themes bundled with future versions of Blasdoise whilst avoiding the re-install upon upgrade issue.
	// $development_build controls us overwriting bundled themes and plugins when a non-stable release is being updated
	if ( !is_bd_error($result) && ( ! defined('CORE_UPGRADE_SKIP_NEW_BUNDLED') || ! CORE_UPGRADE_SKIP_NEW_BUNDLED ) ) {
		foreach ( (array) $_new_bundled_files as $file => $introduced_version ) {
			// If a $development_build or if $introduced version is greater than what the site was previously running
			if ( $development_build || version_compare( $introduced_version, $old_bd_version, '>' ) ) {
				$directory = ('/' == $file[ strlen($file)-1 ]);
				list($type, $filename) = explode('/', $file, 2);

				// Check to see if the bundled items exist before attempting to copy them
				if ( ! $bd_filesystem->exists( $from . $distro . 'bd-content/' . $file ) )
					continue;

				if ( 'plugins' == $type )
					$dest = $bd_filesystem->bd_plugins_dir();
				elseif ( 'themes' == $type )
					$dest = trailingslashit($bd_filesystem->bd_themes_dir()); // Back-compat, ::bd_themes_dir() did not return trailingslash'd
				else
					continue;

				if ( ! $directory ) {
					if ( ! $development_build && $bd_filesystem->exists( $dest . $filename ) )
						continue;

					if ( ! $bd_filesystem->copy($from . $distro . 'bd-content/' . $file, $dest . $filename, FS_CHMOD_FILE) )
						$result = new BD_Error( "copy_failed_for_new_bundled_$type", __( 'Could not copy file.' ), $dest . $filename );
				} else {
					if ( ! $development_build && $bd_filesystem->is_dir( $dest . $filename ) )
						continue;

					$bd_filesystem->mkdir($dest . $filename, FS_CHMOD_DIR);
					$_result = copy_dir( $from . $distro . 'bd-content/' . $file, $dest . $filename);

					// If a error occurs partway through this final step, keep the error flowing through, but keep process going.
					if ( is_bd_error( $_result ) ) {
						if ( ! is_bd_error( $result ) )
							$result = new BD_Error;
						$result->add( $_result->get_error_code() . "_$type", $_result->get_error_message(), substr( $_result->get_error_data(), strlen( $dest ) ) );
					}
				}
			}
		} //end foreach
	}

	// Handle $result error from the above blocks
	if ( is_bd_error($result) ) {
		$bd_filesystem->delete($from, true);
		return $result;
	}

	// Remove old files
	foreach ( $_old_files as $old_file ) {
		$old_file = $to . $old_file;
		if ( !$bd_filesystem->exists($old_file) )
			continue;
		$bd_filesystem->delete($old_file, true);
	}

	// Remove any Genericons example.html's from the filesystem
	_upgrade_422_remove_genericons();

	// Upgrade DB with separate request
	/** This filter is documented in bd-admin/includes/update-core.php */
	apply_filters( 'update_feedback', __( 'Upgrading database&#8230;' ) );
	$db_upgrade_url = admin_url('upgrade.php?step=upgrade_db');
	bd_remote_post($db_upgrade_url, array('timeout' => 60));

	// Clear the cache to prevent an update_option() from saving a stale db_version to the cache
	bd_cache_flush();
	// (Not all cache backends listen to 'flush')
	bd_cache_delete( 'alloptions', 'options' );

	// Remove working directory
	$bd_filesystem->delete($from, true);

	// Force refresh of update information
	if ( function_exists('delete_site_transient') )
		delete_site_transient('update_core');
	else
		delete_option('update_core');

	/**
	 * Fires after Blasdoise core has been successfully updated.
	 *
	 * @since 1.0.0
	 *
	 * @param string $bd_version The current Blasdoise version.
	 */
	do_action( '_core_updated_successfully', $bd_version );

	// Clear the option that blocks auto updates after failures, now that we've been successful.
	if ( function_exists( 'delete_site_option' ) )
		delete_site_option( 'auto_core_update_failed' );

	return $bd_version;
}

/**
 * Copies a directory from one location to another via the Blasdoise Filesystem Abstraction.
 * Assumes that BD_Filesystem() has already been called and setup.
 *
 * @ignore
 * @since 1.0.0
 * @see copy_dir()
 *
 * @global BD_Filesystem_Base $bd_filesystem
 *
 * @param string $from     source directory
 * @param string $to       destination directory
 * @param array $skip_list a list of files/folders to skip copying
 * @return mixed BD_Error on failure, True on success.
 */
function _copy_dir($from, $to, $skip_list = array() ) {
	global $bd_filesystem;

	$dirlist = $bd_filesystem->dirlist($from);

	$from = trailingslashit($from);
	$to = trailingslashit($to);

	foreach ( (array) $dirlist as $filename => $fileinfo ) {
		if ( in_array( $filename, $skip_list ) )
			continue;

		if ( 'f' == $fileinfo['type'] ) {
			if ( ! $bd_filesystem->copy($from . $filename, $to . $filename, true, FS_CHMOD_FILE) ) {
				// If copy failed, chmod file to 0644 and try again.
				$bd_filesystem->chmod( $to . $filename, FS_CHMOD_FILE );
				if ( ! $bd_filesystem->copy($from . $filename, $to . $filename, true, FS_CHMOD_FILE) )
					return new BD_Error( 'copy_failed__copy_dir', __( 'Could not copy file.' ), $to . $filename );
			}
		} elseif ( 'd' == $fileinfo['type'] ) {
			if ( !$bd_filesystem->is_dir($to . $filename) ) {
				if ( !$bd_filesystem->mkdir($to . $filename, FS_CHMOD_DIR) )
					return new BD_Error( 'mkdir_failed__copy_dir', __( 'Could not create directory.' ), $to . $filename );
			}

			/*
			 * Generate the $sub_skip_list for the subdirectory as a sub-set
			 * of the existing $skip_list.
			 */
			$sub_skip_list = array();
			foreach ( $skip_list as $skip_item ) {
				if ( 0 === strpos( $skip_item, $filename . '/' ) )
					$sub_skip_list[] = preg_replace( '!^' . preg_quote( $filename, '!' ) . '/!i', '', $skip_item );
			}

			$result = _copy_dir($from . $filename, $to . $filename, $sub_skip_list);
			if ( is_bd_error($result) )
				return $result;
		}
	}
	return true;
}

/**
 * Redirect to the About Blasdoise page after a successful upgrade.
 *
 * @since 1.0.0
 *
 * @global string $bd_version
 * @global string $pagenow
 * @global string $action
 *
 * @param string $new_version
 */
function _redirect_to_about_blasdoise( $new_version ) {
	global $bd_version, $pagenow, $action;

	if ( version_compare( $bd_version, '1.0-RC', '>=' ) )
		return;

	// Ensure we only run this on the update-core.php page. The Core_Upgrader may be used in other contexts.
	if ( 'update-core.php' != $pagenow )
		return;

 	if ( 'do-core-upgrade' != $action && 'do-core-reinstall' != $action )
 		return;

	// Load the updated default text localization domain for new strings.
	load_default_textdomain();

	// See do_core_upgrade()
	show_message( __('Blasdoise updated successfully') );

	// self_admin_url(), so relative URLs are intentional.
	show_message( '<span class="hide-if-no-js">' . sprintf( __( 'Welcome to Blasdoise %1$s. You will be redirected to the About Blasdoise screen. If not, click <a href="%2$s">here</a>.' ), $new_version, 'about.php?updated' ) . '</span>' );
	show_message( '<span class="hide-if-js">' . sprintf( __( 'Welcome to Blasdoise %1$s. <a href="%2$s">Learn more</a>.' ), $new_version, 'about.php?updated' ) . '</span>' );
	echo '</div>';
	?>
<script type="text/javascript">
window.location = 'about.php?updated';
</script>
	<?php

	// Include admin-footer.php and exit.
	include(ABSPATH . 'bd-admin/admin-footer.php');
	exit();
}

/**
 * Cleans up Genericons example files.
 *
 * @since 1.0.0
 *
 * @global array              $bd_theme_directories
 * @global BD_Filesystem_Base $bd_filesystem
 */
function _upgrade_422_remove_genericons() {
	global $bd_theme_directories, $bd_filesystem;

	// A list of the affected files using the filesystem absolute paths.
	$affected_files = array();

	// Themes
	foreach ( $bd_theme_directories as $directory ) {
		$affected_theme_files = _upgrade_422_find_genericons_files_in_folder( $directory );
		$affected_files       = array_merge( $affected_files, $affected_theme_files );
	}

	// Plugins
	$affected_plugin_files = _upgrade_422_find_genericons_files_in_folder( BD_PLUGIN_DIR );
	$affected_files        = array_merge( $affected_files, $affected_plugin_files );

	foreach ( $affected_files as $file ) {
		$gen_dir = $bd_filesystem->find_folder( trailingslashit( dirname( $file ) ) );
		if ( empty( $gen_dir ) ) {
			continue;
		}

		// The path when the file is accessed via BD_Filesystem may differ in the case of FTP
		$remote_file = $gen_dir . basename( $file );

		if ( ! $bd_filesystem->exists( $remote_file ) ) {
			continue;
		}

		if ( ! $bd_filesystem->delete( $remote_file, false, 'f' ) ) {
			$bd_filesystem->put_contents( $remote_file, '' );
		}
	}
}

/**
 * Recursively find Genericons example files in a given folder.
 *
 * @ignore
 * @since 1.0.0
 *
 * @param string $directory Directory path. Expects trailingslashed.
 * @return array
 */
function _upgrade_422_find_genericons_files_in_folder( $directory ) {
	$directory = trailingslashit( $directory );
	$files     = array();

	if ( file_exists( "{$directory}example.html" ) && false !== strpos( file_get_contents( "{$directory}example.html" ), '<title>Genericons</title>' ) ) {
		$files[] = "{$directory}example.html";
	}

	$dirs = glob( $directory . '*', GLOB_ONLYDIR );
	if ( $dirs ) {
		foreach ( $dirs as $dir ) {
			$files = array_merge( $files, _upgrade_422_find_genericons_files_in_folder( $dir ) );
		}
	}

	return $files;
}
