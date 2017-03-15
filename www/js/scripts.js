function readMore() {

    // Grab all the excerpt class

        // Run formatWord function and specify the length of words display to viewer
    $('.dek').html(formatWords($('.dek').html(), 38));

        // Hide the extra words
        //$('.dek').children('span').hide();
    
    // Apply click event to read more link
    $('.more_link').click(function () {

        // Grab the hidden span and anchor
        var more_text = $(this).closest(".dek").children('span.more_text');
        var ellipsis = $(this).closest(".dek").children('span.ellipsis');
        var more_link = $(this).closest(".dek").children('a.more_link');
            
        // Toggle visibility using hasClass
        // I know you can use is(':visible') but it doesn't work in IE8 somehow...
        if (more_text.hasClass('hide')) {
            more_text.show();
            ellipsis.hide();
            more_link.html(' Read less');       
            more_text.removeClass('hide');
        } else {
            more_text.hide();
            ellipsis.show();
            more_link.html(' Read more');           
            more_text.addClass('hide');
        }

        return false;
    
    });
}   

// Accept a paragraph and return a formatted paragraph with additional html tags
function formatWords(sentence, show) {

    // split all the words and store it in an array
    var words = sentence.split(' ');
  // var words = sentence;
    var new_sentence = '';

    // loop through each word
    for (i = 0; i < words.length; i++) {

        // process words that will visible to viewer
        if (i <= show) {
            new_sentence += words[i] + ' ';
            
        // process the rest of the words
        } else {
    
            // add a span at start
            if (i == (show + 1)) new_sentence += '<span class="ellipsis">... </span><span class="more_text hide">';     

            new_sentence += words[i] + ' ';
        
            // close the span tag and add read more link in the very end
            if (words[i+1] == null) new_sentence += '</span><a href="#" class="more_link"> Read more</a>';
        }       
    } 

    return new_sentence;

}

//readMore();

$(document).ready(function(){

        var height1 = $(".rel_content2 img").width()*2.5/3;
        var height2 = $(".rel_content3 img").width()*2.5/3;
        var video_height = $(".videoWrapper").width()*1.8/3;

        $(".rel_content2 img").css("height",height1);
        $(".rel_content3 img").css("height",height2);
        $(".videoWrapper").css("height",video_height);
});

$(window).resize(function(){
    var height1 = $(".rel_content2 img").width()*2.5/3;
    var height2 = $(".rel_content3 img").width()*2.5/3;
    var video_height = $(".videoWrapper").width()*1.8/3;

    $(".rel_content2 img").css("height",height1);
    $(".rel_content3 img").css("height",height2);
    $(".videoWrapper").css("height",video_height);
})

$(document).ready(function(){}),
       
    function() {
        var e, t;
        e = this.jQuery || window.jQuery, t = e(window), e.fn.stick_in_parent = function(o) {
            var n, i, s, r, a, c, l, d, g, w;
            for (null == o && (o = {}), l = o.sticky_class, i = o.inner_scrolling, c = o.recalc_every, a = o.parent, r = o.offset_top, s = o.spacer, n = o.bottoming, null == r && (r = 0), null == a && (a = void 0), null == i && (i = !0), null == l && (l = "is_stuck"), null == n && (n = !0), d = function(o, d, g, w, p, u, f, h) {
                    var m, v, b, y, k, T, _, M, $, x, S;
                    if (!o.data("sticky_kit")) {
                        if (o.data("sticky_kit", !0), T = o.parent(), null != a && (T = T.closest(a)), !T.length) throw "failed to find stick parent";
                        if (m = b = !1, (x = null != s ? s && o.closest(s) : e("<div />")) && x.css("position", o.css("position")), _ = function() {
                                var e, t, n;
                                return !h && (e = parseInt(T.css("border-top-width"), 10), t = parseInt(T.css("padding-top"), 10), d = parseInt(T.css("padding-bottom"), 10), g = T.offset().top + e + t, w = T.height(), b && (m = b = !1, null == s && (o.insertAfter(x), x.detach()), o.css({
                                    position: "",
                                    top: "",
                                    width: "",
                                    bottom: ""
                                }).removeClass(l), n = !0), p = o.offset().top - parseInt(o.css("margin-top"), 10) - r, u = o.outerHeight(!0), f = o.css("float"), x && x.css({
                                    width: o.outerWidth(!0),
                                    height: u,
                                    display: o.css("display"),
                                    "vertical-align": o.css("vertical-align"),
                                    "float": f
                                }), n) ? S() : void 0
                            }, _(), u !== w) return y = void 0, k = r, $ = c, S = function() {
                            var e, a, v, M;
                            return !h && (null != $ && (--$, 0 >= $ && ($ = c, _())), v = t.scrollTop(), null != y && (a = v - y), y = v, b ? (n && (M = v + u + k > w + g, m && !M && (m = !1, o.css({
                                position: "fixed",
                                bottom: "",
                                top: k
                            }).trigger("sticky_kit:unbottom"))), p > v && (b = !1, k = r, null == s && ("left" !== f && "right" !== f || o.insertAfter(x), x.detach()), e = {
                                position: "",
                                width: "",
                                top: ""
                            }, o.css(e).removeClass(l).trigger("sticky_kit:unstick")), i && (e = t.height(), u + r > e && !m && (k -= a, k = Math.max(e - u, k), k = Math.min(r, k), b && o.css({
                                top: k + "px"
                            })))) : v > p && (b = !0, e = {
                                position: "fixed",
                                top: k
                            }, e.width = "border-box" === o.css("box-sizing") ? o.outerWidth() + "px" : o.width() + "px", o.css(e).addClass(l), null == s && (o.after(x), "left" !== f && "right" !== f || x.append(o)), o.trigger("sticky_kit:stick")), b && n && (null == M && (M = v + u + k > w + g), !m && M)) ? (m = !0, "static" === T.css("position") && T.css({
                                position: "relative"
                            }), o.css({
                                position: "absolute",
                                bottom: d,
                                top: "auto"
                            }).trigger("sticky_kit:bottom")) : void 0
                        }, M = function() {
                            return _(), S()
                        }, v = function() {
                            return h = !0, t.off("touchmove", S), t.off("scroll", S), t.off("resize", M), e(document.body).off("sticky_kit:recalc", M), o.off("sticky_kit:detach", v), o.removeData("sticky_kit"), o.css({
                                position: "",
                                bottom: "",
                                top: "",
                                width: ""
                            }), T.position("position", ""), b ? (null == s && ("left" !== f && "right" !== f || o.insertAfter(x), x.remove()), o.removeClass(l)) : void 0
                        }, t.on("touchmove", S), t.on("scroll", S), t.on("resize", M), e(document.body).on("sticky_kit:recalc", M), o.on("sticky_kit:detach", v), setTimeout(S, 0)
                    }
                }, g = 0, w = this.length; w > g; g++) o = this[g], d(e(o));
            return this
        }
    }.call(this), $("#nydn-header").stick_in_parent({
        bottoming: !1,
        offset_top: 0
    });
    
    $(document).ready(function() { }), 
    function() {
        var e, t;
        e = this.jQuery || window.jQuery, t = e(window), e.fn.stick_in_parent = function(o) {
            var n, i, s, r, a, c, l, d, g, w;
            for (null == o && (o = {}), l = o.sticky_class, i = o.inner_scrolling, c = o.recalc_every, a = o.parent, r = o.offset_top, s = o.spacer, n = o.bottoming, null == r && (r = 0), null == a && (a = void 0), null == i && (i = !0), null == l && (l = "is_stuck"), null == n && (n = !0), d = function(o, d, g, w, p, u, f, h) {
                    var m, v, b, y, k, T, _, M, $, x, S;
                    if (!o.data("sticky_kit")) {
                        if (o.data("sticky_kit", !0), T = o.parent(), null != a && (T = T.closest(a)), !T.length) throw "failed to find stick parent";
                        if (m = b = !1, (x = null != s ? s && o.closest(s) : e("<div />")) && x.css("position", o.css("position")), _ = function() {
                                var e, t, n;
                                return !h && (e = parseInt(T.css("border-top-width"), 10), t = parseInt(T.css("padding-top"), 10), d = parseInt(T.css("padding-bottom"), 10), g = T.offset().top + e + t, w = T.height(), b && (m = b = !1, null == s && (o.insertAfter(x), x.detach()), o.css({
                                    position: "",
                                    top: "",
                                    width: "",
                                    bottom: ""
                                }).removeClass(l), n = !0), p = o.offset().top - parseInt(o.css("margin-top"), 10) - r, u = o.outerHeight(!0), f = o.css("float"), x && x.css({
                                    width: o.outerWidth(!0),
                                    height: u,
                                    display: o.css("display"),
                                    "vertical-align": o.css("vertical-align"),
                                    "float": f
                                }), n) ? S() : void 0
                            }, _(), u !== w) return y = void 0, k = r, $ = c, S = function() {
                            var e, a, v, M;
                            return !h && (null != $ && (--$, 0 >= $ && ($ = c, _())), v = t.scrollTop(), null != y && (a = v - y), y = v, b ? (n && (M = v + u + k > w + g, m && !M && (m = !1, o.css({
                                position: "fixed",
                                bottom: "",
                                top: k
                            }).trigger("sticky_kit:unbottom"))), p > v && (b = !1, k = r, null == s && ("left" !== f && "right" !== f || o.insertAfter(x), x.detach()), e = {
                                position: "",
                                width: "",
                                top: ""
                            }, o.css(e).removeClass(l).trigger("sticky_kit:unstick")), i && (e = t.height(), u + r > e && !m && (k -= a, k = Math.max(e - u, k), k = Math.min(r, k), b && o.css({
                                top: k + "px"
                            })))) : v > p && (b = !0, e = {
                                position: "fixed",
                                top: k
                            }, e.width = "border-box" === o.css("box-sizing") ? o.outerWidth() + "px" : o.width() + "px", o.css(e).addClass(l), null == s && (o.after(x), "left" !== f && "right" !== f || x.append(o)), o.trigger("sticky_kit:stick")), b && n && (null == M && (M = v + u + k > w + g), !m && M)) ? (m = !0, "static" === T.css("position") && T.css({
                                position: "relative"
                            }), o.css({
                                position: "absolute",
                                bottom: d,
                                top: "auto"
                            }).trigger("sticky_kit:bottom")) : void 0
                        }, M = function() {
                            return _(), S()
                        }, v = function() {
                            return h = !0, t.off("touchmove", S), t.off("scroll", S), t.off("resize", M), e(document.body).off("sticky_kit:recalc", M), o.off("sticky_kit:detach", v), o.removeData("sticky_kit"), o.css({
                                position: "",
                                bottom: "",
                                top: "",
                                width: ""
                            }), T.position("position", ""), b ? (null == s && ("left" !== f && "right" !== f || o.insertAfter(x), x.remove()), o.removeClass(l)) : void 0
                        }, t.on("touchmove", S), t.on("scroll", S), t.on("resize", M), e(document.body).on("sticky_kit:recalc", M), o.on("sticky_kit:detach", v), setTimeout(S, 0)
                    }
                }, g = 0, w = this.length; w > g; g++) o = this[g], d(e(o));
            return this
        }
    }.call(this), $("#social").stick_in_parent({
        bottoming: !1,
        offset_top: 52
    });
