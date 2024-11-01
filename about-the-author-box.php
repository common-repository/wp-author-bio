<?php
/*
Plugin Name: About the Author Box
Plugin URI: http://devilsbackyard.com/post/wordpress-about-the-author-box-plugin/
Version: 1.0.7
Description: Displays a sleek "about the author" box in the single posts page. This is very useful for team blog having too many writers.
Author: Kushagra Agarwal
Author URI: http://www.devilsbackyard.com/
Donate Uri: http://www.devilsbackyard.com/donate
*/


                wp_enqueue_script('devlounge_plugin_series', get_bloginfo('wpurl') . '/wp-content/plugins/wp-author-bio/jscolor.js',  '0.1');
            

function atab_box()
{
?>
<small style="visibility:hidden; font-size:1px;">Line Break</small><div class="atab_box">
<p><b>Author: <?php the_author_link(); ?>
</b> <small>(<?php the_author_posts(); ?> Articles)</small></p>
<?php echo get_avatar( get_the_author_email(), '50' ); ?>
<p><?php the_author_description(); ?></p>
<noscript><a href="http://yourherbalcare.com">Your Herbal Care</a></noscript>
</div>


<?php
}

function style() {
$background = get_option('back_color');
$width = get_option('box_width');
$height = get_option('box_height');
$img_background = get_option('img_background');
		echo "
		<style>
		<!--
			.avatar{
				float:left;
    background-color: #$img_background;
    border:1px solid #ccc;
    padding: 4px;
    margin: 0 7px 2px 5px;
    display: inline;

			}
.atab_box{
				color: #666;
font-weight: normal;
background: #$background;
border: 1px solid #ccc;
padding: 12px;
width: $width;
margin-bottom:8px;
margin-top:8px;
}

		-->
		</style>
		";
	}
function set_color_options(){
add_option('box_width','500px','Width of the Box');
add_option('box_height','','Height of the Box');
add_option('back_color','#fff','Background Color');
add_option('img_background','#fff','Backgound color of image');
}

function unset_color_options(){
delete_option('box_height');
delete_option('box_width');
delete_option('back_color');
delete_option('img_background');
}

register_activation_hook(__File__,'set_color_options');
register_deactivation_hook(__File__,'unset_color_options');

function admin_color_options(){
?><div class="wrap"><h2>About the author box Options</h2>
<?php

if ($_REQUEST['submit']) {
 update_color_options();
}
print_color_form();
?></div><?php
}

function update_color_options(){
$ok = false;
if ($_REQUEST['box_height']){
update_option('box_height',$_REQUEST['box_height']);
$ok = true;
}
if ($_REQUEST['box_width']){
update_option('box_width',$_REQUEST['box_width']);
$ok = true;
}
if ($_REQUEST['back_color']){
update_option('back_color',$_REQUEST['back_color']);
$ok = true;
}
if ($_REQUEST['img_background']){
update_option('img_background',$_REQUEST['img_background']);
$ok = true;
}
if($ok) {
?><div id="message" class="updated fade">
<p>Options Saved.</p>
</div><?php
}
else{
?><div id="message" class="error fade">
<p>Failed to save options..</p>
</div><?php
}
}

function print_color_form(){
$default_background= get_option('back_color');
$default_height= get_option('box_height');
$default_width= get_option('box_width');
$default_img_background= get_option('img_background');
?>
<form method="post">
<h3>Background</h3>
<p>Select the color of the box from below. You can choose any color you want for the box.</p>
<label for="back_color">Colour:<input class="color" type="text" name="back_color" value="<?php echo $default_background; ?>" /> </label>
<br />
<hr />
<h3>Background Colour of Image</h3>
<p>This color will be the background of the image. You can select that from below:</p>
<label for="img_background">Background Colour of Image:<input type="text" class="color" name="img_background" value="<?php echo $default_img_background; ?>" /> </label>
<br />
<hr />
<h3>Dimensions of the Box</h3>
<p>You must set the dimensions of the box manually to fit the box with your theme by changing the width(<b>in pixels</b>). You can do it from below:
<p><label for="box_width">Width:<input type="text" name="box_width" value="<?php echo $default_width; ?>" /> </label> e.g. <b>400px, 500px</b></p>
<p>If you want the width to automatically adjust to your theme, enter <b>"none"</b> above.</p>
<p>Don't worry about the height, it will be adjusted automatically according to the length of the bio.</p>
<hr/>
<h3>Position of the box</h3>
You can also change the position of the box easily but you need to edit the plugin file for that. 
<br>Follow these steps to change the position:<p>
<br>1.Go to your Plugin editor and open '<b>about-the-author-box.php</b>' to edit.
<br>2.Now scroll to the bottom until you find '<b>the_content</b>'. (It should be the last line)
<br>3.Now changing 'the_content' to '<b>comments_template</b>' will make the box appear at bottom of the post.</p>
<p>
<input type="submit" name="submit" value="Submit" />
</p>
</form>

<p>For more information, visit <a href="http://devilsbackyard.com">Devils Backyard</a></p>

<?php
}

function modify_menu(){
add_options_page(  'About the author box options', 'About the author box', 'manage_options',__File__,'admin_color_options');

}


add_action('admin_menu','modify_menu');



function author_box_ContentFilter($content)
{
if (is_single())
return $content.atab_box().style();
else
return $content;

}

add_filter('the_content', 'author_box_ContentFilter');

?>