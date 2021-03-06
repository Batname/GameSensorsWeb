function getUnitWidthBlog() {
    "use strict";
    var t;
    return containerblog.width() <= 320 ? t = Math.floor((containerblog.width() - 20) / 1) : containerblog.width() >= 321 && containerblog.width() <= 480 ? t = Math.floor((containerblog.width() - 20) / 1) : containerblog.width() >= 481 && containerblog.width() <= 662 ? t = Math.floor((containerblog.width() - 20) / 1) : containerblog.width() >= 663 && containerblog.width() <= 768 ? t = Math.floor((containerblog.width() - 20) / 2) : containerblog.width() >= 769 && containerblog.width() <= 979 ? t = Math.floor((containerblog.width() - 40) / 3) : containerblog.width() >= 980 && containerblog.width() <= 1200 ? t = Math.floor((containerblog.width() - 40) / 3) : containerblog.width() >= 1201 && containerblog.width() <= 1600 ? t = Math.floor((containerblog.width() - 20) / 5) : containerblog.width() >= 1601 && containerblog.width() <= 1824 ? t = Math.floor((containerblog.width() - 20) / 8) : containerblog.width() >= 1825 && (t = Math.floor((containerblog.width() - 20) / 10)), t
}

function setWidthsBlog() {
    "use strict";
    var t = getUnitWidthBlog() - 0;
    containerblog.children(":not(.width2)").css({
        width: t
    }), containerblog.width() >= 321 && containerblog.width() <= 480 && (containerblog.children(".width2").css({
        width: 1 * t
    }), containerblog.children(".width4").css({
        width: 2 * t
    }), containerblog.children(".width6").css({
        width: 3 * t
    })), containerblog.width() >= 481 ? (containerblog.children(".width6").css({
        width: 4 * t
    }), containerblog.children(".width4").css({
        width: 3 * t
    }), containerblog.children(".width2").css({
        width: 2 * t
    })) : containerblog.children(".width2").css({
        width: t
    })
}

function shopping_cart_dropdown() {
    !jQuery(".widget_shopping_cart .widget_shopping_cart_content .cart_list .empty").length && jQuery(".widget_shopping_cart .widget_shopping_cart_content .cart_list").length > 0 && jQuery(".cart-menu-wrap").addClass("has_products")
}

function shopping_cart_dropdown_show(t) {
    clearTimeout(timeout), !jQuery(".widget_shopping_cart .widget_shopping_cart_content .cart_list .empty").length && jQuery(".widget_shopping_cart .widget_shopping_cart_content .cart_list").length > 0 && "undefined" != typeof t.type && (jQuery(".container .cart-menu-wrap").hasClass("has_products") ? jQuery(".container .cart-notification").is(":visible") ? jQuery(".container .cart-notification").show() : jQuery(".container .cart-notification").fadeIn(400) : setTimeout(function() {
        jQuery(".container .cart-notification").fadeIn(400)
    }, 400), timeout = setTimeout(hideCart, 2700))
}

function hideCart() {
    jQuery(".container .cart-notification").stop(!0, !0).fadeOut()
}

function NotifyMe(t, e) {
    "use strict";
    noty({
        text: t,
        type: e,
        animation: {
            open: "animated fadeInUp",
            close: "animated fadeOutDown",
            easing: "swing",
            speed: 500
        },
        dismissQueue: !0,
        maxVisible: 5,
        timeout: 5e3,
        layout: "topLeft"
    })
}
jQuery.noConflict(), jQuery(document).ready(function() {
    "use strict";
    var t = jQuery(".comment-body .comment-reply-link");
    t.addClass("button-small"), jQuery(".shadowbox").fancybox()
}), 0 !== jQuery("#foo5").length && (jQuery.noConflict(), jQuery(function() {
    jQuery("#foo5").carouFredSel({
        width: "100%",
        prev: "#prev3",
        next: "#next3",
        scroll: 2
    })
})), jQuery(document).ready(function() {
    "use strict";
    var t = jQuery("ul.sub-menu");
    t.parent().addClass("dropdown"), t.addClass("dropdown-menu");
    var e = jQuery("dropdown-menu.children");
    e.parent().addClass("dropdown"), e.addClass("dropdown-menu");
    var r = jQuery("dropdown-menu.children");
    r.parent().addClass("dropdown");
    var o = jQuery(".menu ul");
    o.parent().addClass("dropdown");
    var i = jQuery(".dropdown .dropdown-menu");
    i.removeClass("children");
    var n = jQuery(".dropdown > a br");
    n.before('<b class="caret"></b>'), r.hover(function() {
        jQuery(this).parent().addClass("active")
    }, function() {
        jQuery(this).parent().removeClass("active")
    }), t.hover(function() {
        jQuery(this).parent().addClass("active")
    }, function() {
        jQuery(this).parent().removeClass("active")
    });
    var a = jQuery(".menu ul");
    a.addClass("nav"), e.removeClass("nav")
}), jQuery.noConflict(), jQuery(document).ready(function() {
    "use strict";
    var t = jQuery("#searchform #searchsubmit");
    t.remove();
    var e = jQuery("#searchform .screen-reader-text");
    e.remove();
    var r = jQuery("#searchform div");
    r.append('<input type="hidden" value="post" name="post_type">');
    var o = jQuery("#searchform div h3");
    o.remove()
}), jQuery.noConflict(), jQuery(function() {
    "use strict";
    jQuery("[data-toggle=tooltip]").tooltip()
}), jQuery.noConflict();
var wgtitle = jQuery(".widget .title-wrapper");
wgtitle.each(function() {
    "use strict";
    var t = jQuery('.widget ul li .bbp-forum-title[href*="topic"]'),
        e = jQuery('.widget ul li .bbp-forum-title:not([href*="topic"])');
    t.prepend('<i class="icon-comment"></i>'), e.prepend('<i class="icon-comments"></i>');
    var r = jQuery('.footer_widget ul li .bbp-forum-title[href*="topic"]'),
        o = jQuery('.footer_widget ul li .bbp-forum-title:not([href*="topic"])');
    r.prepend('<i class="icon-comment"></i>'), o.prepend('<i class="icon-comments"></i>')
});
var blog = jQuery(".blog.normal-page");
if (0 !== blog.length && (jQuery.Isotope.prototype._getMasonryGutterColumns = function() {
    var t = this.options.masonry && this.options.masonry.gutterWidth || 0;
    containerWidth = this.element.width(), this.masonry.columnWidth = this.options.masonry && this.options.masonry.columnWidth || this.jQueryfilteredAtoms.outerWidth(!0) || containerWidth, this.masonry.columnWidth += t, this.masonry.cols = Math.floor((containerWidth + t) / this.masonry.columnWidth), this.masonry.cols = Math.max(this.masonry.cols, 1)
}, jQuery.Isotope.prototype._masonryReset = function() {
    this.masonry = {}, this._getMasonryGutterColumns();
    var t = this.masonry.cols;
    for (this.masonry.colYs = []; t--;) this.masonry.colYs.push(0)
}, jQuery.Isotope.prototype._masonryResizeChanged = function() {
    var t = this.masonry.cols;
    return this._getMasonryGutterColumns(), this.masonry.cols !== t
}, jQuery.isFunction(jQuery.fn.imagesLoaded))) {
    var containerblog = jQuery(".isoblog");
    containerblog.imagesLoaded(function() {
        containerblog.isotope({
            layoutMode: "masonry",
            resizable: !1,
            masonry: {
                columnWidth: (containerblog.width() - 40) / 3,
                gutterWidth: 20
            }
        })
    }), jQuery(".cat a").click(function() {
        var t = jQuery(this).attr("href");
        return containerblog.isotope({
            filter: t
        }), !1
    }), jQuery(window).smartresize(function() {
        setWidthsBlog(), containerblog.isotope({
            masonry: {
                columnWidth: getUnitWidthBlog(),
                gutterWidth: 20
            }
        })
    }).resize()
}
var timeout, productToAdd;
jQuery(".product-wrap .add_to_cart_button").click(function() {
    productToAdd = jQuery(this).parents("li").find("h3").text(), jQuery(".container .cart-notification span.item-name").html(productToAdd)
}), jQuery(".container .cart-notification").hover(function() {
    jQuery(this).fadeOut(400), jQuery(".container .widget_shopping_cart").stop(!0, !0).fadeIn(400), jQuery(".container .cart_list").stop(!0, !0).fadeIn(400), clearTimeout(timeout)
}), jQuery(".container div.cart-outer").hover(function() {
    jQuery(".container .widget_shopping_cart").stop(!0, !0).fadeIn(400), jQuery(".container .cart_list").stop(!0, !0).fadeIn(400), clearTimeout(timeout), jQuery(".container .cart-notification").fadeOut(300)
}, function() {
    jQuery(".container .widget_shopping_cart").stop(!0, !0).fadeOut(300), jQuery(".container .cart_list").stop(!0, !0).fadeOut(300)
}), jQuery("body").bind("added_to_cart", shopping_cart_dropdown_show), jQuery("body").bind("added_to_cart", shopping_cart_dropdown), setTimeout(shopping_cart_dropdown, 550);
var mcontainer = jQuery(".mcontainer script");
mcontainer.unwrap();
var bbip = jQuery(".bbp-author-ip");
bbip.remove();
var ticker = jQuery("#webticker");
ticker.webTicker();
jQuery(document).ready(function() {
    "use strict";
    var t = jQuery(".login-tooltip"),
        e = jQuery(".login-btn"),
        r = jQuery("#login_tooltip .closeto"),
        o = jQuery(".navbar-wrapper");
    e.click(function() {
        t.fadeTo("fast", 1, function() {
            jQuery(this).css("top", "50%"), o.css("z-index", "2147483647")
        })
    }), r.on("click", function() {
        t.fadeTo("fast", 0, function() {
            jQuery(this).css("top", "-5000px"), o.css("z-index", "99999999")
        })
    })
}), jQuery(document).ready(function() {
    "use strict";
    var t = jQuery(".tab-inner");
    if (0 !== t.length) {
        var e = jQuery('.tab-inner a[href="#tab-1"]').parent().index();
        t.tabs().tabs("option", "active", e)
    }
}), jQuery(document).ready(function() {
    "use strict";
    var t = jQuery(".add-round"),
        e = jQuery(".submit-score");
    t.remove(), e.bind("click", function() {
        var t = jQuery(".remove");
        t.remove()
    })
}), jQuery(document).ready(function() {
    "use strict";
    jQuery("a.back-to-top").click(function() {
        jQuery("html, body").animate({
            scrollTop: 0
        }, 800)
    })
}), jQuery(document).ready(function() {
    "use strict";
    var t = jQuery("#members-dir-search"),
        e = jQuery("#subnav");
    e.before(t)
}), jQuery(document).ready(function(t) {
    "use strict";
    var e = t("#contactForm");
    e.validate();

    var online = jQuery('.footer_widget .widget-error');
    online.addClass('avatar-block').removeClass('widget-error');

    jQuery('.limit_to_numbers').keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if (jQuery.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) ||
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
    jQuery('.limit_to_url').focusout(function() {
    	var container = jQuery(this).val();
    	if (container.length > 0) {
    		if (!isUrlValid(container)) {
    			jQuery(this).css('border-color', '#ff0000');

	    	} else {
	    		jQuery(this).css('border-color', '');
	    	}
    	}
    });

});



function isUrlValid(url) {
    return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
}

jQuery(document).ready(function() {
var edit_image = jQuery('.edit-attachment');
var samaslika = jQuery('.js--select-attachment');
samaslika.on( "click", function() {
edit_image.remove();
});

jQuery("a.ajaxloadblock").one("click", function() {
    jQuery(this).click(function () { return false; });
});

})