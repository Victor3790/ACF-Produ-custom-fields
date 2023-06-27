<?php
/**
 * Template to echo the taxonomy field.
 *
 * @package WordPress.
 */

?>
<div <?php echo esc_attr( $attributes ); ?>>
	<?php acf_render_field( $field ); ?>
</div>
<div id="produ-sub-sections" style="display: flex; justify-content: space-around; flex-wrap: wrap;">
	<input type="hidden" name="produ-sub-categories" value='<?php echo wp_kses( $sub_categories, 'post' ); ?>'>
</div>
