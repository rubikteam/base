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
  function dialogDiv(cm, template) {
    var wrap = cm.getWrapperElement();
    var dialog = wrap.insertBefore(document.createElement("div"), wrap.firstChild);
    dialog.className = "CodeMirror-dialog";
    dialog.innerHTML = '<div>' + template + '</div>';
    return dialog;
  }

  CodeMirror.defineExtension("openDialog", function(template, callback) {
    var dialog = dialogDiv(this, template);
    var closed = false, me = this;
    function close() {
      if (closed) return;
      closed = true;
      dialog.parentNode.removeChild(dialog);
    }
    var inp = dialog.getElementsByTagName("input")[0], button;
    if (inp) {
      CodeMirror.connect(inp, "keydown", function(e) {
        if (e.keyCode == 13 || e.keyCode == 27) {
          CodeMirror.e_stop(e);
          close();
          me.focus();
          if (e.keyCode == 13) callback(inp.value);
        }
      });
      inp.focus();
      CodeMirror.connect(inp, "blur", close);
    } else if (button = dialog.getElementsByTagName("button")[0]) {
      CodeMirror.connect(button, "click", function() {
        close();
        me.focus();
      });
      button.focus();
      CodeMirror.connect(button, "blur", close);
    }
    return close;
  });

  CodeMirror.defineExtension("openConfirm", function(template, callbacks) {
    var dialog = dialogDiv(this, template);
    var buttons = dialog.getElementsByTagName("button");
    var closed = false, me = this, blurring = 1;
    function close() {
      if (closed) return;
      closed = true;
      dialog.parentNode.removeChild(dialog);
      me.focus();
    }
    buttons[0].focus();
    for (var i = 0; i < buttons.length; ++i) {
      var b = buttons[i];
      (function(callback) {
        CodeMirror.connect(b, "click", function(e) {
          CodeMirror.e_preventDefault(e);
          close();
          if (callback) callback(me);
        });
      })(callbacks[i]);
      CodeMirror.connect(b, "blur", function() {
        --blurring;
        setTimeout(function() { if (blurring <= 0) close(); }, 200);
      });
      CodeMirror.connect(b, "focus", function() { ++blurring; });
    }
  });
})();