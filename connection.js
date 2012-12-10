var connection = (function () {
    
    var that = {};

    var subscriptions = {};
 
    var nextId = 0;

    var begunPolling = false; 
    
    var channel = -1;

    var hasFocus = true;

    that.subscribe = function (a, instant) {
        var id = nextId,
            s = {
                action: a.action,
                args: a.args,
                callback: a.callback,
                unsubscribe: function () {
                    delete subscriptions[id];
                }
            };

        subscriptions[id] = s;
        nextId++;
        
		if (instant) that.requestMultiple([]);
		
        return s;
    };
    
    /*
     * Example input:
     a = [
     {
       action: "doSomething"
       args: {limit: 4},
       callback: function
     ]
     */
    that.requestMultiple = function(a, onSuccess) {
        var callbacks = {},
            data = {};

        a = a || [];
        
        a.forEach(function (v) {
            var id = nextId; 
            data[id] = {
                action: v.action,
                args: v.args,
                lazy: false
            }
            callbacks[id] = v.callback;
            nextId++;
        });
        
        Object.keys(subscriptions).forEach(function (k) {

            var v = subscriptions[k];
            data[k] = {
                action: v.action,
                args: v.args,
                lazy: true
            }
        });
        
        var onResponse = function(json) {
            var c = json.channel;
            
            if (typeof c !== 'undefined') {
                channel = c;
            }
            
            var a = json.response;
            
            if (a) {
                Object.keys(a).forEach(function (k) {
                    var v = a[k],
                        s = subscriptions[k],
                        c = callbacks[k];
                    if (s) {
                        s.callback(v);
                    } else if (c) {
                        c(v);
                    }
                });
            }
            if (onSuccess) { 
                onSuccess();
            }
        };
        
        if (Object.keys(data).length > 0) {
            $.ajax({
                url: 'ajax.php',
                dataType: 'json',
                type: 'POST',
                data: {
                    channel: channel,
                    request: JSON.stringify(data)
                },
                success: onResponse
            });
        }
    };

    that.request = function(a) {
        that.requestMultiple([{action: a.action,
                           args: a.args,
                           callback: a.callback
                          }]);
    };
	
	that.sync = function() {
		that.requestMultiple([]);
	}

    that.beginPolling = function(interval) {
        if (!begunPolling) {
            (function f () {
                if (hasFocus) {
                    setTimeout(function () {
                        that.requestMultiple([], f);
                    }, interval);
                } else {
                    setTimeout(f, interval);
                }
            })();
            begunPolling = true;
        }
    }

    $(document).ready(function() {
        $(window).focus(function() {
            hasFocus = true;
        });
        
        $(window).blur(function() {
            hasFocus = false;
        });
    });

    return that;

})();

connection.beginPolling(5000);
