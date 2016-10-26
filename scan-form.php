<?php defined( 'ABSPATH' ) or exit; ?>
<div class="form">
	<p><?php _e( 'Enter url of any webpage of your website to scan (Just copy and paste url from browser address bar)', 'cgss' ); ?></p>
	<form action="<?php echo $cgss_scan_admin; ?>" method="post">
		<p><input type="text" name="cgss-url" class="regular-text code" />&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="Scan Now" name="submit-cgss-url" class="button button-primary" /></p>
    <fieldset>
     <strong>Advanced Options</strong>
     <p>
	    <input type="checkbox" name="cgss-robots-command" value="cgss-robots-command" />
	    <label for="cgss-robots-command">
	     &nbsp;<?php echo __( 'Include robots.txt analysis in the report.', 'cgss' ) . '<br />' . __( 'This will take around 10 seconds more. If you are sure there is no blocking of this url by robots.txt, then you may skip this checkbox.', 'cgss' ); ?>
	    </label>
     </p>
     <p>
      <input type="text" name="cgss-sitemap-input" class="regular-text code" />
      <label for="cgss-robots-command">
	     &nbsp;<?php echo __( 'Input sitemap.xml url for analysis.', 'cgss' ) . '<br />' . __( 'This will also take around 30 seconds  more. If you don\'t enter any url, no analysis on xml sitemap will take place.', 'cgss' ) . '<br />' . __( 'If you are sure that this page is included in xml sitemap, no need to recheck it.', 'cgss' ); ?>
	    </label>
     </p>
    </fieldset>
	</form>
	<br />
	<p><?php echo __( 'Time to show you results depends on your server and your website quality. Actual scanning process takes less than', 'cgss' ) . '<strong> 0.01 </strong>' . __( 'seconds to complete.', 'cgss' ); ?></p>
</div>
