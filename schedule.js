FT.Schedule = function() {
	var scope = this;
	this.sub = connection.subscribe({
		action: 'getSchedule',
		args: {
			day : 7,
			future : true
		},
		callback: function(data) {
			scope.build(data);
		}
	}, true);
};

FT.Schedule.prototype = new FT.Element("section");

FT.Schedule.prototype.onDetach = function() {
	this.sub.unsubscribe();
}

FT.Schedule.prototype.build = function(data) {
	console.log("schedule data");
	console.log(data);
}