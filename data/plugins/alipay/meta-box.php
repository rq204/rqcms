<?php
add_action( 'admin_menu', 'ali_create_meta_box' );
add_action( 'save_post', 'ali_save_meta_data' );
function ali_create_meta_box() {
	add_meta_box( 'ali-post-meta-boxes','商品信息', 'ali_post_meta_boxes', 'post', 'normal', 'high' );
	add_meta_box( 'ali-page-meta-boxes', '商品信息', 'ali_page_meta_boxes', 'page', 'normal', 'high' );
}
function ali_post_boxes() {
	$meta_boxes = array(
	array(
    "name"             => "ali_display",
    "title"            => "开启支付",
    "desc"             => "启用支付功能",
    "type"             => "checkbox",
    "capability"       => "manage_options"
    ),
    array(
    "name"             => "ali_name",
    "title"            => "商品名称",
    "desc"             => "销售商品的名称",
    "type"             =>    "text",
    "capability"       => "manage_options"
    ),
    array(
    "name"             => "ali_price",
    "title"            => "商品价格",
    "desc"             => "销售商品的价格",
    "type"             => "text",
    "capability"       => "manage_options"
    ),
    array(
    "name"             => "ali_dl",
    "title"            => "下载链接",
    "desc"             => "销售商品的下载链接",
    "type"             => "text",
    "capability"       => "manage_options"
    ),
    array(
    "name"             => "ali_description",
    "title"            => "商品介绍",
    "desc"             => "销售商品的介绍",
    "type"             => "textarea",
    "capability"       => "manage_options"
    )
	);
	return apply_filters( 'ali_post_boxes', $meta_boxes );
}
function ali_page_boxes() {
	$meta_boxes = array(
	array(
    "name"             => "ali_display",
    "title"            => "开启支付",
    "desc"             => "启用支付功能",
    "type"             => "checkbox",
    "capability"       => "manage_options"
    ),
    array(
    "name"             => "ali_name",
    "title"            => "商品名称",
    "desc"             => "销售商品的名称",
    "type"             =>    "text",
    "capability"       => "manage_options"
    ),
    array(
    "name"             => "ali_price",
    "title"            => "商品价格",
    "desc"             => "销售商品的价格",
    "type"             => "text",
    "capability"       => "manage_options"
    ),
    array(
    "name"             => "ali_dl",
    "title"            => "下载链接",
    "desc"             => "销售商品的下载链接",
    "type"             => "text",
    "capability"       => "manage_options"
    ),
    array(
    "name"             => "ali_description",
    "title"            => "商品介绍",
    "desc"             => "销售商品的介绍",
    "type"             => "textarea",
    "capability"       => "manage_options"
    )
	);
	return apply_filters( 'ali_page_boxes', $meta_boxes );
}
function ali_post_meta_boxes() {
	global $post;
	$meta_boxes = ali_post_boxes(); 
?>
	<table class="form-table">
	<?php foreach ( $meta_boxes as $meta ) :
		$value = get_post_meta( $post->ID, $meta['name'], true );
		if ( $meta['type'] == 'text' )
			ali_meta_text_input( $meta, $value );
		elseif ( $meta['type'] == 'textarea' )
			ali_meta_textarea( $meta, $value );
		elseif ( $meta['type'] == 'select' )
			ali_meta_select( $meta, $value );
		elseif ( $meta['type'] == 'checkbox' )
			ali_meta_checkbox( $meta, $value );
	endforeach; ?>
	</table>
<?php
}
function ali_page_meta_boxes() {
	global $post;
	$meta_boxes = ali_page_boxes(); 
?>
	<table class="form-table">
	<?php foreach ( $meta_boxes as $meta ) :
		$value = stripslashes( get_post_meta( $post->ID, $meta['name'], true ) );
		if ( $meta['type'] == 'text' )
			ali_meta_text_input( $meta, $value );
		elseif ( $meta['type'] == 'textarea' )
			ali_meta_textarea( $meta, $value );
		elseif ( $meta['type'] == 'select' )
			ali_meta_select( $meta, $value );
		elseif ( $meta['type'] == 'checkbox' )
			ali_meta_checkbox( $meta, $value );
		endforeach; ?>
	</table>
<?php
}
function ali_meta_text_input( $args = array(), $value = false ) {
	extract( $args ); ?>
	<tr>
		<th style="width:10%;">
			<label for="<?php echo $name; ?>"><?php echo $title; ?></label>
		</th>
		<td>
			<input type="text" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo wp_specialchars( $value, 1 ); ?>" size="30" tabindex="30" style="width: 97%;" />
			<input type="hidden" name="<?php echo $name; ?>_noncename" id="<?php echo $name; ?>_noncename" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
			<br />
			<p class="description"><?php echo $desc; ?></p>
		</td>
	</tr>
	<?php
}
function ali_meta_select( $args = array(), $value = false ) {
	extract( $args ); ?>
	<tr>
		<th style="width:10%;">
			<label for="<?php echo $name; ?>"><?php echo $title; ?></label>
		</th>
		<td>
			<select name="<?php echo $name; ?>" id="<?php echo $name; ?>">
			<?php foreach ( $options as $option ) : ?>
				<option <?php if ( htmlentities( $value, ENT_QUOTES ) == $option ) echo ' selected="selected"'; ?>>
					<?php echo $option; ?>
				</option>
			<?php endforeach; ?>
			</select>
			<input type="hidden" name="<?php echo $name; ?>_noncename" id="<?php echo $name; ?>_noncename" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
		</td>
	</tr>
	<?php
}
function ali_meta_textarea( $args = array(), $value = false ) {
	extract( $args ); ?>
	<tr>
		<th style="width:10%;">
			<label for="<?php echo $name; ?>"><?php echo $title; ?></label>
		</th>
		<td>
			<textarea name="<?php echo $name; ?>" id="<?php echo $name; ?>" cols="60" rows="4" tabindex="30" style="width: 97%;"><?php echo wp_specialchars( $value, 1 ); ?></textarea>
			<input type="hidden" name="<?php echo $name; ?>_noncename" id="<?php echo $name; ?>_noncename" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
    <br />
			<p class="description"><?php echo $desc; ?></p>		</td>
	</tr>
	<?php
}
function ali_meta_checkbox( $args = array(), $value = false ) {
	extract( $args ); ?>
<tr>
		<th style="width:10%;">
	<label for="<?php echo $name; ?>"><?php echo $title; ?></label>		</th>
		<td>
    <input type="checkbox" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="yes"
    <?php if ( htmlentities( $value, 1 ) == 'yes' ) echo ' checked="checked"'; ?>
    style="width: auto;" />
    <input type="hidden" name="<?php echo $name; ?>_noncename" id="<?php echo $name; ?>_noncename" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
    <span class="description"><?php echo $desc; ?></span>
    </td>
	</tr>
	<?php }
function ali_save_meta_data( $post_id ) {
	if ( 'page' == $_POST['post_type'] )
		$meta_boxes = array_merge( ali_page_boxes() );
	else
		$meta_boxes = array_merge( ali_post_boxes() );
		foreach ( $meta_boxes as $meta_box ) :
		if ( !wp_verify_nonce( $_POST[$meta_box['name'] . '_noncename'], plugin_basename( __FILE__ ) ) )
			return $post_id;
		if ( 'page' == $_POST['post_type'] && !current_user_can( 'edit_page', $post_id ) )
			return $post_id;
		elseif ( 'post' == $_POST['post_type'] && !current_user_can( 'edit_post', $post_id ) )
			return $post_id;
		$data = stripslashes( $_POST[$meta_box['name']] );
		if ( get_post_meta( $post_id, $meta_box['name'] ) == '' )
			add_post_meta( $post_id, $meta_box['name'], $data, true );
		elseif ( $data != get_post_meta( $post_id, $meta_box['name'], true ) )
			update_post_meta( $post_id, $meta_box['name'], $data );
		elseif ( $data == '' )
			delete_post_meta( $post_id, $meta_box['name'], get_post_meta( $post_id, $meta_box['name'], true ) );
	endforeach;
}
?>