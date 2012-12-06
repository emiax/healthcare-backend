FT.App = function() {

	connection.request({
		action: 'logOut',
		args: {},
		callback: function(data) {
			console.log("logout");
			console.log(data);
		}
	});

	var scope = this;
	this.loggedIn = undefined;
	this.html = $("body");
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
			}
			else if (data.username && scope.loggedIn !== true) {
				//Load application
				scope.loggedIn = true;
				console.log("logged in : username = " + data.username);
	
				scope.header = new FT.AppHeader();
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

FT.App.prototype = new FT.Element("body");