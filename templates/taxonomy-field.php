<?php
 //TODO: Check JSON echo.
 //phpcs:ignorefile 
?>
<div <?php echo $attributes; ?>>
	<?php acf_render_field( $field ); ?>
</div>
<div id="produ-sub-sections" style="display: flex; justify-content: space-around; flex-wrap: wrap;">
	<input type="hidden" name="produ-sub-categories" value='<?php echo $sub_categories; ?>'>
</div>
