<?php
/**
 * @/user/lib/snp-class.php
 * on: 08.06.2015
 * Custom snippet method. css classes are custom built in plugin stylesheet.
 *
 * 6 properties:
 * 1. $snp_id for snippet id attribute.
 * 2. $snp_title for snippet icon class attribute. Required.
 * 3. $snp_url for snippet content. Required.
 * 4. $snp_pre_desc for small text before main snippet content.
 * 5. $snp_desc for snippet content. Required for search() function.
 * 6. $snp_img for snippet icon class attribute. Required for ogp() and twitter() functions.
 * 7. $snp_heading for snippet icon class attribute.
 *
 * Using custom css class: hide-snp-text, this snippet hides text content and shows  for small screen.
 */
class CGSS_SNIPPET {

	//declare properties
	private $snp_id;
	private $snp_title;
	private $snp_url;
	private $snp_pre_desc;
	private $snp_desc;
	private $snp_img;
	private $snp_heading;

	//construct properties
	public function __construct( $snp_id, $snp_title, $snp_url, $snp_desc, $snp_pre_desc, $snp_img, $snp_heading ) {
		$this->snp_id = $snp_id;
		$this->snp_title = $snp_title;
		$this->snp_url = $snp_url;
		$this->snp_pre_desc = $snp_pre_desc;
		$this->snp_desc = $snp_desc;
		$this->snp_img = $snp_img;
		$this->snp_heading = $snp_heading;
	}

	//define method: display() to output raw html for search snippet.
	public function display_search() {
		if ( $this->snp_title and $this->snp_url and $this->snp_desc ) :
		return
			'<div' . ( $this->snp_id ? ' id="' . $this->snp_id . '"' : '' ) . ' class="snippet-container">
				<div class="search-snippet">
					<span id="SnippetTitle">' . $this->snp_title . '</span>
					<div class="search-snippet-gap"></div>
						<span id="SnippetUrl">' . $this->snp_url . '</span>
					<div class="search-snippet-gap"></div>
					<p id="SnippetPreDesc">' . $this->snp_pre_desc . '</p>
					<p id="SnippetDesc">' . $this->snp_desc . '</p>
				</div>
			</div>' .
			( $this->snp_heading ? '<div class="put-center"><span class="current-label">' . $this->snp_heading . '</span></div>' : '' );
		else:
			return __( 'SEARCH SNIPPET ERROR', 'cgss' );
		endif;
	}

	//
	public function ogp() {
		if ( $this->snp_title and $this->snp_url and $this->snp_img ) :
		return
			'<div' . ( $this->snp_id ? ' id="' . $this->snp_id . '"' : '' ) . ' class="snippet-container">
				<div class="ogp-snippet">
					<div class="ogp-image">
						
					</div>
					<div class="ogp-text">
						
					</div>
				</div>
			</div>';
		else:
			return __( 'OGP SNIPPET ERROR', 'cgss' );
		endif;
	}
}
?>
