(function() {
    function e(t, i, n) {
        var r = e.resolve(t);
        if (null == r) {
            n = n || t, i = i || "root";
            var s = Error('Failed to require "' + n + '" from "' + i + '"');
            throw s.path = n, s.parent = i, s.require = !0, s
        }
        var o = e.modules[r];
        return o.exports || (o.exports = {}, o.client = o.component = !0, o.call(this, o.exports, e.relative(r), o)), o.exports
    }
    var t = Object.prototype.hasOwnProperty;
    e.modules = {}, e.aliases = {}, e.resolve = function(i) {
        "/" === i.charAt(0) && (i = i.slice(1));
        for (var n = i + "/index.js", r = [i, i + ".js", i + ".json", i + "/index.js", i + "/index.json"], s = 0; r.length > s; s++) {
            var i = r[s];
            if (t.call(e.modules, i)) return i
        }
        return t.call(e.aliases, n) ? e.aliases[n] : void 0
    }, e.normalize = function(e, t) {
        var i = [];
        if ("." != t.charAt(0)) return t;
        e = e.split("/"), t = t.split("/");
        for (var n = 0; t.length > n; ++n) ".." == t[n] ? e.pop() : "." != t[n] && "" != t[n] && i.push(t[n]);
        return e.concat(i).join("/")
    }, e.register = function(t, i) {
        e.modules[t] = i
    }, e.alias = function(i, n) {
        if (!t.call(e.modules, i)) throw Error('Failed to alias "' + i + '", it does not exist');
        e.aliases[n] = i
    }, e.relative = function(i) {
        function n(e, t) {
            for (var i = e.length; i--;)
                if (e[i] === t) return i;
            return -1
        }

        function r(t) {
            var n = r.resolve(t);
            return e(n, i, t)
        }
        var s = e.normalize(i, "..");
        return r.resolve = function(t) {
            var r = t.charAt(0);
            if ("/" == r) return t.slice(1);
            if ("." == r) return e.normalize(s, t);
            var o = i.split("/"),
                l = n(o, "deps") + 1;
            return l || (l = 0), t = o.slice(0, l + 1).join("/") + "/deps/" + t
        }, r.exists = function(i) {
            return t.call(e.modules, r.resolve(i))
        }, r
    }, e.register("component-emitter/index.js", function(e, t, i) {
        function n(e) {
            return e ? r(e) : void 0
        }

        function r(e) {
            for (var t in n.prototype) e[t] = n.prototype[t];
            return e
        }
        i.exports = n, n.prototype.on = function(e, t) {
            return this._callbacks = this._callbacks || {}, (this._callbacks[e] = this._callbacks[e] || []).push(t), this
        }, n.prototype.once = function(e, t) {
            function i() {
                n.off(e, i), t.apply(this, arguments)
            }
            var n = this;
            return this._callbacks = this._callbacks || {}, t._off = i, this.on(e, i), this
        }, n.prototype.off = n.prototype.removeListener = n.prototype.removeAllListeners = function(e, t) {
            this._callbacks = this._callbacks || {};
            var i = this._callbacks[e];
            if (!i) return this;
            if (1 == arguments.length) return delete this._callbacks[e], this;
            var n = i.indexOf(t._off || t);
            return ~n && i.splice(n, 1), this
        }, n.prototype.emit = function(e) {
            this._callbacks = this._callbacks || {};
            var t = [].slice.call(arguments, 1),
                i = this._callbacks[e];
            if (i) {
                i = i.slice(0);
                for (var n = 0, r = i.length; r > n; ++n) i[n].apply(this, t)
            }
            return this
        }, n.prototype.listeners = function(e) {
            return this._callbacks = this._callbacks || {}, this._callbacks[e] || []
        }, n.prototype.hasListeners = function(e) {
            return !!this.listeners(e).length
        }
    }), e.register("dropzone/index.js", function(e, t, i) {
        i.exports = t("./lib/dropzone.js")
    }), e.register("dropzone/lib/dropzone.js", function(e, t, i) {
        (function() {
            var e, n, r, s, o, l, a = {}.hasOwnProperty,
                p = function(e, t) {
                    function i() {
                        this.constructor = e
                    }
                    for (var n in t) a.call(t, n) && (e[n] = t[n]);
                    return i.prototype = t.prototype, e.prototype = new i, e.__super__ = t.prototype, e
                },
                c = [].slice,
                u = [].indexOf || function(e) {
                    for (var t = 0, i = this.length; i > t; t++)
                        if (t in this && this[t] === e) return t;
                    return -1
                };
            n = "undefined" != typeof Emitter && null !== Emitter ? Emitter : t("emitter"), o = function() {}, e = function(e) {
                function t(e, i) {
                    var n, r, s, o;
                    if (this.element = e, this.version = t.version, this.defaultOptions.previewTemplate = this.defaultOptions.previewTemplate.replace(/\n*/g, ""), "string" == typeof this.element && (this.element = document.querySelector(this.element)), !this.element || null == this.element.nodeType) throw Error("Invalid dropzone element.");
                    if (this.element.dropzone) throw Error("Dropzone already attached.");
                    if (t.instances.push(this), e.dropzone = this, n = null != (o = t.optionsForElement(this.element)) ? o : {}, r = function() {
                            var e, t, i, n, r, s, o;
                            for (n = arguments[0], i = arguments.length >= 2 ? c.call(arguments, 1) : [], s = 0, o = i.length; o > s; s++) {
                                t = i[s];
                                for (e in t) r = t[e], n[e] = r
                            }
                            return n
                        }, this.options = r({}, this.defaultOptions, n, null != i ? i : {}), null == this.options.url && (this.options.url = this.element.action), !this.options.url) throw Error("No URL provided.");
                    if (this.options.method = this.options.method.toUpperCase(), this.options.forceFallback || !t.isBrowserSupported()) return this.options.fallback.call(this);
                    if ((s = this.getExistingFallback()) && s.parentNode && s.parentNode.removeChild(s), this.options.previewsContainer) {
                        if ("string" == typeof this.options.previewsContainer ? this.previewsContainer = document.querySelector(this.options.previewsContainer) : null != this.options.previewsContainer.nodeType && (this.previewsContainer = this.options.previewsContainer), null == this.previewsContainer) throw Error("Invalid `previewsContainer` option provided. Please provide a CSS selector or a plain HTML element.")
                    } else this.previewsContainer = this.element;
                    if (this.options.clickable && (this.options.clickable === !0 ? this.clickableElement = this.element : "string" == typeof this.options.clickable ? this.clickableElement = document.querySelector(this.options.clickable) : null != this.options.clickable.nodeType && (this.clickableElement = this.options.clickable), !this.clickableElement)) throw Error("Invalid `clickable` element provided. Please set it to `true`, a plain HTML element or a valid CSS selector.");
                    this.init()
                }
                return p(t, e), t.prototype.events = ["drop", "dragstart", "dragend", "dragenter", "dragover", "dragleave", "selectedfiles", "addedfile", "removedfile", "thumbnail", "error", "processingfile", "uploadprogress", "sending", "success", "complete", "reset"], t.prototype.defaultOptions = {
                    url: null,
                    method: "post",
                    parallelUploads: 2,
                    maxFilesize: 256,
                    paramName: "file",
                    createImageThumbnails: !0,
                    maxThumbnailFilesize: 2,
                    thumbnailWidth: 100,
                    thumbnailHeight: 100,
                    params: {},
                    clickable: !0,
                    acceptParameter: null,
                    enqueueForUpload: !0,
                    previewsContainer: null,
                    dictDefaultMessage: "Drop files here to upload",
                    dictFallbackMessage: "Your browser does not support drag'n'drop file uploads.",
                    dictFallbackText: "Please use the fallback form below to upload your files like in the olden days.",
                    accept: function(e, t) {
                        return t()
                    },
                    init: function() {
                        return o
                    },
                    forceFallback: !1,
                    fallback: function() {
                        var e, i, n, r, s, o;
                        for (this.element.className = "" + this.element.className + " browser-not-supported", o = this.element.getElementsByTagName("div"), r = 0, s = o.length; s > r; r++) e = o[r], /(^| )message($| )/.test(e.className) && (i = e, e.className = "message");
                        return i || (i = t.createElement('<div class="message"><span></span></div>'), this.element.appendChild(i)), n = i.getElementsByTagName("span")[0], n && (n.textContent = this.options.dictFallbackMessage), this.element.appendChild(this.getFallbackForm())
                    },
                    drop: function() {
                        return this.element.classList.remove("drag-hover")
                    },
                    dragstart: o,
                    dragend: function() {
                        return this.element.classList.remove("drag-hover")
                    },
                    dragenter: function() {
                        return this.element.classList.add("drag-hover")
                    },
                    dragover: function() {
                        return this.element.classList.add("drag-hover")
                    },
                    dragleave: function() {
                        return this.element.classList.remove("drag-hover")
                    },
                    selectedfiles: function() {
                        return this.element === this.previewsContainer ? this.element.classList.add("started") : void 0
                    },
                    reset: function() {
                        return this.element.classList.remove("started")
                    },
                    addedfile: function(e) {
                        return e.previewTemplate = t.createElement(this.options.previewTemplate), this.previewsContainer.appendChild(e.previewTemplate), e.previewTemplate.querySelector(".filename span").textContent = e.name, e.previewTemplate.querySelector(".details").appendChild(t.createElement('<div class="size">' + this.filesize(e.size) + "</div>"))
                    },
                    removedfile: function(e) {
                        return e.previewTemplate.parentNode.removeChild(e.previewTemplate)
                    },
                    thumbnail: function(e, i) {
                        return e.previewTemplate.classList.remove("file-preview"), e.previewTemplate.classList.add("image-preview"), e.previewTemplate.querySelector(".details").appendChild(t.createElement('<img alt="' + e.name + '" src="' + i + '"/>'))
                    },
                    error: function(e, t) {
                        return e.previewTemplate.classList.add("error"), e.previewTemplate.querySelector(".error-message span").textContent = t
                    },
                    processingfile: function(e) {
                        return e.previewTemplate.classList.add("processing")
                    },
                    uploadprogress: function(e, t) {
                        return e.previewTemplate.querySelector(".progress .upload").style.width = "" + t + "%"
                    },
                    sending: o,
                    success: function(e,resp) {
                        return e.previewTemplate.classList.add("success")
                    },
                    complete: o,
                    previewTemplate: '<div class="preview file-preview">\n  <div class="details">\n   <div class="filename"><span></span></div>\n  </div>\n  <div class="progress"><span class="upload"></span></div>\n  <div class="success-mark"><span>✔</span></div>\n  <div class="error-mark"><span>✘</span></div>\n  <div class="error-message"><span></span></div>\n</div>'
                }, t.prototype.init = function() {
                    var e, i, n, r, s, o, l, a = this;
                    for ("form" === this.element.tagName && this.element.setAttribute("enctype", "multipart/form-data"), this.element.classList.contains("dropzone") && !this.element.querySelector(".message") && this.element.appendChild(t.createElement('<div class="default message"><span>' + this.options.dictDefaultMessage + "</span></div>")), this.clickableElement && (n = function() {
                            return a.hiddenFileInput && document.body.removeChild(a.hiddenFileInput), a.hiddenFileInput = document.createElement("input"), a.hiddenFileInput.setAttribute("type", "file"), a.hiddenFileInput.setAttribute("multiple", "multiple"), null != a.options.acceptParameter && a.hiddenFileInput.setAttribute("accept", a.options.acceptParameter), a.hiddenFileInput.style.visibility = "hidden", a.hiddenFileInput.style.height = "0", a.hiddenFileInput.style.width = "0", document.body.appendChild(a.hiddenFileInput), a.hiddenFileInput.addEventListener("change", function() {
                                var e;
                                return e = a.hiddenFileInput.files, e.length && (a.emit("selectedfiles", e), a.handleFiles(e)), n()
                            })
                        }, n()), this.files = [], this.filesQueue = [], this.filesProcessing = [], this.URL = null != (o = window.URL) ? o : window.webkitURL, l = this.events, r = 0, s = l.length; s > r; r++) e = l[r], this.on(e, this.options[e]);
                    return i = function(e) {
                        return e.stopPropagation(), e.preventDefault ? e.preventDefault() : e.returnValue = !1
                    }, this.listeners = [{
                        element: this.element,
                        events: {
                            dragstart: function(e) {
                                return a.emit("dragstart", e)
                            },
                            dragenter: function(e) {
                                return i(e), a.emit("dragenter", e)
                            },
                            dragover: function(e) {
                                return i(e), a.emit("dragover", e)
                            },
                            dragleave: function(e) {
                                return a.emit("dragleave", e)
                            },
                            drop: function(e) {
                                return i(e), a.drop(e), a.emit("drop", e)
                            },
                            dragend: function(e) {
                                return a.emit("dragend", e)
                            }
                        }
                    }], this.clickableElement && this.listeners.push({
                        element: this.clickableElement,
                        events: {
                            click: function(e) {
                                return a.clickableElement !== a.element || e.target === a.element || t.elementInside(e.target, a.element.querySelector(".message")) ? a.hiddenFileInput.click() : void 0
                            }
                        }
                    }), this.enable(), this.options.init.call(this)
                }, t.prototype.getFallbackForm = function() {
                    var e, i, n, r;
                    return (e = this.getExistingFallback()) ? e : (n = '<div class="fallback">', this.options.dictFallbackText && (n += "<p>" + this.options.dictFallbackText + "</p>"), n += '<input type="file" name="' + this.options.paramName + '" multiple="multiple" /><button type="submit">Upload!</button></div>', i = t.createElement(n), "FORM" !== this.element.tagName ? (r = t.createElement('<form action="' + this.options.url + '" enctype="multipart/form-data" method="' + this.options.method + '"></form>'), r.appendChild(i)) : (this.element.setAttribute("enctype", "multipart/form-data"), this.element.setAttribute("method", this.options.method)), null != r ? r : i)
                }, t.prototype.getExistingFallback = function() {
                    var e, t, i, n, r, s;
                    for (t = function(e) {
                            var t, i, n;
                            for (i = 0, n = e.length; n > i; i++)
                                if (t = e[i], /(^| )fallback($| )/.test(t.className)) return t
                        }, s = ["div", "form"], n = 0, r = s.length; r > n; n++)
                        if (i = s[n], e = t(this.element.getElementsByTagName(i))) return e
                }, t.prototype.setupEventListeners = function() {
                    var e, t, i, n, r, s, o;
                    for (s = this.listeners, o = [], n = 0, r = s.length; r > n; n++) e = s[n], o.push(function() {
                        var n, r;
                        n = e.events, r = [];
                        for (t in n) i = n[t], r.push(e.element.addEventListener(t, i, !1));
                        return r
                    }());
                    return o
                }, t.prototype.removeEventListeners = function() {
                    var e, t, i, n, r, s, o;
                    for (s = this.listeners, o = [], n = 0, r = s.length; r > n; n++) e = s[n], o.push(function() {
                        var n, r;
                        n = e.events, r = [];
                        for (t in n) i = n[t], r.push(e.element.removeEventListener(t, i, !1));
                        return r
                    }());
                    return o
                }, t.prototype.disable = function() {
                    return this.clickableElement === this.element && this.element.classList.remove("clickable"), this.removeEventListeners(), this.filesProcessing = [], this.filesQueue = []
                }, t.prototype.enable = function() {
                    return this.clickableElement === this.element && this.element.classList.add("clickable"), this.setupEventListeners()
                }, t.prototype.filesize = function(e) {
                    var t;
                    return e >= 1e11 ? (e /= 1e11, t = "TB") : e >= 1e8 ? (e /= 1e8, t = "GB") : e >= 1e5 ? (e /= 1e5, t = "MB") : e >= 100 ? (e /= 100, t = "KB") : (e = 10 * e, t = "b"), "<strong>" + Math.round(e) / 10 + "</strong> " + t
                }, t.prototype.drop = function(e) {
                    var t;
                    if (e.dataTransfer) return t = e.dataTransfer.files, this.emit("selectedfiles", t), t.length ? this.handleFiles(t) : void 0
                }, t.prototype.handleFiles = function(e) {
                    var t, i, n, r;
                    for (r = [], i = 0, n = e.length; n > i; i++) t = e[i], r.push(this.addFile(t));
                    return r
                }, t.prototype.accept = function(e, t) {
                    return e.size > 1024 * 1024 * this.options.maxFilesize ? t("File is too big (" + Math.round(e.size / 1024 / 10.24) / 100 + "MB). Max filesize: " + this.options.maxFilesize + "MB") : this.options.accept.call(this, e, t)
                }, t.prototype.addFile = function(e) {
                    var t = this;
                    return this.files.push(e), this.emit("addedfile", e), this.options.createImageThumbnails && e.type.match(/image.*/) && e.size <= 1024 * 1024 * this.options.maxThumbnailFilesize && this.createThumbnail(e), this.accept(e, function(i) {
                        return i ? t.errorProcessing(e, i) : t.options.enqueueForUpload ? (t.filesQueue.push(e), t.processQueue()) : void 0
                    })
                }, t.prototype.removeFile = function(e) {
                    if (e.processing) throw Error("Can't remove file currently processing");
                    return this.files = l(this.files, e), this.filesQueue = l(this.filesQueue, e), this.emit("removedfile", e), 0 === this.files.length ? this.emit("reset") : void 0
                }, t.prototype.removeAllFiles = function() {
                    var e, t, i, n;
                    for (n = this.files.slice(), t = 0, i = n.length; i > t; t++) e = n[t], 0 > u.call(this.filesProcessing, e) && this.removeFile(e);
                    return null
                }, t.prototype.createThumbnail = function(e) {
                    var t, i = this;
                    return t = new FileReader, t.onload = function() {
                        var n;
                        return n = new Image, n.onload = function() {
                            var t, r, s, o, l, a, p, c, u, d, h, m, f;
                            return t = document.createElement("canvas"), r = t.getContext("2d"), a = 0, p = 0, l = n.width, s = n.height, t.width = i.options.thumbnailWidth, t.height = i.options.thumbnailHeight, m = 0, f = 0, h = t.width, u = t.height, o = n.width / n.height, d = t.width / t.height, n.height < t.height || n.width < t.width ? (u = s, h = l) : o > d ? (s = n.height, l = s * d) : (l = n.width, s = l / d), a = (n.width - l) / 2, p = (n.height - s) / 2, f = (t.height - u) / 2, m = (t.width - h) / 2, r.drawImage(n, a, p, l, s, m, f, h, u), c = t.toDataURL("image/png"), i.emit("thumbnail", e, c)
                        }, n.src = t.result
                    }, t.readAsDataURL(e)
                }, t.prototype.processQueue = function() {
                    var e, t, i;
                    for (t = this.options.parallelUploads, i = this.filesProcessing.length, e = i; t > e;) {
                        if (!this.filesQueue.length) return;
                        this.processFile(this.filesQueue.shift()), e++
                    }
                }, t.prototype.processFile = function(e) {
                    return this.filesProcessing.push(e), e.processing = !0, this.emit("processingfile", e), this.uploadFile(e)
                }, t.prototype.uploadFile = function(e) {
                    var t, i, n, r, s, o, l, a, p, c, u, d, h, m, f = this;
                    if (p = new XMLHttpRequest, p.open(this.options.method, this.options.url, !0), i = function() {
                            return f.errorProcessing(e, p.responseText || "Server responded with " + p.status + " code.")
                        }, p.onload = function(t) {
                            var n, r;
                            return (r = p.status) >= 200 && 300 > r ? (f.emit("uploadprogress", e, 100, e.size), n = p.responseText, p.getResponseHeader("content-type") && ~p.getResponseHeader("content-type").indexOf("application/json") && (n = JSON.parse(n)), f.finished(e, n, t)) : i()
                        }, p.onerror = function() {
                            return i()
                        }, l = null != (d = p.upload) ? d : p, l.onprogress = function(t) {
                            return f.emit("uploadprogress", e, Math.max(0, Math.min(100, 100 * t.loaded / t.total)), t.loaded)
                        }, p.setRequestHeader("Accept", "application/json"), p.setRequestHeader("Cache-Control", "no-cache"), p.setRequestHeader("X-Requested-With", "XMLHttpRequest"), p.setRequestHeader("X-File-Name", e.name), t = new FormData, this.options.params) {
                        h = this.options.params;
                        for (o in h) a = h[o], t.append(o, a)
                    }
                    if ("FORM" === this.element.tagName)
                        for (m = this.element.querySelectorAll("input, textarea, select, button"), c = 0, u = m.length; u > c; c++) n = m[c], r = n.getAttribute("name"), s = n.getAttribute("type"), (!s || "checkbox" !== s.toLowerCase() || n.checked) && t.append(r, n.value);
                    return this.emit("sending", e, p, t), t.append(this.options.paramName, e), p.send(t)
                }, t.prototype.finished = function(e, t, i) {
                    return this.filesProcessing = l(this.filesProcessing, e), e.processing = !1, this.processQueue(), this.emit("success", e, t, i), this.emit("finished", e, t, i), this.emit("complete", e)
                }, t.prototype.errorProcessing = function(e, t) {
                    return this.filesProcessing = l(this.filesProcessing, e), e.processing = !1, this.processQueue(), this.emit("error", e, t), this.emit("complete", e)
                }, t
            }(n), e.version = "2.0.15", e.options = {}, e.optionsForElement = function(t) {
                return t.id ? e.options[r(t.id)] : void 0
            }, e.instances = [], e.forElement = function(e) {
                var t;
                return "string" == typeof e && (e = document.querySelector(e)), null != (t = e.dropzone) ? t : null
            }, e.autoDiscover = !0, e.discover = function() {
                var t, i, n, r, s, o;
                if (e.autoDiscover) {
                    for (document.querySelectorAll ? n = document.querySelectorAll(".dropzone") : (n = [], t = function(e) {
                            var t, i, r, s;
                            for (s = [], i = 0, r = e.length; r > i; i++) t = e[i], /(^| )dropzone($| )/.test(t.className) ? s.push(n.push(t)) : s.push(void 0);
                            return s
                        }, t(document.getElementsByTagName("div")), t(document.getElementsByTagName("form"))), o = [], r = 0, s = n.length; s > r; r++) i = n[r], e.optionsForElement(i) !== !1 ? o.push(new e(i)) : o.push(void 0);
                    return o
                }
            }, e.blacklistedBrowsers = [/opera.*Macintosh.*version\/12/i], e.isBrowserSupported = function() {
                var t, i, n, r, s;
                if (t = !0, window.File && window.FileReader && window.FileList && window.Blob && window.FormData && document.querySelector)
                    if ("classList" in document.createElement("a"))
                        for (s = e.blacklistedBrowsers, n = 0, r = s.length; r > n; n++) i = s[n], i.test(navigator.userAgent) && (t = !1);
                    else t = !1;
                else t = !1;
                return t
            }, l = function(e, t) {
                var i, n, r, s;
                for (s = [], n = 0, r = e.length; r > n; n++) i = e[n], i !== t && s.push(i);
                return s
            }, r = function(e) {
                return e.replace(/[\-_](\w)/g, function(e) {
                    return e[1].toUpperCase()
                })
            }, e.createElement = function(e) {
                var t;
                return t = document.createElement("div"), t.innerHTML = e, t.childNodes[0]
            }, e.elementInside = function(e, t) {
                if (e === t) return !0;
                for (; e = e.parentNode;)
                    if (e === t) return !0;
                return !1
            }, "undefined" != typeof jQuery && null !== jQuery && (jQuery.fn.dropzone = function(t) {
                return this.each(function() {
                    return new e(this, t)
                })
            }), i !== void 0 && null !== i ? i.exports = e : window.Dropzone = e, s = function(e, t) {
                var i, n, r, s, o, l, a, p, c;
                if (r = !1, c = !0, n = e.document, p = n.documentElement, i = n.addEventListener ? "addEventListener" : "attachEvent", a = n.addEventListener ? "removeEventListener" : "detachEvent", l = n.addEventListener ? "" : "on", s = function(i) {
                        return "readystatechange" !== i.type || "complete" === n.readyState ? (("load" === i.type ? e : n)[a](l + i.type, s, !1), !r && (r = !0) ? t.call(e, i.type || i) : void 0) : void 0
                    }, o = function() {
                        var e;
                        try {
                            p.doScroll("left")
                        } catch (t) {
                            return e = t, setTimeout(o, 50), void 0
                        }
                        return s("poll")
                    }, "complete" !== n.readyState) {
                    if (n.createEventObject && p.doScroll) {
                        try {
                            c = !e.frameElement
                        } catch (u) {}
                        c && o()
                    }
                    return n[i](l + "DOMContentLoaded", s, !1), n[i](l + "readystatechange", s, !1), e[i](l + "load", s, !1)
                }
            }, s(window, e.discover)
        }).call(this)
    }), e.alias("component-emitter/index.js", "dropzone/deps/emitter/index.js"), "object" == typeof exports ? module.exports = e("dropzone") : "function" == typeof define && define.amd ? define(function() {
        return e("dropzone")
    }) : window.Dropzone = e("dropzone")
})();