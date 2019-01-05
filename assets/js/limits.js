!function(t) {
    function e(o) {
        if (n[o])
            return n[o].exports;
        var r = n[o] = {
            i: o,
            l: !1,
            exports: {}
        };
        return t[o].call(r.exports, r, r.exports, e), r.l = !0, r.exports
    }
    var n = {};
    e.m = t, e.c = n, e.d = function(t, n, o) {
        e.o(t, n) || Object.defineProperty(t, n, {
            configurable: !1,
            enumerable: !0,
            get: o
        })
    }, e.n = function(t) {
        var n = t && t.__esModule ? function() {
            return t.default
        } : function() {
            return t
        };
        return e.d(n, "a", n), n
    }, e.o = function(t, e) {
        return Object.prototype.hasOwnProperty.call(t, e)
    }, e.p = "", e(e.s = 3)
}([function(t, e, n) {
    "use strict";
    function o(t, e) {
        if (null !== t && void 0 !== t)
            if ("string" == typeof t || "number" == typeof t)
                e.push(t.toString());
            else if (Array.isArray(t))
                for (var n = 0; n < t.length; n++)
                    o(t[n], e);
            else
                e.push(t)
    }
    function r(t, e) {
        for (var n = [], r = arguments.length, a = Array(r > 2 ? r - 2 : 0), c = 2; c < r; c++)
            a[c - 2] = arguments[c];
        return o(a, n), "function" == typeof t ? t(i({}, e, {
            children: n
        })) : {
            tag: t,
            props: e,
            children: n
        }
    }
    e.a = r;
    var i = Object.assign || function(t) {
        for (var e = 1; e < arguments.length; e++) {
            var n = arguments[e];
            for (var o in n)
                Object.prototype.hasOwnProperty.call(n, o) && (t[o] = n[o])
        }
        return t
    }
}, function(t, e, n) {
    "use strict";
    function o(t) {
        for (var e = t.data, n = void 0 === e ? [] : e, o = t.onClick, l = void 0 === o ? i.c : o, f = t.colorFun, p = void 0 === f ? i.e : f, d = t.startDate, h = void 0 === d ? Object(i.d)() : d, g = t.endDate, v = void 0 === g ? Object(i.f)() : g, y = t.size, b = void 0 === y ? 12 : y, m = t.space, O = void 0 === m ? 1 : m, j = t.padX, x = void 0 === j ? 20 : j, w = t.padY, k = void 0 === w ? 20 : w, S = t.styleOptions, C = void 0 === S ? {} : S, E = [], P = Object(i.a)(h, v), D = n.reduce(function(t, e) {
            return t[e.date] = e.count, t
        }, {}), B = 0, M = 0; M <= P; M += 1) {
            var F = new Date(h);
            F.setDate(F.getDate() + M);
            var T = F.getDay(),
                A = D[Object(i.b)(F)] || 0;
            (0 === T && 0 !== M || 0 === M) && (E.push([]), B += 1), E[B - 1].push({
                count: A,
                date: F,
                day: T
            })
        }
        var Y = b + 2 * O,
            R = B * Y + 2 * x,
            z = 7 * Y + k + 10,
            X = "0 0 " + R + " " + z,
            $ = Object(s.a)(C),
            L = {
                styles: $,
                values: E,
                size: b,
                space: O,
                colorFun: p,
                padX: x,
                padY: k,
                onClick: l
            };
        return Object(r.a)("svg", {
            width: R,
            height: z,
            viewBox: X
        }, Object(r.a)("rect", {
            x: 0,
            y: 0,
            width: R,
            height: z,
            fill: "#fff"
        }), Object(r.a)(a.a, L), Object(r.a)(c.a, L), Object(r.a)(u.a, L))
    }
    e.a = o;
    var r = n(0),
        i = n(2),
        a = n(6),
        c = n(7),
        u = n(8),
        s = n(9)
}, function(t, e, n) {
    "use strict";
    function o(t, e) {
        var n = Date.UTC(t.getFullYear(), t.getMonth(), t.getDate()),
            o = Date.UTC(e.getFullYear(), e.getMonth(), e.getDate());
        return Math.floor((o - n) / l)
    }
    function r() {}
    function i() {
        var t = new Date;
        return t.setHours(0, 0, 0, 0, 0), t
    }
    function a() {
        var t = i();
        return t.setFullYear(t.getFullYear() - 1), t
    }
    function c(t) {
        return t.count ? t.count > 45 ? f[4] : t.count > 30 ? f[3] : t.count > 15 ? f[2] : f[1] : f[0]
    }
    function u(t) {
        return t > 9 ? "" + t : "0" + t
    }
    function s(t) {
        var e = t.getFullYear(),
            n = t.getMonth() + 1,
            o = t.getDate();
        return e + "-" + u(n) + "-" + u(o)
    }
    e.a = o, e.c = r, e.f = i, e.d = a, e.e = c, e.b = s;
    var l = 864e5,
        f = ["#eee", "#c6e48b", "#7bc96f", "#239a3b", "#196127"]
}, function(t, e, n) {
    "use strict";
    function o() {
        return Math.floor(50 * Math.random())
    }
    function r(t) {
        var e = /(>)\s*(<)(\/*)/g,
            n = / *(.*) +\n/g,
            o = /(<.+>)(.+\n)/g;
        t = t.replace(e, "$1\n$2$3").replace(n, "$1\n").replace(o, "$1\n$2");
        for (var r = "", i = t.split("\n"), a = 0, c = "other", u = {
            "single->single": 0,
            "single->closing": -1,
            "single->opening": 0,
            "single->other": 0,
            "closing->single": 0,
            "closing->closing": -1,
            "closing->opening": 0,
            "closing->other": 0,
            "opening->single": 1,
            "opening->closing": 0,
            "opening->opening": 1,
            "opening->other": 1,
            "other->single": 0,
            "other->closing": -1,
            "other->opening": 0,
            "other->other": 0
        }, s = 0; s < i.length; s++) {
            var l = i[s];
            if (l.match(/\s*<\?xml/))
                r += l + "\n";
            else {
                var f = Boolean(l.match(/<.+\/>/)),
                    p = Boolean(l.match(/<\/.+>/)),
                    d = Boolean(l.match(/<[^!].*>/)),
                    h = f ? "single" : p ? "closing" : d ? "opening" : "other",
                    g = c + "->" + h;
                c = h;
                var v = "";
                a += u[g];
                for (var y = 0; y < a; y++)
                    v += "  ";
                "opening->closing" == g ? r = r.substr(0, r.length - 1) + l + "\n" : r += v + l + "\n"
            }
        }
        return r
    }
    Object.defineProperty(e, "__esModule", {
        value: !0
    });
    var i = n(4),
        a = n(2),
        c = function() {
            for (var t = Object(a.f)(), e = Object(a.d)(), n = Object(a.a)(e, t), r = [], i = 0; i <= n; i++) {
                var c = new Date(e);
                c.setDate(c.getDate() + i);
                var u = Object(a.b)(c),
                    s = o();
                r.push({
                    date: u,
                    count: s
                })
            }
            return r
        }(),
        u = {
            onClick: function(t) {
                console.log(t)
            }
        },
        s = (new i.b("#svg", c, u), new i.a("#canvas", c, u), new i.c(c, u));
    !function() {
        document.querySelector("#str").textContent = r(s.render())
    }(), function() {
        for (var t = document.getElementById("tooltip"), e = document.getElementsByClassName("cg-day"), n = function(e) {
            e = e || window.event;
            var n = e.target || e.srcElement,
                o = n.getBoundingClientRect(),
                r = n.getAttribute("data-count"),
                i = n.getAttribute("data-date");
            t.style.display = "block", t.textContent = r + " contributions on " + i;
            var a = t.getBoundingClientRect().width;
            t.style.left = o.left - a / 2 + 6 + "px", t.style.top = o.top - 35 + "px"
        }, o = function(e) {
            e = e || window.event, t.style.display = "none"
        }, r = 0; r < e.length; r++)
            document.body.addEventListener ? (e[r].addEventListener("mouseover", n, !1), e[r].addEventListener("mouseout", o, !1)) : (e[r].attachEvent("onmouseover", n), e[r].attachEvent("onmouseout", o))
    }()
}, function(t, e, n) {
    "use strict";
    var o = n(5),
        r = n(11),
        i = n(14);
    n.d(e, "b", function() {
        return o.a
    }), n.d(e, "a", function() {
        return r.a
    }), n.d(e, "c", function() {
        return i.a
    });
    r.a
}, function(t, e, n) {
    "use strict";
    function o(t, e) {
        if (!(t instanceof e))
            throw new TypeError("Cannot call a class as a function")
    }
    var r = n(0),
        i = n(1),
        a = n(10),
        c = Object.assign || function(t) {
            for (var e = 1; e < arguments.length; e++) {
                var n = arguments[e];
                for (var o in n)
                    Object.prototype.hasOwnProperty.call(n, o) && (t[o] = n[o])
            }
            return t
        },
        u = function() {
            function t(t, e) {
                for (var n = 0; n < e.length; n++) {
                    var o = e[n];
                    o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(t, o.key, o)
                }
            }
            return function(e, n, o) {
                return n && t(e.prototype, n), o && t(e, o), e
            }
        }(),
        s = function() {
            function t(e, n) {
                var r = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : {};
                o(this, t), this.dom = "string" == typeof e ? document.querySelector(e) : e, this.data = n, this.options = r, this.render()
            }
            return u(t, [{
                key: "setData",
                value: function(t) {
                    this.data = t, this.render()
                }
            }, {
                key: "setOptions",
                value: function(t) {
                    this.options = c({}, this.options, t), this.render()
                }
            }, {
                key: "render",
                value: function() {
                    var t = this.data,
                        e = this.options;
                    this.tree && this.dom.removeChild(this.tree), this.tree = Object(a.a)(Object(r.a)(i.a, c({
                        data: t
                    }, e))), this.dom.appendChild(this.tree)
                }
            }]), t
        }();
    e.a = s
}, function(t, e, n) {
    "use strict";
    function o(t) {
        var e = t.values,
            n = t.size,
            o = t.space,
            a = t.padX,
            c = t.padY,
            u = t.colorFun,
            s = t.onClick;
        return Object(r.a)("g", null, e.map(function(t, e) {
            var l = n + 2 * o,
                f = a + e * l + o,
                p = c + o;
            return Object(r.a)("g", null, t.map(function(t) {
                return Object(r.a)("rect", {
                    class: "cg-day",
                    x: f,
                    y: t.day * l + p,
                    width: n,
                    height: n,
                    fill: u(t),
                    "data-count": t.count,
                    "data-date": Object(i.b)(t.date),
                    onClick: function() {
                        return s(t)
                    }
                })
            }))
        }))
    }
    n.d(e, "a", function() {
        return o
    });
    var r = n(0),
        i = n(2)
}, function(t, e, n) {
    "use strict";
    function o(t) {
        var e = t.styles,
            n = t.values,
            o = t.size,
            a = t.space,
            c = t.padX,
            u = t.padY,
            s = o + 2 * a,
            l = 2 * s,
            f = [];
        return n.forEach(function(t, e) {
            t.forEach(function(t, n) {
                if (0 === n && 0 === t.day) {
                    var o = t.date.getMonth(),
                        r = e * s + c + a,
                        i = f.slice(-1).pop();
                    (!i || o !== i.month && r - i.x > l) && f.push({
                        month: o,
                        x: r
                    })
                }
            })
        }), Object(r.a)("g", null, f.map(function(t, n) {
            return Object(r.a)("text", {
                key: n,
                x: t.x,
                y: u / 2,
                style: e.text
            }, i[t.month])
        }))
    }
    e.a = o;
    var r = n(0),
        i = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]
}, function(t, e, n) {
    "use strict";
    function o(t) {
        var e = t.styles,
            n = t.size,
            o = t.space,
            i = t.padX,
            a = t.padY,
            c = n + 2 * o,
            u = c / 2,
            s = [{
                v: "M",
                y: a + 1 * c + u
            }, {
                v: "W",
                y: a + 3 * c + u
            }, {
                v: "F",
                y: a + 5 * c + u
            }];
        return Object(r.a)("g", null, s.map(function(t, n) {
            return Object(r.a)("text", {
                key: n,
                x: i / 2,
                y: t.y,
                style: e.text2
            }, t.v)
        }))
    }
    e.a = o;
    var r = n(0)
}, function(t, e, n) {
    "use strict";
    function o(t) {
        var e = t.textColor,
            n = void 0 === e ? "#959494" : e,
            o = t.fontSize,
            c = void 0 === o ? i : o,
            u = t.fontFamily,
            s = void 0 === u ? a : u,
            l = {
                fill: n,
                "font-size": c,
                "font-family": s,
                "dominant-baseline": "central"
            };
        return {
            text: l,
            text2: r({}, l, {
                "text-anchor": "middle"
            })
        }
    }
    e.a = o;
    var r = Object.assign || function(t) {
            for (var e = 1; e < arguments.length; e++) {
                var n = arguments[e];
                for (var o in n)
                    Object.prototype.hasOwnProperty.call(n, o) && (t[o] = n[o])
            }
            return t
        },
        i = "12px",
        a = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif'
}, function(t, e, n) {
    "use strict";
    function o(t, e) {
        Object.keys(e).forEach(function(n) {
            var o = e[n];
            "style" === n && "object" === (void 0 === o ? "undefined" : i(o)) ? Object.keys(o).forEach(function(e) {
                t.style[e] = o[e]
            }) : "onClick" === n ? "function" == typeof o && t.addEventListener("click", o) : t.setAttribute(n, o)
        })
    }
    function r(t, e) {
        var n = t.tag,
            i = t.props,
            u = t.children,
            s = c.createElementNS(a, n);
        return i && o(s, i), u.forEach(function(t) {
            s.appendChild("string" == typeof t ? c.createTextNode(t) : r(t, e))
        }), s
    }
    e.a = r;
    var i = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(t) {
            return typeof t
        } : function(t) {
            return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
        },
        a = "http://www.w3.org/2000/svg",
        c = document
}, function(t, e, n) {
    "use strict";
    function o(t, e) {
        if (!(t instanceof e))
            throw new TypeError("Cannot call a class as a function")
    }
    var r = n(0),
        i = n(1),
        a = n(12),
        c = n(13),
        u = Object.assign || function(t) {
            for (var e = 1; e < arguments.length; e++) {
                var n = arguments[e];
                for (var o in n)
                    Object.prototype.hasOwnProperty.call(n, o) && (t[o] = n[o])
            }
            return t
        },
        s = function() {
            function t(t, e) {
                for (var n = 0; n < e.length; n++) {
                    var o = e[n];
                    o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(t, o.key, o)
                }
            }
            return function(e, n, o) {
                return n && t(e.prototype, n), o && t(e, o), e
            }
        }(),
        l = function() {
            function t(e, n) {
                var r = this,
                    i = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : {};
                o(this, t), this.ctx = Object(c.a)(e), this.data = n, this.options = i, this.render(), this.ctx.onClick = function(t) {
                    return r.render(t)
                }
            }
            return s(t, [{
                key: "setData",
                value: function(t) {
                    this.data = t, this.render()
                }
            }, {
                key: "setOptions",
                value: function(t) {
                    this.options = u({}, this.options, t), this.render()
                }
            }, {
                key: "render",
                value: function(t) {
                    var e = this.data,
                        n = this.options;
                    Object(a.a)(Object(r.a)(i.a, u({
                        data: e
                    }, n)), this.ctx, t)
                }
            }]), t
        }();
    e.a = l
}, function(t, e, n) {
    "use strict";
    function o(t, e, n) {
        var r = t.tag,
            i = t.props,
            a = t.children;
        if ("svg" === r) {
            var c = i.width,
                u = i.height;
            e.width = c, e.height = u
        }
        if ("rect" === r) {
            var s = i.x,
                l = i.y,
                f = i.width,
                p = i.height,
                d = i.fill,
                h = i.onClick;
            e.beginPath(), e.moveTo(s, l), e.lineTo(s + f, l), e.lineTo(s + f, l + p), e.lineTo(s, l + p), e.lineTo(s, l), n && h && e.isPointInPath(n.x, n.y) && h(), e.closePath(), d && (e.fillStyle = d), e.fill()
        }
        if ("text" === r) {
            var g = i.x,
                v = i.y,
                y = i.style;
            if (y) {
                e.fillStyle = y.fill;
                var b = {
                        central: "middle",
                        middle: "middle",
                        hanging: "hanging",
                        alphabetic: "alphabetic",
                        ideographic: "ideographic"
                    },
                    m = {
                        start: "start",
                        middle: "center",
                        end: "end"
                    };
                e.textBaseline = b[y["dominant-baseline"]] || "alphabetic", e.textAlign = m[y["text-anchor"]] || "start", e.font = (y["font-weight"] || "") + " " + y["font-size"] + " " + y["font-family"]
            }
            e.fillText(a.join(""), g, v)
        }
        a.forEach(function(t) {
            "string" != typeof t && o(t, e, n)
        })
    }
    e.a = o
}, function(t, e, n) {
    "use strict";
    function o(t) {
        var e = "string" == typeof t ? document.querySelector(t) : t,
            n = e.getContext("2d"),
            o = n.webkitBackingStorePixelRatio || n.mozBackingStorePixelRatio || n.msBackingStorePixelRatio || n.oBackingStorePixelRatio || n.backingStorePixelRatio || 1,
            r = (window.devicePixelRatio || 1) / o;
        return ["width", "height"].forEach(function(t) {
            Object.defineProperty(n, t, {
                get: function() {
                    return e[t] / r
                },
                set: function(o) {
                    e[t] = o * r, e.style[t] = o + "px", n.scale(r, r)
                },
                enumerable: !0,
                configurable: !0
            })
        }), e.addEventListener("click", function(t) {
            if (n.onClick) {
                var o = e.getBoundingClientRect();
                n.onClick({
                    x: (t.clientX - o.left) * r,
                    y: (t.clientY - o.top) * r
                })
            }
        }), n
    }
    e.a = o
}, function(t, e, n) {
    "use strict";
    function o(t, e) {
        if (!(t instanceof e))
            throw new TypeError("Cannot call a class as a function")
    }
    var r = n(0),
        i = n(1),
        a = n(15),
        c = Object.assign || function(t) {
            for (var e = 1; e < arguments.length; e++) {
                var n = arguments[e];
                for (var o in n)
                    Object.prototype.hasOwnProperty.call(n, o) && (t[o] = n[o])
            }
            return t
        },
        u = function() {
            function t(t, e) {
                for (var n = 0; n < e.length; n++) {
                    var o = e[n];
                    o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(t, o.key, o)
                }
            }
            return function(e, n, o) {
                return n && t(e.prototype, n), o && t(e, o), e
            }
        }(),
        s = function() {
            function t(e) {
                var n = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {};
                o(this, t), this.data = e, this.options = n
            }
            return u(t, [{
                key: "setData",
                value: function(t) {
                    this.data = t
                }
            }, {
                key: "setOptions",
                value: function(t) {
                    this.options = c({}, this.options, t)
                }
            }, {
                key: "render",
                value: function() {
                    var t = this.data,
                        e = this.options;
                    return Object(a.a)(Object(r.a)(i.a, c({
                        data: t
                    }, e)))
                }
            }]), t
        }();
    e.a = s
}, function(t, e, n) {
    "use strict";
    function o(t) {
        return String(t).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/"/g, "&quot;").replace(/\t/g, "&#x9;").replace(/\n/g, "&#xA;").replace(/\r/g, "&#xD;")
    }
    function r(t) {
        return String(t).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/\r/g, "&#xD;")
    }
    function i(t, e) {
        var n = t.tag,
            c = t.props,
            u = t.children,
            s = [];
        return s.push("<" + n), Object.keys(c || {}).forEach(function(t) {
            var e = c[t];
            "onClick" !== t && ("style" === t && "object" === (void 0 === e ? "undefined" : a(e)) && (e = Object.keys(e).map(function(t) {
                return t + ":" + e[t] + ";"
            }).join("")), s.push(" " + t + '="' + o(e) + '"'))
        }), u && u.length ? (s.push(">"), u.forEach(function(t) {
            "string" == typeof t ? s.push(r(t)) : s.push(i(t, e))
        }), s.push("</" + n + ">"), s.join("")) : (s.push(" />"), s.join(""))
    }
    e.a = i;
    var a = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(t) {
        return typeof t
    } : function(t) {
        return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
    }
}]);

