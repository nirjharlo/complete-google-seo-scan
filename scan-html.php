<fieldset class="asadharon">
 <table>
  <tbody>
   <?php foreach ( $cgss_scaned_page_props as $cgss_scaned_page_prop ) : ?>
    <tr>
     <td class="inside-po"><?php echo $cgss_scaned_page_prop[0]; ?></td>
     <td class="inside-po">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $cgss_scaned_page_prop[1]; ?></td>
    </tr>
   <?php endforeach; ?>
  </tbody>
 </table>
</fieldset>
<br />
<div class="control-section accordion-section">
 <h3 class="accordion-section-title hndle-one"><?php _e( 'Content Scan Results', 'cgss' ); ?></h3>
 <div class="accordion-section-content body-one">
  <table class="widefat">
   <thead>
    <tr>
     <th><strong><?php _e( 'Name', 'cgss' ); ?></strong></th>
     <th><strong><?php _e( 'Description', 'cgss' ); ?></strong></th>
     <th><strong><?php _e( 'Result', 'cgss' ); ?></strong></th>
     <th><strong><?php _e( 'Action', 'cgss' ); ?></strong></th>
    </tr>
   </thead>
   <tbody>
   <?php foreach ( $cgss_content_results as $cgss_content_result ) : ?>
    <tr>
     <td class="inside"><?php echo $cgss_content_result[0]; ?></td>
     <td class="inside"><?php echo $cgss_content_result[1]; ?></td>
     <td class="inside"><?php echo $cgss_content_result[2]; ?></td>
     <td class="inside"><?php echo $cgss_content_result[3]; ?></td>
    </tr>
   <?php endforeach; ?>
   </tbody>
  </table>
  <div id="pagecontentBODY"></div>
  <br />
  <br />
  <h3 class="accordion-section-title hndle-content"><?php _e( 'Content crawled from this webpage', 'cgss' ); ?></h3>
  <div class="accordion-section-content body-content">
   <fieldset>
    <div class="inside">
     <small><?php echo $cgss_only_text; ?></small>
    </div>
   </fieldset>
  </div>
 </div>
</div>
<br />
<div class="control-section accordion-section">
 <h3 class="accordion-section-title hndle-two"><?php _e( 'Technical Scan Results', 'cgss' ); ?></h3>
 <div class="accordion-section-content body-two">
  <table class="widefat">
   <thead>
    <tr>
     <th><strong><?php _e( 'Name', 'cgss' ); ?></strong></th>
     <th><strong><?php _e( 'Description', 'cgss' ); ?></strong></th>
     <th><strong><?php _e( 'Result', 'cgss' ); ?></strong></th>
     <th><strong><?php _e( 'Action', 'cgss' ); ?></strong></th>
    </tr>
   </thead>
   <tbody>
    <?php foreach ( $cgss_tech_results as $cgss_tech_result ) : ?>
     <tr>
      <td class="inside"><?php echo $cgss_tech_result[0]; ?></td>
	    <td class="inside"><p><?php echo $cgss_tech_result[1]; ?></td>
      <td class="inside"><?php echo $cgss_tech_result[2]; ?></td>
      <td class="inside"><?php echo $cgss_tech_result[3]; ?></td>
     </tr>
    <?php endforeach; ?>
   </tbody>
  </table>
 </div>
 <br />
 <div class="success-call">
  <div class="center" id="directbodySELL">
   <p><strong><?php echo __( 'More Traffic', 'cgss' ) . '&nbsp;&rarr;&nbsp;' . __( 'Improved SEO', 'cgss' ) . '&nbsp;&rarr;&nbsp;' . __( 'Better User experience', 'cgss' ) . '&nbsp;&rarr;&nbsp;<a href="http://gogretel.com/more-traffic/">' . __( 'See How ?', 'cgss' ) . '</a>' . '&nbsp;&larr;&nbsp;' . __( 'The easy way', 'cgss' ); ?></strong></p>
  </div>
 </div>
 <br />
</div>
