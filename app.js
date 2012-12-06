FT.App = function() {

	connection.request({
		action: 'getStatus',
		args: {},
		callback: function (data) {
			console.log("login data");
			console.log(data);
		}
	});

	this.html = $("body");
	this.header = new FT.AppHeader();
	this.schedule = new FT.Schedule();
	this.main = new FT.MainWindow();
	this.append(this.header);
	this.append(this.schedule);
	this.append(this.main);
};

FT.App.prototype = new FT.Element("body");