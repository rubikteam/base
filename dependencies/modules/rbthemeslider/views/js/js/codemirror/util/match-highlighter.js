/**
* 2007-2019 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2019 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*
* Don't forget to prefix your containers with your own identifier
* to avoid any conflicts with others containers.
*/
(function() {
  var DEFAULT_MIN_CHARS = 2;
  
  function MatchHighlightState() {
	this.marked = [];
  }
  function getMatchHighlightState(cm) {
	return cm._matchHighlightState || (cm._matchHighlightState = new MatchHighlightState());
  }
  
  function clearMarks(cm) {
	var state = getMatchHighlightState(cm);
	for (var i = 0; i < state.marked.length; ++i)
		state.marked[i].clear();
	state.marked = [];
  }
  
  function markDocument(cm, className, minChars) {
    clearMarks(cm);
	minChars = (typeof minChars !== 'undefined' ? minChars : DEFAULT_MIN_CHARS);
	if (cm.somethingSelected() && cm.getSelection().replace(/^\s+|\s+$/g, "").length >= minChars) {
		var state = getMatchHighlightState(cm);
		var query = cm.getSelection();
		cm.operation(function() {
			if (cm.lineCount() < 2000) { // This is too expensive on big documents.
			  for (var cursor = cm.getSearchCursor(query); cursor.findNext();) {
				//Only apply matchhighlight to the matches other than the one actually selected
				if (!(cursor.from().line === cm.getCursor(true).line && cursor.from().ch === cm.getCursor(true).ch))
					state.marked.push(cm.markText(cursor.from(), cursor.to(), className));
			  }
			}
		  });
	}
  }

  CodeMirror.defineExtension("matchHighlight", function(className, minChars) {
    markDocument(this, className, minChars);
  });
})();
