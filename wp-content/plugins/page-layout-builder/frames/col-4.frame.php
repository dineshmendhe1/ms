 
	<div class="grid_3">
        <input type="hidden" id="<?php echo $id; ?>_1" name="layout_grids[<?php echo $holder; ?>_rows][<?php echo $id; ?>][grid_1]" value="3">

        <div class="column" id="column_1_<?php echo $id; ?>">
    <ul class="module"  rel="column_1_<?php echo $id; ?>">
            <?php minimax_render_module_frames("column_1_{$id}"); ?>	 
            </ul>
        	<a class="btnAddMoudule" rel="column_1_<?php echo $id; ?>" href="#">Add Module</a>
    </div>
    </div>
    
 
	<div class="grid_3">
        <input type="hidden" id="<?php echo $id; ?>_2" name="layout_grids[<?php echo $holder; ?>_rows][<?php echo $id; ?>][grid_2]" value="3">

        <div class="column" id="column_2_<?php echo $id; ?>">
    <ul class="module"  rel="column_2_<?php echo $id; ?>">
            <?php minimax_render_module_frames("column_2_{$id}"); ?> 
            </ul>
        	<a class="btnAddMoudule" rel="column_2_<?php echo $id; ?>" href="#">Add Module</a>
    </div>
    
    </div>
	 
	<div class="grid_3">
        <input type="hidden" id="<?php echo $id; ?>_3" name="layout_grids[<?php echo $holder; ?>_rows][<?php echo $id; ?>][grid_3]" value="3">

        <div class="column" id="column_3_<?php echo $id; ?>">
    <ul class="module"  rel="column_3_<?php echo $id; ?>">
            <?php minimax_render_module_frames("column_3_{$id}"); ?>     
            </ul>
            <a class="btnAddMoudule" rel="column_3_<?php echo $id; ?>" href="#">Add Module</a>
    </div>
    
    </div>
    
     <div class="grid_3">
         <input type="hidden" id="<?php echo $id; ?>_4" name="layout_grids[<?php echo $holder; ?>_rows][<?php echo $id; ?>][grid_4]" value="3">

         <div class="column" id="column_4_<?php echo $id; ?>">
            <ul class="module"  rel="column_4_<?php echo $id; ?>">
            <?php minimax_render_module_frames("column_4_{$id}"); ?>	 
            </ul>
        	<a class="btnAddMoudule" rel="column_4_<?php echo $id; ?>" href="#">Add Module</a>
    </div>
    
    </div>
	 