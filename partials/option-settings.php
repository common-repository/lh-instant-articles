<h1><?php echo esc_html(get_admin_page_title()); ?></h1>

<p><?php _e("Your Instant Articles Feed is located here: ", $this->namespace ); ?><a href="<?php echo get_feed_link("lh-instant-articles"); ?>"><?php echo get_feed_link("lh-instant-articles"); ?></a></p>
<p><?php _e("Note if the feed is 404 not found then try regenerating your permalinks, in you Dashboard under Settings->Permalinks.", $this->namespace ); ?></p>

<form name="form1" method="post" action="<?php echo add_query_arg( $this->namespace.'-buster', time(), $this->curpageurl());  ?>">
<?php wp_nonce_field( $this->namespace."-backend_nonce", $this->namespace."-backend_nonce", false ); ?>
<table class="form-table">
<tr valign="top">
<th scope="row"><label for="<?php echo $this->fb_pages_field_name; ?>"><?php _e("Facebook Page ID(s):", $this->namespace ); ?></label></th>
<td><?php

        printf(
            '<input type="text" id="'.$this->fb_pages_field_name.'" name="'.$this->fb_pages_field_name.'" value="%s" />',
            isset( $this->options[$this->fb_pages_field_name] ) ? esc_attr( $this->options[$this->fb_pages_field_name]) : ''
        );

?></td>
</tr>
<tr valign="top">
<th scope="row"><label for="<?php echo $this->approved_types_field_name; ?>"><?php _e("Approved Post Type(s):", $this->namespace ); ?></label></th>
<td>
<p>
<legend><?php _e("What post types can be included in Instant Articles Feed?", $this->namespace ); ?></legend>
<select multiple="multiple" name="<?php echo $this->approved_types_field_name; ?>[]" id="<?php echo $this->approved_types_field_name; ?>">

<?php foreach ( get_post_types( array('public'   => true ), 'names' ) as $posttype ) { ?>

<option name="<?php echo $this->approved_types_field_name; ?>[]" value="<?php echo $posttype; ?>" <?php if (isset($this->options[$this->approved_types_field_name]) and is_array($this->options[$this->approved_types_field_name])  and in_array($posttype, $this->options[$this->approved_types_field_name])) { echo 'selected="selected"'; } ?> /><?php echo $posttype; ?></option>



<?php } ?>


?>
</select>
</p>
</td>
</tr>
<tr valign="top">
<th scope="row"><label for="<?php echo $this->tracking_code_field_name; ?>"><?php _e("Tracking Code", $this->namespace ); ?></label></th>
<td>
<?php

echo '<p>please note you need to include opening and closing iframe tags in this input</p>';
  
echo '<textarea id="'.$this->tracking_code_field_name.'" name="'.$this->tracking_code_field_name.'" rows="10" cols="50">';
  
  if (isset($this->options[$this->tracking_code_field_name])){  echo $this->options[$this->tracking_code_field_name]; }
	
	
echo '</textarea>';

?>
</td>
</tr>
<tr valign="top">
<th scope="row"><label for="<?php echo $this->advertisement_code_field_name; ?>"><?php _e("Advertisement Code", $this->namespace ); ?></label></th>
<td>
<p><?php _e("This functionality uses ", $this->namespace ); ?><a href="https://developers.facebook.com/docs/instant-articles/monetization/ad-placement"><?php _e("Automatic Placement", $this->namespace ); ?></a><?php _e(", as such please past the full figure tags, without the enclosing section tag.", $this->namespace ); ?></p>

<?php


   printf(
            '<textarea id="'.$this->advertisement_code_field_name.'" name="'.$this->advertisement_code_field_name.'" rows="10" cols="50">%s</textarea>',
            isset( $this->options[$this->advertisement_code_field_name]) ? esc_attr( $this->options[$this->advertisement_code_field_name]) : ''
        );

?>
</td>
</tr>
<tr valign="top">
<th scope="row"><label for="<?php echo $this->footer_message_field_name; ?>"><?php _e("Footer Message", $this->namespace ); ?></label></th>
<td>
<?php

if (isset($this->options[$this->footer_message_field_name])){

$content = $this->options[$this->footer_message_field_name];

} else {

$content = "";

}
$settings = array( 'media_buttons' => false, 'textarea_rows' => 8 );

?>
<p>
<?php wp_editor( $content, $this->footer_message_field_name, $settings); ?>
</p>
</td>
</tr>
</table>

<?php submit_button( 'Save Changes' ); ?>

</form>