<?php
/*
Plugin Name: CodeMirror Special Character Indicator
Plugin URI: https://github.com/devaloka/cm-special-char-indicator
Description: Extensible special character indicator for CodeMirror, which is useful to avoid trivial errors caused by invalid characters.
Version: 1.0.0
Author: Whizark
Author URI: https://whizark.com
Text Domain: cmsci
Domain Path: /languages
Network: true
License: MIT, GNU General Public License v2 or later
*/

/**
 * CodeMirror Special Character Indicator
 *
 * @author Whizark <devaloka@whizark.com>
 * @see https://whizark.com
 * @copyright Copyright (C) 2019 Whizark.
 * @license MIT
 * @license GPL-2.0-or-later
 */
namespace Devaloka\Plugin\Code_Mirror_Special_Character_Indicator;

if ( ! function_exists( 'Devaloka\Plugin\Code_Mirror_Special_Character_Indicator\get_version' ) ) {
	/**
	 * Gets the current version of this plugin.
	 *
	 * @return string The current version.
	 */
	function get_version() {
		return get_plugin_data( __FILE__ )['Version'];
	}
}

if ( ! function_exists( 'Devaloka\Plugin\Code_Mirror_Special_Character_Indicator\admin_enqueue_styles' ) ) {
	/**
	 * Enqueues admin styles.
	 */
	function admin_enqueue_styles() {
		wp_register_style(
			'cmsci',
			plugin_dir_url( __FILE__ ) . 'assets/css/index.css',
			[ 'code-editor' ],
			get_version()
		);

		wp_enqueue_style( 'cmsci' );
	}
}

add_action( 'admin_enqueue_scripts', 'Devaloka\Plugin\Code_Mirror_Special_Character_Indicator\admin_enqueue_styles' );

if ( ! function_exists( 'Devaloka\Plugin\Code_Mirror_Special_Character_Indicator\admin_enqueue_scripts' ) ) {
	/**
	 * Enqueues admin scripts.
	 */
	function admin_enqueue_scripts() {
		wp_register_script(
			'cmsci',
			plugin_dir_url( __FILE__ ) . 'assets/js/index.js',
			[ 'jquery', 'code-editor' ],
			get_version(),
			true
		);

		$cmsci = [
			/**
			 * Filters the list of the special characters to highlight.
			 *
			 * This includes the same elements as the default value of `CodeMirror.defaults.specialChars` option by
			 * default.
			 *
			 * @see https://codemirror.net/doc/manual.html#option_specialChars CodeMirror: User Manual
			 *
			 * @param string[] $special_chars An array of the special characters.
			 *                                Each element is evaluated as a character in RegExp character class.
			 *
			 * @since 1.0.0
			 */
			'SPECIAL_CHARS' => apply_filters(
				'cmsci_special_chars',
				[
					'\u0000-\u001f',
					'\u007f-\u009f',
					'\u00ad',
					'\u061c',
					'\u200b-\u200f',
					'\u2028',
					'\u2029',
					'\ufeff',
				]
			),
		];

		$json = wp_json_encode( $cmsci );

		$js = <<<JS
;(function (window, document, $, undefined) {
	'use strict';
	
	var cmsci = {$json};

	window.CMSCI = window.CMSCI || cmsci;
}(window, window.document, jQuery));
JS;

		wp_add_inline_script( 'cmsci', $js, 'before' );

		wp_enqueue_script( 'cmsci' );
	}
}

add_action( 'admin_enqueue_scripts', 'Devaloka\Plugin\Code_Mirror_Special_Character_Indicator\admin_enqueue_scripts' );

if ( ! function_exists( 'Devaloka\Plugin\Code_Mirror_Special_Character_Indicator\cmsci_default_special_chars' ) ) {
	/**
	 * Adds additional special characters.
	 *
	 * @param string[] $special_chars An array of special characters.
	 *
	 * @return string[] The filtered array of the special characters.
	 */
	function cmsci_add_additional_special_chars( array $special_chars ) {
		$special_chars[] = '\u00a0';
		$special_chars[] = '\ufefe';

		return $special_chars;
	}
}

add_filter(
	'cmsci_special_chars',
	'Devaloka\Plugin\Code_Mirror_Special_Character_Indicator\cmsci_add_additional_special_chars'
);
