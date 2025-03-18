"use strict";
/**
 * mini (~300 b) version for event-emitter.
 */

// Object.defineProperty(exports, "__esModule", {
//   value: true
// });
/**
 * const ee = new OnFire();
 *
 * ee.on('click', () => {});
 *
 * ee.on('mouseover', () => {});
 *
 * ee.emit('click', 1, 2, 3);
 * ee.fire('mouseover', {}); // same with emit
 *
 * ee.off();
 */

var OnFire =
/** @class */
function () {
  function OnFire() {
    // 所有事件的监听器
    this.es = {}; // cname of fire

    this.emit = this.fire;
  }

  OnFire.prototype.on = function (eventName, cb, once) {
    if (once === void 0) {
      once = false;
    }

    if (!this.es[eventName]) {
      this.es[eventName] = [];
    }

    this.es[eventName].push({
      cb: cb,
      once: once
    });
  };

  OnFire.prototype.once = function (eventName, cb) {
    this.on(eventName, cb, true);
  };

  OnFire.prototype.fire = function (eventName) {
    var params = [];

    for (var _i = 1; _i < arguments.length; _i++) {
      params[_i - 1] = arguments[_i];
    }

    var listeners = this.es[eventName] || [];

    for (var i = 0; i < listeners.length; i++) {
      var _a = listeners[i],
          cb = _a.cb,
          once = _a.once;
      cb.apply(this, params);

      if (once) {
        listeners.splice(i, 1);
        i--;
      }
    }
  };

  OnFire.prototype.off = function (eventName, cb) {
    // clean all
    if (eventName === undefined) {
      this.es = {};
    } else {
      if (cb === undefined) {
        // clean the eventName's listeners
        delete this.es[eventName];
      } else {
        var listeners = this.es[eventName] || []; // clean the event and listener

        for (var i = 0; i < listeners.length; i++) {
          if (listeners[i].cb === cb) {
            listeners.splice(i, 1);
            i--;
          }
        }
      }
    }
  };

  OnFire.ver = "2.0.0";
  return OnFire;
}();
export default OnFire
// exports.default = OnFire;