/* https://github.com/olav/drops.js -- MIT */
(function (window, document, undefined) {
  'use strict';
  var o = {};
  window.drops = function (selector, options) {
    if (!o.support())
      return;
    var el = document.querySelector(selector);
    options.element = el;
    o.when(el, 'dragenter', options.dragenter);
    o.when(el, 'dragover', options.dragover);
    o.when(el, 'dragexit', options.dragexit);
    o.when(el, 'dragleave', options.dragleave);
    o.when(el, 'dragend', options.dragend);
    o.when(el, 'drop', function (event) {
      if (options.drop)
        options.drop(event);
      o.readFiles(event.dataTransfer.files, options, function (files) {
        o.uploadFiles(files, options, options.complete);
      });
    });
  };
  o.support = function () {
    var xhr = new XMLHttpRequest();
    var xhr2 = !!(xhr && 'upload' in xhr && 'onprogress' in xhr.upload);
    var fileReader = !!(window.File && window.FileList && window.FileReader);
    var formData = window.FormData !== undefined;
    return !!(document.querySelector && formData && fileReader && xhr2);
  };
  o.when = function (el, name, fn) {
    if (!el)
      return;
    var handler = function (event) {
      event.stopPropagation();
      event.preventDefault();
      if (fn)
        fn(event);
      return false;
    };
    var oldHandler = document[name];
    if (oldHandler)
      el.removeEventListener(name, oldHandler, false);
    document[name] = handler;
    el.removeEventListener(name, handler, false);
    el.addEventListener(name, handler, false);
  };
  o.readFiles = function (files, options, callback) {
    var complete = [], onload = function (file) {
        return function () {
          complete.push(file);
          if (complete.length === files.length)
            callback(complete);
        };
      };
    for (var i = 0; i < files.length; i++) {
      var reader = new FileReader();
      reader.onload = onload(files[i]);
      reader.readAsArrayBuffer(files[i]);
    }
  };
  o.uploadFiles = function (files, options, callback) {
    var n = 0, i = 0, terminator = function () {
        if (++n === files.length && callback)
          callback();
      };
    for (i = 0; i < files.length; i++) {
      o.uploadFile(files[i], options, terminator);
    }
  };
  o.uploadFile = function (file, options, done) {
    var formData = new FormData();
    formData.append(options.field || 'file', file);
    // sepa.co hack
    if ($(options.element).attr('data-user-id')) {
      formData.append('user-id', $(options.element).attr('data-user-id'));
    }
    if ($(options.element).attr('data-upload-type')) {
      formData.append('upload-type', $(options.element).attr('data-upload-type'));
    }
    if (options.formData)
      options.formData(formData, file);
    var xhr = new XMLHttpRequest();
    xhr.open(options.method || 'POST', options.url || '/', true);
    xhr.upload.onprogress = o.progress(options, file);
    xhr.setRequestHeader('Accept', options.acccept || 'text');
    xhr.setRequestHeader('Cache-Control', 'no-cache');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader('X-File-Name', encodeURIComponent(file.name));
    if (options.xhr)
      options.xhr(xhr);
    xhr.onload = function () {
      if (options.success)
        options.success(xhr, file);
      if (options.progress)
        options.progress(file, 100);
      done();
    };
    xhr.onerror = function () {
      if (options.error)
        options.error(xhr, file);
      done();
    };
    xhr.send(formData);
  };
  o.progress = function (options, file) {
    return function (event) {
      if (!event.lengthComputable || !options.progress)
        return;
      var percent = Math.max(0, Math.min(100, event.loaded / event.total * 100));
      options.progress(file, Math.ceil(percent));
    };
  };
}(window, document));