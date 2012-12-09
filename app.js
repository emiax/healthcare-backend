FT.App = function() {
	FT.Element.call(this, "body");

	this.loggedIn = undefined;
	this.html = $("body");
	
	this.user = null;
	var scope = this;
	
	var refresh = $('<div style="position: absolute; top: 0; right: 30px; z-index: 9999; width: 30px; height: 30px; background: #5bc7ff">');
	refresh.click(function() {
		location.reload();
	});
	var logout = $('<div style="position: absolute; top: 0; right: 0; z-index: 9999; width: 30px; height: 30px; background: #ff5151">');
	logout.click(function() {
		connection.request({action: 'logOut'});
	});
	
	this.html.append(refresh).append(logout);
	
	this.login = null;

	connection.subscribe({
		action: 'getStatus',
		args: {},
		callback: function (data) {
			console.log("check login: loggedIn = " + scope.loggedIn);
			if (!data.username && scope.loggedIn !== false) {
				//Load login form
				console.log("logged out : loggedIn = " + scope.loggedIn);
				scope.loggedIn = false;
				scope.login = new FT.Login();
				
				scope.detach(scope.header);
				scope.detach(scope.schedule);
				scope.detach(scope.main);
				
				scope.append(scope.login);
				console.log(scope.login);
			}
			else if (data.username && scope.loggedIn !== true) {
				//Load application
				scope.user = new FT.User(data);
				
				scope.loggedIn = true;
				console.log("logged in : username = " + scope.user.username);
	
				scope.header = new FT.AppHeader(scope.user);
				scope.schedule = new FT.Schedule();
				scope.main = new FT.MainWindow();
				
				scope.detach(scope.login);
				
				scope.append(scope.header);
				scope.append(scope.schedule);
				scope.append(scope.main);
			}
		}
	}, true);
};

FT.App.prototype = new FT.Element();