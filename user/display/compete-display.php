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
								<p><span class="success-icon"><?php echo $elem->dashicon( 'welcome-learn-more' ); ?></span> <?php _e( 'This is an an extension of Complete Google Seo Scan Plugin for WordPress', 'cgss' ); ?>. <a href="http://gogretel.com/checkout-2?edd_action=straight_to_gateway&download_id=570" target="_blank"><?php _e( 'Get it for', 'cgss' ); ?> $64</a>, <?php _e( 'install and activate this extension as a seperate plugin. Then you can use this feature here.', 'cgss' ); ?></p>
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
											<p class="parent-theme"><?php _e( 'Help documentation and support request link are made available, when you install and activate this feature. Check out', 'cgss' ); ?> <a href="http://gogretel.com/terms/" target="_blank"><?php _e( 'terms of use', 'cgss' ); ?></a> <?php _e( 'of our website, before you place an order.', 'cgss' ); ?></p>
										<?php else : ?>
											<p class="parent-theme"><?php _e( 'Take a look at <strong>Extension</strong> and <strong>Extension FAQ</strong> tab in inbuilt <strong>Help section</strong> (at top right corner of this page) for help.', 'cgss' ); ?></p>
										<?php endif; ?>
									</div>
									<div class="col-2">
										<div class="grey-border-box">
											<h4><?php _e( 'From Last Report', 'cgss' ); ?></h4>
											<span><?php _e( 'Fetch last competitive analysis results for this page.', 'cgss' ); ?></p></span>
											<button type="button" class="button fetch-compete"<?php echo ( ! $xtend_install ? ' disabled="disabled"' : '' ); ?>><?php _e( 'SHOW REPORT', 'cgss' ); ?></button>
											<?php echo $elem->loading( 'fetch-result' ); ?>
											<?php if ( ! $xtend_install ) : ?>
												<br /><small><?php _e( 'Available with original extension', 'cgss' ); ?></small>
											<?php endif; ?>
										</div>
										<span class="compete-fetch-msg"></span>
										<?php if ( ! $xtend_install ) : ?>
											<div class="clear"></div>
											<div class="grey-border-box">
												<p><?php _e( 'Find out how the result will help you?', 'cgss' ); ?></p>
												<button class="button button-primary show-demo-result"><?php _e( 'Show Demo Result', 'cgss' ); ?></button>
											</div>
										<?php endif; ?>
									</div>
								</div>
							</div>

							<!--Compete Results-->
							<div class="compete-result">
								<?php if ( ! $xtend_install ) : ?>
									<p class="parent-theme"><?php _e( 'This is a <strong>sample result</strong> for demo purpose only. After activating original extension you can generate original result.', 'cgss' ); ?></p>
									<div class="aligncenter">
										<h4 class="theme-update"><?php _e( 'RESEARCH COMPETITORS USING GOOGLE SEARCH AND TAKE 10 CRUCIAL SEO DECISIONS', 'cgss' ); ?></h4>
									</div>
									<?php else : ?>
									<div class="aligncenter">
										<h4 class="theme-update"><?php _e( 'RESULTS FROM <span id="CompNumResult"></span> COMPETITORS', 'cgss' ); ?></h4>
									</div>
									<?php endif; ?>
								<div class="clear"></div>
								<div class="row">
									<div class="col-1 hide-mobile"></div>
									<div class="col-2">
										<?php $word_table = $elem->compiled_progress_bar( 'Word', array( 360, '1.5k', 452, '500 - 985', 608 . $elem->ok() ) ) .
										'<span>' . __( 'Number of Words', 'cgss' ) . '</span><hr /><br />' .
										$elem->compiled_progress_bar( 'Thr', array( 11, 26, 18.8, '14 - 20', 15 . $elem->ok() ) ) .
										'<span>' . __( 'Text to HTML ratio', 'cgss' ) . '</span><hr /><br />' .
										$elem->comp_help( 'Words', __( 'Words in content and text to html ratio in terms of size is shown. You may optimize the ratio by writing more words.', 'cgss' ) );
										echo $accord->display( 'comp-word', __( 'How many words to write?', 'cgss' ), $elem->dashicon( 'editor-alignleft' ), $word_table ); ?>
									</div>
									<div class="col-2">
										<?php $links_table = $elem->compiled_progress_bar( 'Links', array( 9, 36, 19, '16 - 29', 26 . $elem->ok() ) ) .
										'<span>' . __( 'Total Links', 'cgss' ) . '</span><hr /><br />' .
										$elem->compiled_progress_bar( 'ExtLinks', array( 5, 21, 12, '10 - 15', 9 . $elem->down() ) ) .
										'<span>' . __( 'External Links', 'cgss' ) . '</span><hr /><br />' .
										$elem->comp_help( 'Links', __( 'First make sure number of total links are optimized. Then balance external links as compared to total links.', 'cgss' ) );
										echo $accord->display( 'comp-links', __( 'How many links to include?', 'cgss' ), $elem->dashicon( 'networking' ), $links_table ); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-1 hide-mobile"></div>
									<div class="col-4">
										<?php $h1 = $elem->progress_bar( 'Comph1', 'h1' . '<span id="Youh1"></span>', '8' );
											$h2 = $elem->progress_bar( 'Comph2', 'h2' . '<span id="Youh2">' . $elem->ok() . '</span>', '30' );
											$h3 = $elem->progress_bar( 'Comph3', 'h3' . '<span id="Youh3"></span>', '80' );
											$h4 = $elem->progress_bar( 'Comph4', 'h4' . '<span id="Youh4">' . $elem->ok() . '</span>', '62' );
											$h5 = $elem->progress_bar( 'Comph5', 'h5' . '<span id="Youh5">' . $elem->ok() . '</span>', '11' );
											$h6 = $elem->progress_bar( 'Comph6', 'h6' . '<span id="Youh6"></span>', '20' );
											$snip_table = $elem->sign_blocks( __( 'means you have that heading tag', 'cgss' ), false, false ) .
											'<div class="row">
												<div class="col-3">
													<div style="padding-right: 10px;">' .
														$h1 . $h2 . $h3 .
													'</div>
												</div>
												<div class="col-3">
													<div style="padding-left: 10px;">' .
														$h4 . $h5 . $h6 .
													'</div>
												</div>
											</div>' .
											$elem->comp_help( 'Heads', __( 'Percentage based on total number of each heading tags, found in all competitors.<br />For example, if <code>h4</code> is mentaioned once or more in any competitor, +1 has been added to total number of <code>h4</code>.', 'cgss' ) );
											echo $accord->display( 'comp-heads', __( 'What are the necessary heading tags?', 'cgss' ), $elem->dashicon( 'tagcloud' ), $snip_table ); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-1 hide-mobile"></div>
									<div class="col-4">
										<?php $keys_table = '
										<div class="row">
											<div class="col-3">
												<div style="padding-left: 10px;">' .
													$elem->compiled_progress_bar( 'Keysperc', array( 0, 2.142, 1.1, '0.7 - 1.5', 1.78 . $elem->up() ) ) .
													'<span>' . __( 'Keyword frrequency in', 'cgss' ) . ' %</span><hr />' .
												'</div>
											</div>
											<div class="col-3">
												<div style="padding-right: 10px;">' .
													$elem->compiled_progress_bar( 'KeysCount', array( 0, 14, 5, '4 - 9', 6 . $elem->ok() ) ) .
													'<span>' . __( 'keyword count in content', 'cgss' ) . '</span><hr />
												</div>
											</div>
										</div>' .
										$elem->comp_help( 'Keys', __( 'Target keyword frequency represents use of keyword in total content. It must kept optimum even if number of keywords are not.', 'cgss' ) );
										echo $accord->display( 'comp-keys', __( 'How many times to use keyword?', 'cgss' ), $elem->dashicon( 'text' ), $keys_table ); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-1 hide-mobile"></div>
									<div class="col-4">
										<?php
											$domain = $elem->progress_bar( 'CompDomain', __( 'Domain Name', 'cgss' ) . '<span id="YouDomain"></span>', '8' );
											$title = $elem->progress_bar( 'CompTitle', __( 'Title tag', 'cgss' ) . '<span id="YouTitle"></span>', '30' );
											$url = $elem->progress_bar( 'CompUrl', __( 'Url', 'cgss' ) . '<span id="YouUrl">' . $elem->ok() . '</span>', '80' );
											$desc = $elem->progress_bar( 'CompDesc', __( 'Meta description tag', 'cgss' ) . '<span id="YouDesc">' . $elem->ok() . '</span>', '62' );
											$alt = $elem->progress_bar( 'CompAlt', __( 'Alt tag', 'cgss' ) . '<span id="YouAlt"></span>', '11' );
											$anch = $elem->progress_bar( 'CompAnch', __( 'Anchor text', 'cgss' ) . '<span id="YouAnch"></span>', '20' );
											$htag = $elem->progress_bar( 'CompHtag', __( 'Heading Tag', 'cgss' ) . '<span id="YouHtag"></span>', '50' );
											$txt = $elem->progress_bar( 'CompTxt', __( 'Plain text', 'cgss' ) . '<span id="YouTxt">' . $elem->ok() . '</span>', '74' );
											$snip_parts = $elem->sign_blocks( __( 'means you have keyword there', 'cgss' ), false, false ) .
											'<div class="row">
												<div class="col-3">
													<div style="padding-right: 10px;">' .
														$domain . $title . $url . $desc .
													'</div>
												</div>
												<div class="col-3">
													<div style="padding-left: 10px;">' .
														$alt . $anch . $htag . $txt .
													'</div>
												</div>
											</div>' .
											$elem->comp_help( 'Snip', __( 'Importance percentage of areas, according to target keyword found in those areas of all competitors.<br />For example, if target keyword is found once or more in total anchor text of any competitor, +1 is added to importance number for anchor text.', 'cgss' ) );
											echo $accord->display( 'comp-snippet', __( 'Which area to focus for Keyword usage?', 'cgss' ), $elem->dashicon( 'layout' ), $snip_parts ); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-1 hide-mobile"></div>
									<div class="col-4">
										<?php $social_bars = '
										<div class="row">
											<div class="col-2">
												<div style="padding-right: 10px; padding-left: 10px;">' . 
													$elem->progress_bar( 'Gp', $elem->dashicon( 'googleplus' ) . ' <span id="TotalGp" class="gplus">82</span>', 35 ) .
												'</div>
											</div>
											<div class="col-2">
												<div style="padding-right: 10px; padding-left: 10px;">' .
													$elem->progress_bar( 'Fb', $elem->dashicon( 'facebook-alt' ) . ' <span id="TotalFb" class="facebook">120</span>', 40 ) .
												'</div>
											</div>
											<div class="col-2">
												<div style="padding-right: 10px; padding-left: 10px;">' .
													$elem->progress_bar( 'Tw', $elem->dashicon( 'twitter' ) . ' <span id="TotalTw" class="twitter">75</span>', 25 ) .
												'</div>
											</div>
										</div>';
										$share_table = $social_bars . $elem->comp_help( 'Share', __( 'Each chart shows percentage share of each social networks among total shares, taken from all competitors.<br />In Google+ share means +1s. In Facebook and Twitter share means number of likes and tweets respectively.', 'cgss' ) );
										echo $accord->display( 'comp-shares', __( 'Which social network to focus?', 'cgss' ), $elem->dashicon( 'share' ), $share_table ); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-1 hide-mobile"></div>
									<div class="col-2">
										<div class="grey-border-box">
											<h4><?php _e( 'Competitors having ...', 'cgss' ); ?></h4>
											<?php echo $elem->progress_bar( 'CompResponsive', __( 'Responsive design', 'cgss' ), '74' ) . '<hr />' . $elem->progress_bar( 'CompSslSec', __( 'SSL security', 'cgss' ), '22' ); ?>
										</div>
									</div>
									<div class="col-2">
										<div class="grey-border-box">
											<h4><?php _e( 'How many images will suit?', 'cgss' ); ?></h4>
											<?php echo $elem->compiled_progress_bar( 'ImagesCount', array( 5, 19, 12, '8 - 16', 12 . $elem->ok() ) ); ?>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-1 hide-mobile"></div>
									<div class="col-2">
										<div class="grey-border-box">
											<h4><?php _e( 'Save this Report', 'cgss' ); ?></h4>
											<span><?php _e( 'Store this competitive analysis result for future use.', 'cgss' ); ?></p></span>
											<button type="button" class="button save-compete"<?php echo ( ! $xtend_install ? ' disabled="disabled"' : '' ); ?>><?php _e( 'SAVE REPORT', 'cgss' ); ?></button>
											<?php echo $elem->loading( 'save-result' ); ?>
											<?php if ( ! $xtend_install ) : ?>
												<br /><small><?php _e( 'Available with original extension', 'cgss' ); ?></small>
											<?php endif; ?>
										</div>
										<span class="compete-save-msg"></span>
									</div>
									<div class="col-2">
										<div class="grey-border-box">
											<h4><?php _e( 'Average speed of competitors', 'cgss' ); ?></h4>
											<p><span id="AvgSpeed">2.361</span> <?php _e( 'seconds', 'cgss' ); ?></p>
											<?php echo $elem->progress_bar( 'CompSpeed', __( 'compared to slowest that\'s faster by', 'cgss' ), '34' ); ?>
										</div>
									</div>
								</div>
								<?php if ( ! $xtend_install ) : ?>
									<p class="parent-theme"><?php _e( '<strong>Optimum range</strong> is calculated based on average and statistical standard deviation of data.', 'cgss' ); ?></p>
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
