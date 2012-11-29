<?php
/**
 * This is a replacement for ZenPhoto's built-in commenting system using Facebook's commenting plugin
 * @author Carl Chan (carlchan)
 * @package plugins
 */
$plugin_is_filter = 5|ADMIN_PLUGIN|THEME_PLUGIN;
$plugin_description = gettext("Facebook Comment replacement for default ZP comment forms");
$plugin_author = "Carl Chan (CarlChan)";

$option_interface = 'fbcomments';
class fbcomments {
	function fbcomments() {
		setOptionDefault('fbcomments_APIkey','');
	}

	function getOptionsSupported() {
				return  array(gettext('API Key') => array('key' => 'fbcomments_APIkey', 'type' => OPTION_TYPE_TEXTBOX,
												'order'=>1,
												'desc' => gettext('FaceBook Developer API Key'))
							);
	}
	
}

//////////////////////////////////

function getCurrentPageObject() {
		global $_zp_gallery_page, $_zp_current_album, $_zp_current_image, $_zp_current_zenpage_news, $_zp_current_zenpage_page;
		switch ($_zp_gallery_page) {
			case 'album.php':
				return $_zp_current_album;
			case 'image.php':
				return $_zp_current_image;
			case 'news.php':
				return $_zp_current_zenpage_news;
			case 'pages.php':
				return $_zp_current_zenpage_page;
			default:
				return NULL;
		}
	}

function printCommentForm($obj, $showcomments=true) {
	global $_zp_gallery_page;
	switch ($_zp_gallery_page) {
		case 'album.php':
			if (!getOption('comment_form_albums')) return;
			$comments_open = OpenedForComments(ALBUM);
			$formname = '/comment_form.php';
			break;
		case 'image.php':
			if (!getOption('comment_form_images')) return;
			$comments_open = OpenedForComments(IMAGE);
			$formname = '/comment_form.php';
			break;
		case 'pages.php':
			if (!getOption('comment_form_pages')) return;
			$comments_open = zenpageOpenedForComments();
			$formname = '/comment_form.php';
			break;
		case 'news.php':
			if (!getOption('comment_form_articles')) return;
			$comments_open = zenpageOpenedForComments();
			$formname = '/comment_form.php';
			break;
		default:
			return;
			break;
	}
	
	if (is_null($obj)) {
		$obj=getCurrentPageObject();
	}
	if (!is_null($obj)) {
		$pageid=getTinyURL($obj);

		if ($comments_open) {
			?>
			<div class="fb-like" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false"></div>
			<div class="fb-comments" data-href="<?php echo $pageid ?>" data-num-posts="5" data-width="470"></div>
			<?php
		}
	}
?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=375364665888214";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<?php
}
?>
