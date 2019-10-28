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
  function SearchState() {
    this.posFrom = this.posTo = this.query = null;
    this.marked = [];
  }
  function getSearchState(cm) {
    return cm._searchState || (cm._searchState = new SearchState());
  }
  function getSearchCursor(cm, query, pos) {
    // Heuristic: if the query string is all lowercase, do a case insensitive search.
    return cm.getSearchCursor(query, pos, typeof query == "string" && query == query.toLowerCase());
  }
  function dialog(cm, text, shortText, f) {
    if (cm.openDialog) cm.openDialog(text, f);
    else f(prompt(shortText, ""));
  }
  function confirmDialog(cm, text, shortText, fs) {
    if (cm.openConfirm) cm.openConfirm(text, fs);
    else if (confirm(shortText)) fs[0]();
  }
  function parseQuery(query) {
    var isRE = query.match(/^\/(.*)\/([a-z]*)$/);
    return isRE ? new RegExp(isRE[1], isRE[2].indexOf("i") == -1 ? "" : "i") : query;
  }
  var queryDialog =
    'Search: <input type="text" style="width: 10em"/> <span style="color: #888">(Use /re/ syntax for regexp search)</span>';
  function doSearch(cm, rb) {
    var state = getSearchState(cm);
    if (state.query) return findNext(cm, rb);
    dialog(cm, queryDialog, "Search for:", function(query) {
      cm.operation(function() {
        if (!query || state.query) return;
        state.query = parseQuery(query);
        if (cm.lineCount() < 2000) { // This is too expensive on big documents.
          for (var cursor = getSearchCursor(cm, state.query); cursor.findNext();)
            state.marked.push(cm.markText(cursor.from(), cursor.to(), "CodeMirror-searching"));
        }
        state.posFrom = state.posTo = cm.getCursor();
        findNext(cm, rb);
      });
    });
  }
  function findNext(cm, rb) {cm.operation(function() {
    var state = getSearchState(cm);
    var cursor = getSearchCursor(cm, state.query, rb ? state.posFrom : state.posTo);
    if (!cursor.find(rb)) {
      cursor = getSearchCursor(cm, state.query, rb ? {line: cm.lineCount() - 1} : {line: 0, ch: 0});
      if (!cursor.find(rb)) return;
    }
    cm.setSelection(cursor.from(), cursor.to());
    state.posFrom = cursor.from(); state.posTo = cursor.to();
  });}
  function clearSearch(cm) {cm.operation(function() {
    var state = getSearchState(cm);
    if (!state.query) return;
    state.query = null;
    for (var i = 0; i < state.marked.length; ++i) state.marked[i].clear();
    state.marked.length = 0;
  });}

  var replaceQueryDialog =
    'Replace: <input type="text" style="width: 10em"/> <span style="color: #888">(Use /re/ syntax for regexp search)</span>';
  var replacementQueryDialog = 'With: <input type="text" style="width: 10em"/>';
  var doReplaceConfirm = "Replace? <button>Yes</button> <button>No</button> <button>Stop</button>";
  function replace(cm, all) {
    dialog(cm, replaceQueryDialog, "Replace:", function(query) {
      if (!query) return;
      query = parseQuery(query);
      dialog(cm, replacementQueryDialog, "Replace with:", function(text) {
        if (all) {
          cm.compoundChange(function() { cm.operation(function() {
            for (var cursor = getSearchCursor(cm, query); cursor.findNext();) {
              if (typeof query != "string") {
                var match = cm.getRange(cursor.from(), cursor.to()).match(query);
                cursor.replace(text.replace(/\$(\d)/, function(w, i) {return match[i];}));
              } else cursor.replace(text);
            }
          });});
        } else {
          clearSearch(cm);
          var cursor = getSearchCursor(cm, query, cm.getCursor());
          function advance() {
            var start = cursor.from(), match;
            if (!(match = cursor.findNext())) {
              cursor = getSearchCursor(cm, query);
              if (!(match = cursor.findNext()) ||
                  (start && cursor.from().line == start.line && cursor.from().ch == start.ch)) return;
            }
            cm.setSelection(cursor.from(), cursor.to());
            confirmDialog(cm, doReplaceConfirm, "Replace?",
                          [function() {doReplace(match);}, advance]);
          }
          function doReplace(match) {
            cursor.replace(typeof query == "string" ? text :
                           text.replace(/\$(\d)/, function(w, i) {return match[i];}));
            advance();
          }
          advance();
        }
      });
    });
  }

  CodeMirror.commands.find = function(cm) {clearSearch(cm); doSearch(cm);};
  CodeMirror.commands.findNext = doSearch;
  CodeMirror.commands.findPrev = function(cm) {doSearch(cm, true);};
  CodeMirror.commands.clearSearch = clearSearch;
  CodeMirror.commands.replace = replace;
  CodeMirror.commands.replaceAll = function(cm) {replace(cm, true);};
})();