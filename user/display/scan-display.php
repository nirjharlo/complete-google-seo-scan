<div class="wrap">

	<h2><?php echo __( 'Scan Status', 'cgss' ) . ' ' . $btns->head(); ?></h2>

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
			<p class="danger-icon"><?php _e( 'Before You Proceed, please complete scaning for <i>Server Seo Status</i> and <i>Design Seo Status</i>.', 'cgss' ); ?> <a href="<?php echo admin_url() . 'admin.php?page=seo-scan'; ?>"><?php echo _e( 'You will find them here.', 'cgss' ); ?></a></p>
		</div>
	<?php endif; ?>

	<!--Navigation Bar-->
	<div class="wp-filter">
		<ul class="filter-links">
			<?php foreach ( $post_types as $type ) : ?>
					<?php $other_types_url = admin_url() . 'admin.php?page=seo-scan-' . $type[0]; ?>
						<li class="plugin-install-featured">
							<a <?php echo ( $type[0] != $params['type'] ? 'href="' . $other_types_url . '"' : 'class="current" href="#"' ) . '>' . $type[1]; ?></a>
						</li>
			<?php endforeach; ?>
		</ul>
		<div class="search-form search-plugins">
			<?php echo $dpd->filter(); ?>
		</div>
	</div>

	<!--Scan Form Section-->
	<div class="tablenav top">
		<div class="alignleft actions bulkactions">
			<?php cgss_filter_notice(); ?>
		</div>
		<?php cgss_page_nav( $params['type'] ); ?>
	</div>
	<div id="the-list">
		<form id="scanlink">
			<?php $get_posts = get_posts( array( 'posts_per_page' => -1, 'post_type' => $params['type'], 'post_status' => 'publish' ) );

			//for nav
			$per_page = cgss_nav_init();
			$paged = $params['paged'];
			if ( ! $paged ) {
				$paged = 1;
			}
			$min_item_num = ( $paged - 1 ) * $per_page;
			$nav_item = 0;
			$max_item_num = $paged * $per_page;

			foreach ( $get_posts as $post ) :
				if ( $nav_item >= $min_item_num and $nav_item < $max_item_num ) :

					$post_id = $post->ID;

					//get data
					$data = new CGSS_GET_DB( 'cgss_scan_result', $post_id, $time_now );
					$score = $data->score();
					$shares = $data->share();
					$time_diff = $data->time_diff();
					$words = $data->words();
					$keyword = $data->keyword(); ?>

					<div id="<?php echo $score['score'] . '-' . $time_diff['since_sec']; ?>" class="plugin-card">
						<?php echo $elem->loading_alt( $post_id ); ?>
						<div class="plugin-card-top">
							<h4><a href="<?php echo get_permalink( $post_id ); ?>" target="_blank"><?php echo get_the_title( $post_id ) . ' ' . $elem->dashicon( 'external' ); ?></a></h4>
							<p>
								<?php if ( $words and $keyword and $words != '--' and $keyword != '--' ) : ?>
									<span class="scaned-now-<?php echo $post_id; ?>"><span class="words-no-got-<?php echo $post_id; ?>"><?php echo $words; ?></span><?php _e( 'words, focus is', 'cgss' ); ?>: <strong class="keywords-no-got-<?php echo $post_id; ?>"><?php echo $keyword; ?></strong></span>
								<?php else : ?>
									<span class="not-scaned-yet-<?php echo $post_id; ?>"><?php _e( 'Not Scanned Yet', 'cgss' ); ?></span>
								<?php endif; ?>
							</p>
							<p>
								<span class="cover-display-btn">
									<?php echo $elem->dashicon( 'admin-links' ); ?> <span class="links-no-got-<?php echo $post_id; ?>"><?php echo $data->link(); ?></span>
								</span>
								<?php echo $elem->gaps( 2 ); ?>
								<span class="cover-display-btn">
									<?php echo $elem->dashicon( 'images-alt2' ); ?> <span class="images-no-got-<?php echo $post_id; ?>"><?php echo $data->image(); ?></span>
								</span>
								<?php echo $elem->gaps( 2 ); ?>
								<span class="cover-display-btn">
									<?php echo $elem->dashicon( 'share' ); ?> <span class="shares-no-got-<?php echo $post_id; ?>"><?php echo $shares['num']; ?></span>
								</span>
								<?php echo $elem->gaps( 2 ); ?>
								<span class="cover-display-btn">
									<?php echo $elem->dashicon( 'clock' ); ?> <span class="time-no-got-<?php echo $post_id; ?>"><?php echo $data->time(); ?></span>
								</span>
							</p>
						</div>
						<div class="plugin-card-bottom">
							<a id="<?php echo get_permalink( $post_id ); ?>" class="scan-now button button-small" href="#<?php echo $post_id; ?>"><?php _e( 'SCAN', 'cgss' ); ?></a>
								<a id="<?php echo get_permalink( $post_id ); ?>" name="<?php echo $post_id; ?>" class="compete-now button button-small" href="#<?php echo $post_id; ?>"><?php _e( 'COMPETE', 'cgss' ); ?></a>
							<div class="column-updated">
								<!--Error Messages-->
								<a href="#" id="ViewAgain-<?php echo $post_id; ?>" class="view-again">
									<?php echo $elem->dashicon( 'editor-expand' ); ?>
								</a>
								<span class="scan-failed danger-icon" id="ScanFailed-<?php echo $post_id; ?>">
									<?php _e( 'FAILED', 'cgss' ); ?>
								</span>
								<span class="show-msg danger-icon" id="ShowMessage-<?php echo $post_id; ?>"></span>
								<span class="score-in-form">
									<span id="score-<?php echo $post_id; ?>">
										<?php echo ( $score['stars'] != null ? $score['stars'] : $elem->dashicon( 'heart danger-icon' ) ); ?>
									</span>
								</span>
								<?php echo $elem->gaps( 1 ); ?>
								<span class="time-in-form">
									<span id="time-<?php echo $post_id; ?>">
										<?php echo $elem->dashicon( 'clock' ) . ' ' . ( $time_diff['since'] != null ? $time_diff['since'] : __( 'NEVER', 'cgss' ) ); ?>
									</span>
								</span>
								<span id="<?php echo ( $score['marks'] != null ? $score['marks'] : '' ); ?>" class="exact-no-got-<?php echo $post_id; ?> hide"></span>
							</div>
						</div>
					</div>
				<?php
				//for nav
				endif;
				$nav_item += 1;
			 endforeach;
			?>
		</form>
	</div>
	<div class="tablenav top">
		<div class="alignleft actions bulkactions">
			<?php cgss_filter_notice(); ?>
		</div>
		<?php cgss_page_nav( $params['type'] ); ?>
	</div>

	<!--Display Report-->
	<?php require_once('report-display.php'); ?>

	<!--Display Competative intel-->
	<?php require_once('compete-display.php'); ?>
</div>
