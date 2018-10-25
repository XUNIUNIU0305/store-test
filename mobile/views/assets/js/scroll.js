(function (global, factory) {
	if (typeof define === "function" && define.amd) {
		define(['module', 'exports'], factory);
	} else if (typeof exports !== "undefined") {
		factory(module, exports);
	} else {
		var mod = {
			exports: {}
		};
		factory(mod, mod.exports);
		global.scroll = mod.exports;
	}
})(this, function (module, exports) {
	'use strict';

	Object.defineProperty(exports, "__esModule", {
		value: true
	});


	var ids = [];
	var elements = [];
	var handles = [];

	function scroll(element) {
		var id = '';

		function then(callback) {

			id = Math.floor(Math.random() * new Date().getTime());
			ids.push(id);
			elements.push(element);

			var handle = function handle(e) {
				var scrollHeight = _get('scrollHeight', element);
				var scrollWidth = _get('scrollWidth', element);
				var scrollTop = _get('scrollTop', element);
				var scrollLeft = _get('scrollLeft', element);
				var clientHeight = _get('clientHeight', element);
				var clientWidth = _get('clientWidth', element);
				var data = {
					scrollHeight: scrollHeight,
					scrollTop: scrollTop,
					clientHeight: clientHeight,
					scrollWidth: scrollWidth,
					scrollLeft: scrollLeft,
					clientWidth: clientWidth,
					bottom: false,
					right: false
				};

				if (scrollTop + clientHeight === scrollHeight) {
					data.bottom = true;
				}

				if (scrollLeft + clientWidth === scrollWidth) {
					data.right = true;
				}

				typeof callback === 'function' && callback(data);
			};

			handles.push(handle);

			_addEvent(element, 'scroll', handle);
		}

		function unbind() {
			var index = elements.indexOf(element);
			_removeEvent(element, 'scroll', handles[index]);

			ids.splice(index, 1);
			elements.splice(index, 1);
			handles.splice(index, 1);
		}

		function to(type, number) {
			var map = {
				top: 'scrollTop',
				left: 'scrollLeft'
			};

			type = map[type];

			if (_isRootElement(element)) {
				setTimeout(function () {
					document.body[type] = document.documentElement[type] = number;
				}, 0);
			} else {
				element[type] = number;
			}
		}

		return { then: then, to: to, unbind: unbind };
	}

	function _isRootElement(element) {
		if (element === document || element === window || element === document.body || element === document.documentElement) {
			return true;
		} else {
			return false;
		}
	}

	function _get(type, element) {
		if (_isRootElement(element)) {
			return Math.max(document.body[type], document.documentElement[type]);
		} else {
			return element[type];
		}
	}

	function _addEvent(element, type, callback) {
		if (window.addEventListener) {
			element.addEventListener(type, callback);
		} else if (window.attachEvent) {
			element.attachEvent('on' + type, callback);
		} else {
			element['on' + type] = callback;
		}
	}

	function _removeEvent(element, type, callback) {
		if (window.removeEventListener) {
			element.removeEventListener(type, callback);
		} else if (window.detachEvent) {
			element.detachEvent('on' + type, callback);
		} else {
			element['on' + type] = null;
		}
	}

	exports.default = scroll;
	module.exports = exports['default'];
});
