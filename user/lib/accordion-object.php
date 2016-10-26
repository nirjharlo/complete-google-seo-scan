<?php
/**
 * @/user/lib/accordion-object.php
 * @on 17.07.2015
 * @since 2.1
 *
 * Custom accordion method with heading and body box. Works with required jQuery support.
 *
 * 4 properties:
 * @prop string $id Button id attribute.
 * @prop string $title Button icon class attribute.
 * @prop string $icon Button content. Required.
 * @prop string $desc Button link href.
 *
 */
class CGSS_ACCORDION {

	public function display( $id, $title, $icon, $desc ) {
		return '<div id="dashboard_right_now" class="postbox">
			<div class="handlediv handlediv-' . $id . '">' . $icon . '</div>
			<h3 id="hndle-' . $id . '" class="hndle ui-sortable-handle" title="' . __( 'Click to toggle', 'cgss' ) . '"><span>' . $title . '</span></h3>
			<div id="inside-' . $id . '" class="inside">
				<div class="main">' .
					$desc .
				'</div>
			</div>
		</div>';
	}
}
