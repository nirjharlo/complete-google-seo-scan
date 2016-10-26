<div class="wrap">

	<!--Page Display-->
	<h2><?php echo __( 'Scan Overview', 'cgss' ) . ' ' . $btns->overview_head(); ?></h2>

	<!--Fallbacks of Server & Design Seo-->
	<?php
	//get server results
	$server_data = new CGSS_GET_SERVER();
	$server = $server_data->fetch();

	//get design results
	$design_data = new CGSS_GET_DESIGN();
	$design = $design_data->fetch();

	if ( ! $server or ! $design ) :
	?>
		<div class="error notice is-dismissible">
			<p><?php _e( 'Before You Proceed, please complete scaning for <i>Server Seo Status</i> and <i>Design Seo Status</i> bellow. Then reload the page.', 'cgss' ); ?></p>
			<button type="button" class="notice-dismiss">
				<span class="screen-reader-text">Dismis</span>
			</button>
		</div>
	<?php endif; ?>

	<!--Navigation Bar-->
	<div class="wp-filter">
		<ul class="filter-links">
			<?php foreach ( $post_types as $type ) :
					$other_types_url = admin_url() . 'admin.php?page=seo-scan-' . $type[0]; ?>
						<li class="plugin-install-featured">
							<a <?php echo ( $type[0] != $params['type'] ? 'href="' . $other_types_url . '"' : 'class="current" href="#"' ) . '>' . $type[1] . '<sup class="post-type-counts">' . $type[2] . '</sup>'; ?></a>
						</li>
			<?php endforeach; ?>
		</ul>
		<?php if ( $server and $design ) : ?>
			<div class="search-form search-plugins">
				<a class="button archive-show-btn" href="#"><?php _e( 'ARCHIVES', 'cgss' ); ?></a>
			</div>
		<?php endif; ?>
	</div>

	<!--Archive Seo-->
	<div class="negative-margin archive-panel">
		<div class="wp-filter">
			<ul class="filter-links">
				<?php
				$cat_dpd = $dpd->categories();
				$tag_dpd = $dpd->tags();
				$archives = array( $cat_dpd, $tag_dpd );
				foreach ( $archives as $value ) :
					if ( $value ) : ?>
					<li class="plugin-install-featured">
						<div class="search-form search-plugins">
							<?php echo $value; ?>
						</div>
					</li>
				<?php endif; endforeach; ?>
			</ul>
			<div class="search-form search-plugins">
				<span class="danger-icon archive-msg"></span>
				<input class="archive-scan button" type="submit" value="<?php _e( 'SCAN ARCHIVE PAGE', 'cgss' ); ?>" />
				<?php echo $elem->loading( 'archive' ); ?>
			</div>
		</div>
	</div>

	<!--Content Intelligence-->
	<div id="welcome-panel" class="welcome-panel">
		<div class="welcome-panel-content">
			<h3><?php _e( 'Content Status', 'cgss' ); ?></h3>
			<p class="about-description"><?php _e( 'Fetch results from stored scan reports.', 'cgss' ); ?></p>
			<?php echo $elem->loading( 'intel' ); ?>
			<div class="row">
				<div class="col-2">
					<?php $get_report_ids = get_option( 'cgss_seo_option_ids' ); ?>
					<?php if ( $get_report_ids and count( $get_report_ids ) > 0 ) : ?>
						<a id="<?php echo implode( ',', $get_report_ids ); ?>" class="button button-primary button-hero cgss-intel"><?php _e( 'Fetch Intel', 'cgss' ); ?></a>
						<p class="hide-if-no-customize"><span id="NumReports">0</span> <?php echo __( 'of', 'cgss' ) . ' <span id="NumReportsFull">' . count( $get_report_ids ) . '</span> ' . __( 'reports processed.', 'cgss' ); ?></p>
					<?php else: ?>
						<p class="hide-if-no-customize"><?php _e( 'No pages are scaned. Use top navigation to go to list of pages.', 'cgss' ); ?></p>
					<?php endif; ?>
					<p class="intel-msg"></p>
					<div class="cgss-intel-score"></div>
					<div class="clear"></div>
					<div class="row">
						<span class="facebook-intel facebook">
							<span class="dashicons dashicons-facebook"></span> <span id="FbShareIntel"></span>
						</span>
						<span class="googleplus-intel gplus">
							<span class="dashicons dashicons-googleplus"></span> <span id="GplusCountIntel"></span>
						</span>
						<span class="twitter-intel twitter">
							<span class="dashicons dashicons-twitter"></span> <span id="TweeetCountIntel"></span>
						</span>
					</div>
					<div class="clear"></div>
				</div>
				<div class="col-2">
					<div class="intel-content">
						<?php echo $elem->intel_content(); ?>
					</div>
				</div>
				<div class="col-2">
					<div class="intel-extra">
						<?php echo $elem->intel_extra(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="clear"></div>

	<div class="negative-margin">
		<div class="row">
			<div class="col-3">
				<!--Server Seo-->
				<div class="wp-filter">
					<div class="filter-links">
						<h4>
							<?php _e( 'Server Seo Status', 'cgss' ); ?>
							<span class="danger-icon server-msg"></span>
						</h4>
					</div>
					<div class="search-form search-plugins">
						<input class="server-scan button" type="submit" value="<?php _e( 'CHECK NOW', 'cgss' ); ?>" />
						<?php echo $elem->loading( 'server' ); ?>
					</div>
				</div>
				<div class="server-seo-result">
					<?php echo $tables->server_seo(); ?>
				</div>
			</div>
			<div class="col-3">
				<!--Design Seo-->
				<div class="wp-filter">
					<div class="filter-links">
						<h4>
							<?php _e( 'Design Seo Status', 'cgss' ); ?>
							<span class="danger-icon design-msg"></span>
						</h4>
						<?php global $wp_styles; echo $queued->script_ul( 'queued-style', $wp_styles ); ?>
						<?php global $wp_scripts; echo $queued->script_ul( 'queued-script', $wp_scripts ); ?>
					</div>
					<div class="search-form search-plugins">
						<input class="design-scan button" type="submit" value="<?php _e( 'CHECK NOW', 'cgss' ); ?>" />
						<?php echo $elem->loading( 'design' ); ?>
					</div>
				</div>
				<div class="design-seo-result">
					<div id="dashboard-widgets" class="row">
						<?php echo $accord->display( 'req_num', 'Numbers of requests' . ': <span id="TotalDesignSeoNum"></span>', '<span id="DesReqNumIcon"></span>', $elem->design_number() ); ?>
						<?php echo $accord->display( 'size', 'Size of resources' . ': <span id="TotalDesignSeosize"></span> kb', '<span id="DesResSizeIcon"></span>', $elem->design_size() ); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--Display Report-->
	<?php require_once('report-display.php'); ?>
</div>
