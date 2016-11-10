echo "<div class='notice {$instance['ntype']}'>";
        if ( !empty( $title ) ) { echo "<strong>" . $title . "</strong><br />"; } ?>
        <?php echo $instance['notice']; ?>
        <?php echo "</div>";