;(function (window, document, $, wp, CMSCI, undefined) {
	'use strict';

	function on_load() {
		var special_chars = new RegExp('[' + CMSCI.SPECIAL_CHARS.join('') + ']');

		$('.CodeMirror').each(function (index, element) {
			if (typeof element.CodeMirror === 'undefined') {
				return;
			}

			element.CodeMirror.setOption('specialChars', special_chars);
		});
	}

	$(window).on('load', on_load);
}(window, window.document, jQuery, wp, CMSCI));
