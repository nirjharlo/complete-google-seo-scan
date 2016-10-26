<!--Scan Report Section-->
<div class="scan-report">
	<div class="theme-overlay">
		<div class="theme-overlay active">
			<div class="theme-backdrop"></div>
			<div class="theme-wrap">
				<div class="theme-header">
					<button class="stars-top disabled">
						<span id="ScoreStars"></span>
					</button>
					<button class="social-top disabled facebook">
						<span class="dashicons dashicons-facebook"></span> <span id="FbShare"></span>
					</button>
					<button class="social-top disabled gplus">
						<span class="dashicons dashicons-googleplus"></span> <span id="GplusCount"></span>
					</button>
					<button class="social-top disabled twitter">
						<span class="dashicons dashicons-twitter"></span> <span id="TweeetCount"></span>
					</button>
					<button id="ReportClose" class="close dashicons dashicons-no">
						<span class="screen-reader-text"></span>
					</button>
				</div>
				<div class="theme-about printable">
					<span class="theme-version">
						<span id="ScoreStarsAlt"></span> <?php echo $elem->dashicon( 'clock' ); ?> <span id="ScanTime"></span> <?php _e( 'sec', 'cgss' ); ?>
					</span>
					<div class="grid-container">
						<div class="report-details">
							<div class="row">
								<div class="clear"></div>
								<div id="wpseosnippet">
									<a id="SnipTitle" class="title" href="#"></a>
									<span id="SnipUrl" class="url"></span>
									<p class="desc">
										<span id="SnipContent" class="content"></span>
									</p>
								</div>
								<div class="clear"></div>
							</div>
							<?php $report_show = $elem->report_show();
							$accord_content = $accord->display( 'content', __( 'Text & Links', 'cgss' ), $elem->dashicon( 'text' ), $report_show['content'] );
							$accord_design = $accord->display( 'design', __( 'Design', 'cgss' ), $elem->dashicon( 'smartphone' ), $report_show['design'] );
							$accord_crawl = $accord->display( 'crawl', __( 'Crawl', 'cgss' ), $elem->dashicon( 'randomize' ), $report_show['crawl'] );
							$accord_speed = $accord->display( 'time', __( 'Speed', 'cgss' ), $elem->dashicon( 'clock' ), $report_show['time'] ); ?>
							<?php echo $elem->report( $accord_content ); ?>
							<?php echo $elem->report( $accord_design ); ?>
							<?php echo $elem->report( $accord_crawl ); ?>
							<?php echo $elem->report( $accord_speed ); ?>
							<?php echo $report_show['social-tags']; ?>
						</div>
						<div class="report-brief">
							<div class="row">
								<div class="col-1 hide-mobile"></div>
								<div class="col-4">
									<div class="clear"></div>
										<div class="aligncenter">
											<div id="NoActions">
												<?php echo $elem->dashicon( 'carrot success-icon' ); ?>
												<h3><?php _e( 'IT\'S ALL GOOD. NO ACTIONS NEEDED', 'cgss' ); ?></h3>
											</div>
											<div id="ActionsList">
												<?php echo $elem->dashicon( 'format-quote danger-icon' ); ?>
												<h3><?php _e( 'TAKE <span id="ActNum"></span> FOLLOWING ACTIONS', 'cgss' ); ?></h3>
											</div>
										</div>
										<?php echo $elem->action(); ?>
								</div>
							</div>
						</div>
						<div class="clear"></div>
					</div>
				</div>
				<div class="theme-actions">
					<div class="active-theme">
						<?php echo $btns->report(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
