var connection = (function () {
    
    var that = {};

    var subscriptions = {};
 
    var nextId = 0;

    var begunPolling = false; 
    
    var channel = -1;

    that.subscribe = function (a) {
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
    that.getMultiple = function(a, onSuccess) {
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
                url: 'index.php',
                dataType: 'json',
                data: {
                    channel: channel,
                    get: JSON.stringify(data)
                },
                success: onResponse
            });
        }
    };

    that.get = function(a) {
        that.getMultiple([{action: a.action,
                           args: a.args,
                           callback: a.callback
                          }]);
    };

    that.beginPolling = function(interval) {
        if (!begunPolling) {
            (function f () {
                setTimeout(function () {
                    that.getMultiple([], f);
                }, interval);
            })();
            begunPolling = true;
        }
    }


    return that;
    
})();


connection.beginPolling(5000);
