 
 
    
	<div class="grid_4">
        <input type="hidden" id="<?php echo $id; ?>_1" name="layout_grids[<?php echo $holder; ?>_rows][<?php echo $id; ?>][grid_1]" value="4">
    <div class="column" id="column_1_<?php echo $id; ?>">
    <ul class="module" rel="column_1_<?php echo $id; ?>">
            <?php minimax_render_module_frames("column_1_{$id}"); ?> 
            </ul>
        	<a class="btnAddMoudule" href="#" rel="column_1_<?php echo $id; ?>">Add Module</a>
    </div>
    
    </div>
	 
	<div class="grid_8">
        <input type="hidden" id="<?php echo $id; ?>_2" name="layout_grids[<?php echo $holder; ?>_rows][<?php echo $id; ?>][grid_2]" value="8">
    <div class="column" id="column_2_<?php echo $id; ?>">
    <ul class="module" rel="column_2_<?php echo $id; ?>">
            <?php minimax_render_module_frames("column_2_{$id}"); ?>	 
            </ul>
    
        	<a class="btnAddMoudule" rel="column_2_<?php echo $id; ?>" href="#">Add Module</a>
    </div>
    
    </div>
 
 
