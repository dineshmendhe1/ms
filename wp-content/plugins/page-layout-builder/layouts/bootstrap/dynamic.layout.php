<?php
$cols = $cols ? $cols : 2;
$grid = (int)( 12 / $cols );
$rem = 12 % $cols;

for($i = 1; $i <= $cols;  $i++){
    if($i == $cols) $grid += $rem;
    if($gs[$id]) $grid = $gs[$id]['grid_'.$i];
?> 

        <div class="gridt col-md-<?php echo $grid; ?> minimax_column" id="column_<?php echo $i; ?>_<?php echo $id; ?>">

            <?php if(current_user_can('edit_posts') && get_option('minimax_frontend_editing',0) == 1): ?>
                <input class="mx-input" type="hidden" id="<?php echo $id; ?>_<?php echo $i; ?>" name="layout_grids[<?php echo $holder; ?>_rows][<?php echo $id; ?>][grid_<?php echo $i; ?>]" value="<?php echo $grid; ?>">
                <ul class="module" rel='column_<?php echo $i; ?>_<?php echo $id; ?>'>
            <?php endif; ?>

            <?php minimax_render_modules("column_{$i}_{$id}"); ?>

            <?php if( current_user_can('edit_posts') && get_option('minimax_frontend_editing',0) == 1 && ( is_page() || is_single() || isset($init) ) ) { ?>

            </ul>
            <div class="col-ctrl">
                <div class="pull-right">
                    <?php if( $cols > 1 ){ ?>
                    <a class="fbtn mwdth dtooltip btn btn-default btn-xs" title="Increase Width" href="#" rel="inc" id="inc_<?php echo $id; ?>_<?php echo $i; ?>" cols="<?php echo $cols; ?>">
                        <i class="fa fa-plus"></i>
                    </a>
                    <a class="fbtn mwdth dtooltip btn btn-default btn-xs" title="Decrease Width" href="#" rel="dsc" id="dsc_<?php echo $id; ?>_<?php echo $i; ?>" cols="<?php echo $cols; ?>">
                        <i class="fa fa-minus"></i>
                    </a>
                    <?php } ?>
                    <a class="fbtn btnAddMoudule dtooltip btn btn-default btn-xs" title="Add Module" href="#" rel="column_<?php echo $i; ?>_<?php echo $id; ?>">
                        <i class="fa fa-cube"></i>
                    </a>
                </div>
            </div>
            <?php } ?>

        </div>
<?php }