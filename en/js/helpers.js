
var console;

if (console == null || typeof(console) == undefined){
	console = {
		log: function(a){
		}
	}
}

function numbersonly(e, decimal) {
    var key;
    var keychar;

    if (window.event) {
        key = window.event.keyCode;
    } else if (e) {
        key = e.which;
    } else {
        return true;
    }
    keychar = String.fromCharCode(key);

    if ((key == null) || (key == 0) || (key == 8) || (key == 9) || (key == 13)
            || (key == 27)) {
        return true;
    } else if ((("0123456789").indexOf(keychar) > -1)) {
        return true;
    } else if (decimal && (keychar == ".")) {
        return true;
    } else
        return false;
}

function addCommas(n){
    if ($.type(n) == 'undefined' || isNaN(n) || n == null || n == '')
        n = 0;
    n = n * 1;//parseInt(n);
    strValue = String(n);
    var objRegExp = new RegExp('(-?[0-9]+)([0-9]{3})'); 
    while(objRegExp.test(strValue)) {
        strValue = strValue.replace(objRegExp, '$1,$2');
    } 
    return strValue;
}


/* Loading Overlay */
function showLoadingOverlay(){
    $(function() {
    		$('#loading_dialog').show();
    	});
    
}

function hideLoadingOverlay(){
    $(function() {
    		$('#loading_dialog').hide();
    	});
}


/* 엘레먼트 센터 정렬 */
jQuery.fn.center = function () {
    this.css("position","absolute");
    this.css("top", ( $(window).height() - this.height() ) / 2+$(window).scrollTop() + "px");
    this.css("left", ( $(window).width() - this.width() ) / 2+$(window).scrollLeft() + "px");
    return this;
}



function lockScroll(){
	// lock scroll position, but retain settings for later
      var scrollPosition = [
        self.pageXOffset || document.documentElement.scrollLeft || document.body.scrollLeft,
        self.pageYOffset || document.documentElement.scrollTop  || document.body.scrollTop
      ];
      var html = jQuery('html'); // it would make more sense to apply this to body, but IE7 won't have that
      html.data('scroll-position', scrollPosition);
      html.data('previous-overflow', html.css('overflow'));
      html.css('overflow', 'hidden');
      window.scrollTo(scrollPosition[0], scrollPosition[1]);
}

function unlockScroll(){
	// un-lock scroll position
      var html = jQuery('html');
      var scrollPosition = html.data('scroll-position');
      html.css('overflow', html.data('previous-overflow'));
      window.scrollTo(scrollPosition[0], scrollPosition[1])
}


function json_stringify(obj) {
    var t = typeof (obj);
    if (t != "object" || obj === null) {
        // simple data type
        if (t == "string") obj = '"'+obj+'"';
        return String(obj);
    }
    else {
        // recurse array or object
        var n, v, json = [], arr = (obj && obj.constructor == Array);
        for (n in obj) {
            v = obj[n]; t = typeof(v);
            if (t == "string") v = '"'+v+'"';
            else if (t == "object" && v !== null) v = json_stringify(v);
            json.push((arr ? "" : '"' + n + '":') + String(v));
        }
        return (arr ? "[" : "{") + String(json) + (arr ? "]" : "}");
    }
};


(function($) {
$.fn.placeholder = function() {
if(typeof document.createElement("input").placeholder == 'undefined') {
$('[placeholder]').focus(function() {
var input = $(this);
if (input.val() == input.attr('placeholder')) {
input.val('');
input.removeClass('placeholder');
}
}).blur(function() {
var input = $(this);
if (input.val() == '' || input.val() == input.attr('placeholder')) {
input.addClass('placeholder');
input.val(input.attr('placeholder'));
}
}).blur().parents('form').submit(function() {
$(this).find('[placeholder]').each(function() {
var input = $(this);
if (input.val() == input.attr('placeholder')) {
input.val('');
}
})
});
}
}
})(jQuery);


function FauxPlaceholder() {
    if(!ElementSupportAttribute('input','placeholder')) {
        $("input[placeholder]").each(function() {
            var $input = $(this);
            $input.after('<input id="'+$input.attr('id')+'-faux" style="display:none;" type="text" value="' + $input.attr('placeholder') + '" />');
            var $faux = $('#'+$input.attr('id')+'-faux');

            $faux.show().attr('class', $input.attr('class')).attr('style', $input.attr('style'));
            $input.hide();

            $faux.focus(function() {
                $faux.hide();
                $input.show().focus();
            });

            $input.blur(function() {
                if($input.val() === '') {
                    $input.hide();
                    $faux.show();
                }
            });
        });
    }
}
function ElementSupportAttribute(elm, attr) {
    var test = document.createElement(elm);
    return attr in test;
}
