<?php
$cols = $cols ? $cols : 2;
$grid = (int)( 12 / $cols );
$rem = 12 % $cols;

for( $i = 1; $i <= $cols; $i++ ){

    if( $i == $cols ) $grid += $rem;
    if( isset($gs) && isset($gs[$id])) $grid = $gs[$id]['grid_'.$i];
    ?>

	<div id="grid_<?php echo $i; ?>_<?php echo $id; ?>" class="gridt grid_<?php echo $grid; ?>">
        <input type="hidden" id="<?php echo $id; ?>_<?php echo $i; ?>" name="layout_grids[<?php echo $holder; ?>_rows][<?php echo $id; ?>][grid_<?php echo $i; ?>]" value="<?php echo $grid; ?>">
    	<div class="column" id="column_<?php echo $i; ?>_<?php echo $id; ?>">
            <ul class="module" rel='column_<?php echo $i; ?>_<?php echo $id; ?>'>
                <?php minimax_render_module_frames("column_{$i}_{$id}"); ?>
            </ul>
            <a class="fbtn" rel="column_<?php echo $i; ?>_<?php echo $id; ?>" href="#" onclick="return false">&nbsp;</a>
            <?php if($cols > 1): ?>
                <a class="fbtn mwdth tooltip inc-width" title="Increase Width" href="#" rel="inc" id="inc_<?php echo $id; ?>_<?php echo $i; ?>" cols="<?php echo $cols; ?>">
                    <img src="<?php echo plugins_url('page-layout-builder/images/plus.png'); ?>"/>
<!--                    <span class="minimax-icons minimax-square-plus"></span>-->
                </a>
                <a class="fbtn mwdth tooltip dec-width" title="Decrease Width" href="#" rel="dsc" id="dsc_<?php echo $id; ?>_<?php echo $i; ?>" cols="<?php echo $cols; ?>">
                    <img src="<?php echo plugins_url('page-layout-builder/images/minus.png'); ?>"/>
                </a>
            <?php endif; ?>
            <a class="fbtn btnAddMoudule tooltip" title="Add Module" href="#" rel="column_<?php echo $i; ?>_<?php echo $id; ?>">
                <img src="<?php echo plugins_url('page-layout-builder/images/box_add.png'); ?>"/>
            </a>
        </div>
     </div>
<?php } ?>