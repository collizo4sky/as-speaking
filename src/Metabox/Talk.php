<?php
/**
 * AlainSchlesser.com Speaking Page Plugin.
 *
 * @package   AlainSchlesser\Speaking
 * @author    Alain Schlesser <alain.schlesser@gmail.com>
 * @license   MIT
 * @link      https://www.alainschlesser.com/
 * @copyright 2017 Alain Schlesser
 */

namespace AlainSchlesser\Speaking\Metabox;

use AlainSchlesser\Speaking\Assets\ScriptAsset;
use AlainSchlesser\Speaking\Assets\StyleAsset;
use AlainSchlesser\Speaking\CustomPostType\Talk as TalkCPT;
use AlainSchlesser\Speaking\Model\TalkRepository;

/**
 * Abstract class BaseMetabox.
 *
 * @since   0.1.0
 *
 * @package AlainSchlesser\Speaking
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
final class Talk extends BaseMetabox {

	const ID       = 'talk_cpt_metabox';
	const VIEW_URI = 'views/talk-metabox';

	const CSS_HANDLE = 'as-speaking-backend-css';
	const CSS_URI    = 'assets/styles/as-speaking-backend';

	const JS_HANDLE = 'as-speaking-backend-js';
	const JS_URI    = 'assets/scripts/as-speaking-backend';

	const DATE_FORMATTER_JS_HANDLE = 'php-date-formatter-js';
	const DATE_FORMATTER_JS_URI    = 'assets/scripts/php-date-formatter';

	/**
	 * Get the ID to use for the metabox.
	 *
	 * @since 0.1.0
	 *
	 * @return string ID to use for the metabox.
	 */
	protected function get_id() {
		return self::ID;
	}

	/**
	 * Get the title to use for the metabox.
	 *
	 * @since 0.1.0
	 *
	 * @return string Title to use for the metabox.
	 */
	protected function get_title() {
		return __( 'Talk properties', 'as-speaking' );
	}

	/**
	 * Get the array of known assets.
	 *
	 * @since 0.1.0
	 *
	 * @return array<Asset>
	 */
	protected function get_assets() {

		$date_formatter_script = new ScriptAsset(
			self::DATE_FORMATTER_JS_HANDLE,
			self::DATE_FORMATTER_JS_URI,
			[],
			false,
			ScriptAsset::ENQUEUE_FOOTER
		);

		$metabox_style = new StyleAsset(
			self::CSS_HANDLE,
			self::CSS_URI
		);

		$metabox_script = new ScriptAsset(
			self::JS_HANDLE,
			self::JS_URI,
			[ 'jquery', self::DATE_FORMATTER_JS_HANDLE ],
			false,
			ScriptAsset::ENQUEUE_FOOTER
		);

		$metabox_script->add_localization(
			'speakingPageMetabox',
			[
				'dateFormat' => get_option( 'date_format' ),
			]
		);

		return [
			$date_formatter_script,
			$metabox_script,
			$metabox_style,
		];
	}

	/**
	 * Get the screen on which to show the metabox.
	 *
	 * @since 0.1.0
	 *
	 * @return string|array|\WP_Screen Screen on which to show the metabox.
	 */
	protected function get_screen() {
		return TalkCPT::SLUG;
	}

	/**
	 * Get the context in which to show the metabox.
	 *
	 * @since 0.1.0
	 *
	 * @return string Context to use.
	 */
	protected function get_context() {
		return static::CONTEXT_SIDE;
	}

	/**
	 * Get the priority within the context where the boxes should show.
	 *
	 * @since 0.1.0
	 *
	 * @return string Priority within context.
	 */
	protected function get_priority() {
		return static::PRIORITY_HIGH;
	}

	/**
	 * Get the View URI to use for rendering the metabox.
	 *
	 * @since 0.1.0
	 *
	 * @return string View URI.
	 */
	protected function get_view_uri() {
		return self::VIEW_URI;
	}

	/**
	 * Process the metabox attributes.
	 *
	 * @since 0.1.0
	 *
	 * @param array|string $atts Raw metabox attributes passed into the
	 *                           metabox function.
	 *
	 * @return array Processed metabox attributes.
	 */
	protected function process_attributes( $atts ) {
		$talk_repository = new TalkRepository();
		$atts            = (array) $atts;
		$atts['talk']    = $talk_repository->find( get_the_ID() );

		return $atts;
	}

	/**
	 * Do the actual persistence of the changed data.
	 *
	 * @since 0.1.0
	 *
	 * @param int $post_id ID of the post to persist.
	 *
	 * @return void
	 */
	protected function persist( $post_id ) {
		$talk_repository = new TalkRepository();
		$talk            = $talk_repository->find( $post_id );
		$talk->parse_post_data( $_POST );
		$talk->persist_properties();
	}
}
