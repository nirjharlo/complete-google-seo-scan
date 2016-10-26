<!--Scan Report Section-->
<div class="scan-compete">
	<div class="theme-overlay">
		<div class="theme-overlay active">
			<div class="theme-backdrop"></div>
				<div class="theme-wrap">
					<div class="theme-header">
						<button id="CompeteForm" class="left dashicons dashicons-no disabled">
							<span class="screen-reader-text"><?php _e( 'Show previous theme', 'cgss' ); ?></span>
						</button>
						<button id="CompeteResult" class="right dashicons dashicons-no disabled">
							<span class="screen-reader-text"><?php _e( 'Show previous theme', 'cgss' ); ?></span>
						</button>
						<button id="CompeteClose" class="close dashicons dashicons-no">
							<span class="screen-reader-text"></span>
						</button>
					</div>
					<div class="theme-about printable">
						<div class="grid-container">
							<?php if ( ! $xtend_install ) : ?>
							<h3 class="theme-name"><?php _e( 'Competative Intelligence for On-page Optimization <strong>Demo</strong>', 'cgss' ); ?></h3>
							<span class="theme-version">
								<p><span class="success-icon"><?php echo $elem->dashicon( 'welcome-learn-more' ); ?></span> <?php _e( 'This is an an extension of Complete Google Seo Scan Plugin for WordPress', 'cgss' ); ?>.<br />
								<a href="http://gogretel.com/checkout-2?edd_action=straight_to_gateway&download_id=570" target="_blank"><?php _e( 'Get it for', 'cgss' ); ?> $64</a>, <?php _e( 'install and activate this extension as a seperate plugin. Then you can use this feature here.', 'cgss' ); ?></p>
							</span>
							<?php endif; ?>
							<div class="compete-form-container">
								<div class="row">
									<div class="col-4">
										<form class="compete-form" id="CompeteForm">
											<p class="theme-description"><?php echo $elem->dashicon('editor-spellcheck'); ?> <a href="#" class="focus-key-help" for="focus-keyword"><?php _e( 'Target Keyword', 'cgss' ); ?></a></p>
											<div class="focus-key-help-msg">
												<p class="theme-description"><i><?php _e( 'In real life seo, people target a specific long tail keyword for each webpage. So, put your long tail targeted Keyword here, say for example: red hot pizza', 'cgss' ); ?></i></p>
											</div><br />
											<input id="" class="regular-text compete-focus-keyword" name="focus-keyword" type="text" placeholder="Enter focus keyword here" />
											<br /><br /><hr />
											<p class="theme-description"><?php echo $elem->dashicon('admin-links'); ?> <a href="#" class="compete-url-help"><?php _e( 'Competitor Urls', 'cgss' ); ?></a></p>
											<div class="compete-url-help-msg">
												<p class="theme-description"><i><?php _e( 'Search in Google for pages with higher ranks than your webpage for that focus keyword. Then enter those webpage urls one by one. Upto 100 competitors per analysis are allowed.', 'cgss' ); ?><br /><?php _e( 'Enter url with <code>http://</code> or <code>https://</code> as prefix, whichever is suitable. Sequence of entry doesn\'t matter.', 'cgss' ); ?></i></p>
											</div><br />

											<!--Url input-->
											<div class="compete-url-input-cover">
												<span class="view-url">1</span>
												<button type="button" class="button scan-to-compete"><?php _e( 'SCAN', 'cgss' ); ?></button>
												<input class="regular-text compete-url" name="compete-url" type="text" maxlength="100" placeholder="Enter complete url here" />
												<span class="hide-scan-compete-ok success-icon"><?php echo $elem->dashicon( 'yes' ); ?></span>
											</div>
											<button type="button" class="button button-block url-more aligncenter">+ <?php _e( 'MORE', 'cgss' ); ?></button>
											<?php echo $elem->loading( 'compete-scan' ); ?>
											<br /><span class="compete-scan-msg"></span>

											<br /><br /><hr />
											<p class="theme-description">
												<button type="button" class="button submit-compete"><?php _e( 'COMPARE AND OPTIMIZE', 'cgss' ); ?></button>
												<button type="button" class="button reset-compete"><?php _e( 'RESET', 'cgss' ); ?></button>
												<?php echo $elem->loading( 'compete' ); ?>
												<br /><span class="compete-msg"></span>
											</p>
										</form>
										<?php if ( ! $xtend_install ) : ?>
											<p class="parent-theme"><?php _e( 'Help documentation and support request link are made available, when you install and activate this feature.', 'cgss' ); ?></p>
										<?php else : ?>
											<p class="parent-theme"><?php _e( 'Take a look at <strong>Extension</strong> and <strong>Extension FAQ</strong> tab in inbuilt <strong>Help section</strong> (at top right corner of this page) for help.', 'cgss' ); ?></p>
										<?php endif; ?>
									</div>
									<div class="col-2">
										<div class="grey-border-box">
											<h4 class="success-icon"><?php _e( 'From Last Report', 'cgss' ); ?></h4>
											<span><?php _e( 'Fetch last competitive analysis results for this page.', 'cgss' ); ?></p></span>
											<button type="button" class="button fetch-compete"<?php echo ( ! $xtend_install ? ' disabled="disabled"' : '' ); ?>><?php _e( 'SHOW REPORT', 'cgss' ); ?></button>
											<?php echo $elem->loading( 'fetch-result' ); ?>
										</div>
										<span class="compete-fetch-msg"></span>
									</div>
								</div>
							</div>

							<!--Compete Results-->
							<div class="compete-result">
								<?php if ( ! $xtend_install ) : ?>
									<h4 class="theme-update"><?php _e( 'COMPARATIVE RESULTS', 'cgss' ); ?></h4>
									<p class="parent-theme"><?php _e( 'This is a <strong>sample result</strong> for demo purpose only. After activating premium extension you can see original result.', 'cgss' ); ?></p>
								<?php else : ?>
									<h4 class="theme-update"><?php _e( 'RESULTS FROM <span id="CompNumResult"></span> COMPETITORS', 'cgss' ); ?></h4>
								<?php endif; ?>
								<div class="row">
									<div class="col-2">
										<?php $heads = array( __( 'Words', 'cgss' ), __( 'Count', 'cgss' ) );
											$max = array( 'MaxWord' );
											$min = array( 'MinWord' );
											$avg = array( 'AvgWord' );
											$you = array( 'YouWord' );
											$word_table = $tables->comp_multi( $heads, $max, $min, $avg, $you, 'CompeteWords' );
											echo $accord->display( 'comp-word', __( 'Number of Words', 'cgss' ), $elem->dashicon( 'editor-alignleft' ), $word_table ); ?>
									</div>
									<div class="col-4">
										<?php $heads = array( __( 'Links', 'cgss' ), __( 'Total Count', 'cgss' ), __( 'External', 'cgss' ), __( 'Nofollow', 'cgss' ) );
											$max = array( 'MaxLinks', 'MaxExtLinks', 'MaxNofLinks' );
											$min = array( 'MinLinks', 'MinExtLinks', 'MinNofLinks' );
											$avg = array( 'AvgLinks', 'AvgExtLinks', 'AvgNofLinks' );
											$you = array( 'YouLinks', 'YouExtLinks', 'YouNofLinks' );
											$links_table = $tables->comp_multi( $heads, $max, $min, $avg, $you, 'CompeteLinks' );
											echo $accord->display( 'comp-links', __( 'Number of Links', 'cgss' ), $elem->dashicon( 'networking' ), $links_table ); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-2">
										<?php $heads = array( __( 'Text/HTML', 'cgss' ), __( 'Percent', 'cgss' ) );
											$max = array( 'MaxThr' );
											$min = array( 'MinThr' );
											$avg = array( 'AvgThr' );
											$you = array( 'YouThr' );
											$thr_table = $tables->comp_multi( $heads, $max, $min, $avg, $you, 'CompeteThr' );
											echo $accord->display( 'comp-thr', __( 'Text/HTML Ratio', 'cgss' ), $elem->dashicon( 'chart-pie' ), $thr_table ); ?>
									</div>
									<div class="col-2">
										<?php $heads = array( __( 'Images', 'cgss' ), __( 'Count', 'cgss' ) );
											$max = array( 'MaxImages' );
											$min = array( 'MinImages' );
											$avg = array( 'AvgImages' );
											$you = array( 'YouImages' );
											$images_table = $tables->comp_multi( $heads, $max, $min, $avg, $you, 'CompeteImages' );
											echo $accord->display( 'comp-images', __( 'Number of Images', 'cgss' ), $elem->dashicon( 'format-image' ), $images_table ); ?>
									</div>
									<div class="col-2">
										<?php $heads = array( __( 'Speed', 'cgss' ), __( 'Seconds', 'cgss' ) );
											$max = array( 'MaxSpeed' );
											$min = array( 'MinSpeed' );
											$avg = array( 'AvgSpeed' );
											$you = array( 'YouSpeed' );
											$speed_table = $tables->comp_multi( $heads, $max, $min, $avg, $you, 'CompeteSpeed' );
											echo $accord->display( 'comp-speed', __( 'Loading Time', 'cgss' ), $elem->dashicon( 'clock' ), $speed_table ); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-3">
										<?php $heads = array( __( 'Shares', 'cgss' ), $elem->dashicon( 'googleplus' ), $elem->dashicon( 'facebook-alt' ), $elem->dashicon( 'twitter' ) );
											$max = array( 'MaxGp', 'MaxFb', 'MaxTw' );
											$min = array( 'MinGp', 'MinFb', 'MinTw' );
											$avg = array( 'AvgGp', 'AvgFb', 'AvgTw' );
											$you = array( 'YouGp', 'YouFb', 'YouTw' );
											$share_table = $tables->comp_multi( $heads, $max, $min, $avg, $you, 'CompeteShares' );
											echo $accord->display( 'comp-shares', __( 'Number of Shares', 'cgss' ), $elem->dashicon( 'share' ), $share_table ); ?>
									</div>
									<div class="col-3">
										<?php $heads = array( __( 'Keywords', 'cgss' ), __( 'Count', 'cgss' ), __( 'Percent', 'cgss' ) );
											$max = array( 'MaxKeysCount', 'MaxKeysPercent' );
											$min = array( 'MinKeysCount', 'MinKeysPercent' );
											$avg = array( 'AvgKeysCount', 'AvgKeysPercent' );
											$you = array( 'YouKeysCount', 'YouKeysPercent' );
											$keys_table = $tables->comp_multi( $heads, $max, $min, $avg, $you, 'CompeteKeys' );
											echo $accord->display( 'comp-keys', __( 'Amount of Keyword', 'cgss' ), $elem->dashicon( 'text' ), $keys_table ); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-3">
										<?php $heads = array( __( 'Parts', 'cgss' ), __( 'Among <span id="CompNum"></span> Competitors', 'cgss' ), __( 'Yours', 'cgss' ), );
											$domain = array( 'CompDomain', 'YouDomain' );
											$title = array( 'CompTitle', 'YouTitle' );
											$url = array( 'CompUrl', 'YouUrl' );
											$desc = array( 'CompDesc', 'YouDesc' );
											$alt = array( 'CompAlt', 'YouAlt' );
											$anch = array( 'CompAnch', 'YouAnch' );
											$htag = array( 'CompHtag', 'YouHtag' );
											$txt = array( 'CompTxt', 'YouTxt' );
											$bold = array( 'CompBold', 'YouBold' );
											$snip_table = $tables->comp_key_snip( $heads, $domain, $title, $url, $desc, $alt, $anch, $htag, $bold, $txt );
											echo $accord->display( 'comp-snippet', __( 'Usage of Keyword', 'cgss' ), $elem->dashicon( 'layout' ), $snip_table ); ?>
									<div class="clear"></div>
									<div class="grey-border-box">
										<h4 class="success-icon"><?php _e( 'Save this Report', 'cgss' ); ?></h4>
										<span><?php _e( 'Store this competitive analysis result for future use.', 'cgss' ); ?></p></span>
										<button type="button" class="button save-compete"<?php echo ( ! $xtend_install ? ' disabled="disabled"' : '' ); ?>><?php _e( 'SAVE REPORT', 'cgss' ); ?></button>
										<?php echo $elem->loading( 'save-result' ); ?>
									</div>
									<span class="compete-save-msg"></span>
									</div>
									<div class="col-3">
										<?php $mor_con = array(
															array( 'TextDecession', 'Should you write more words or use more HTML markup?.', 'chart-bar' ),
															array( 'LinkDecession', 'Do you need to link more to outside resources or inside pages?', 'chart-bar' ),
															array( 'ImageDecession', 'Use of images vary greatly based on your niche. How many to use?', 'chart-bar' ),
															array( 'SpeedDecession', 'Is this webpage fast enough to suit your users expectation?', 'chart-bar' ),
															array( 'SocialDecession', 'How much social popularity will be adequate? Which network to focus?', 'chart-bar' ),
															array( 'KeyDecession', 'How many times and where you should use focus keyword?', 'chart-bar' ),
															array( 'BonusDecession', 'Bonus: How many sites are using ssl security and mobile responsive design?', 'chart-bar' ),
														);
											$more_table = '';
											foreach ( $mor_con as $val ) {
												$more_table .= '<div class="cgss-actions-comment">' .
																'<span class="success-icon">' . $elem->dashicon( $val[2] ) . '</span> <span id="' . $val[0] . '">' . ( ! $xtend_install ? $val[1] : '' ) . '</span>
															</div>';
											}
											if ( ! $xtend_install ) {
												$concu = __( 'Intel to take optimization decession', 'cgss' );
											} else {
												$concu = __( 'In conclusion', 'cgss' );
											}
											echo $accord->display( 'comp-more', $concu, $elem->dashicon( 'screenoptions' ), $more_table ); ?>
									</div>
								</div>
								<?php if ( ! $xtend_install ) : ?>
									<p class="parent-theme"><?php _e( 'Optimum range is calculated based on average and statistical standard deviation of data.', 'cgss' ); ?></p>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<div class="theme-actions">
						<div class="active-theme">
							<?php if ( $xtend_install ) :
								echo $btns->compete_on();
							else :
								echo $btns->compete_off();
							endif; ?>
						</div>
					</div>
				</div>
		</div>
	</div>
</div>
