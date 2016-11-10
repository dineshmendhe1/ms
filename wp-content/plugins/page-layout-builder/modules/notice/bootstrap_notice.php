<?php
$close_button =  $instance[ 'close_button' ] ;
$ntype = $instance['ntype'];
if($ntype=="error")$ntype="danger";
echo "<div class='alert alert-{$ntype}'>";
if($close_button==1)echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
if ( !empty( $title ) ) { echo "<strong>" . $title . "</strong><br />"; } 
echo $instance['notice'];
if($instance["button_label"]!=''){
echo '<p><a class="btn btn-'.$ntype.'" href="'.$instance['button_url'].'">'.$instance["button_label"].'</a></p>';
} 
echo "</div>";
?>