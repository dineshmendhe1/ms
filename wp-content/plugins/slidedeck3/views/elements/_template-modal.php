<?php
/**
 * SlideDeck Tempalte Modal
 * 
 * More information on this project:
 * http://www.slidedeck.com/
 * 
 * Full Usage Documentation: http://www.slidedeck.com/usage-documentation 
 * 
 * @package SlideDeck
 * @subpackage SlideDeck 3 Pro for WordPress
 * @author Hummingbird Web Solutions Pvt. Ltd.
 */

/*
Copyright 2012 HBWSL  (email : support@hbwsl.com)

This file is part of SlideDeck.

SlideDeck is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

SlideDeck is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with SlideDeck.  If not, see <http://www.gnu.org/licenses/>.
*/
?>
<h3><?php _e( $title, $namespace ); ?></h3>

<form action="<?php echo admin_url( 'admin.php' ); ?>" method="GET">
   
    <?php if( $action != "slidedeck_add_templates" ): ?>
        <p><?php _e( "More Templates Coming Soon ...", $namespace ); ?></p>
    <?php endif; ?>
    
    <input type="hidden" name="page" value="<?php echo SLIDEDECK2_BASENAME; ?>" />
    <input type="hidden" name="action" value="<?php echo $action; ?>" />
        <input type="hidden" name="slidedeck_id" value="<?php echo $slidedeck_id; ?>" />
        
   <!--  foreach (glob(userpro_lite_path . 'admin/*.php') as $filename) { include $filename; } -->
       
   
   
   	<?php 
	
   	$available_lens=array();
    $templates=array();  
   $directories = glob(SLIDEDECK2_DIRNAME . '/template' . '/*' , GLOB_ONLYDIR);
	foreach ($directories as $directoryie)
	{
		array_push($templates,basename($directoryie));



	}


    $professional=glob(SLIDEDECK2_PROFESSIONAL_DIRNAME. '/lenses/*');

    $lens_files = glob( SLIDEDECK2_DIRNAME . '/lenses/*' );
    if( is_dir( SLIDEDECK2_CUSTOM_LENS_DIR ) ) {
    	if( is_readable( SLIDEDECK2_CUSTOM_LENS_DIR ) ) {
    		// Get additional uploaded custom Lenses
    		$custom_lens_files = (array) glob( SLIDEDECK2_CUSTOM_LENS_DIR . '/*' );
    		// Merge Lenses available and loop through to load
    		$lens_files = array_merge( $lens_files, $custom_lens_files,$professional );
    	}
    }else
{
    		$lens_files = array_merge( $lens_files,$professional );

}
        		
    foreach ($lens_files as $lens)
    {
      $available_lens[]=	basename($lens);
    	
    }
    
    
 
   	
   	?>
    <ul class="sources slidetemplate">
        <?php foreach (	$templates as $k=>$template): 
	$files=array('');
   	$dir = SLIDEDECK2_DIRNAME . "/template/$template";
   	$dh  = opendir($dir);
   	while (false !== ($filename = readdir($dh))) {
   		$files[] = $filename;
   	}
	$templatedir=preg_grep ('/\.jpg$/i', $files);
		$path=array_values($templatedir);
		$pathinfo = pathinfo($path[0]);
		$lensname=$pathinfo['filename'];
		

       		?>
            <?php if (!in_array($lensname, $available_lens)) 
            {
				continue;            	
            }
            	
            	?>
            <li class="template">
                <label>
                    
                        <img src="<?php echo SLIDEDECK2_URL .'template/'.$template.'/'.$lensname.'.jpg' ?>" width="121px" />
                          
                  
               <input type="radio" name="template" value="<?php echo $template; ?>" />
                 <div class="lensname">
                    <?php 
						$name=$template;
					
                    echo $name;?></div>
                </label>
           
           
               
                             
               
               
                   
            </li>
            
        <?php endforeach; ?>
    </ul>
  
    
    
    
</form>
