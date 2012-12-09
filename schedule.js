FT.Schedule = function() {
	FT.Element.call(this, "section", "left", "schedule");
	
	var scope = this;
	
	var onChange = function(option) {
		if (!scope.sub) return;
		switch(option.value) {
			case "time":
				console.log("change schedule subscription: time");
				scope.sub.args = {
				}
			break;
			case "patient":
				console.log("change schedule subscription: patient");
				scope.sub.args = {
				}
			break;
			case "past":
				console.log("change schedule subscription: past");
				scope.sub.args = {
				}
			break;
		}
		connection.sync();
	}
	
	this.scheduleFilter = new FT.Selector("sorting", {
		time: "Tid",
		patient: "Patient",
		past: "Passerat"
	}, "patient", onChange);

	this.append(this.scheduleFilter);
};

FT.Schedule.prototype = new FT.Element();

FT.Schedule.prototype.onAppend = function() {
	FT.Element.prototype.onDetach.call(this);
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
}

FT.Schedule.prototype.onDetach = function() {
	FT.Element.prototype.onDetach.call(this);
	this.sub.unsubscribe();
}

FT.Schedule.prototype.build = function(data) {
	console.log("schedule data");
	console.log(data);
}