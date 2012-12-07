FT.AppHeader = function() {
	FT.Element.call(this, "header");

	this.userInfo = $('<div class="left" id="userInfo">');
	this.statusWrapper = $('<div class="right" id="statusWrapper">');
	this.html.append(this.userInfo);
	this.html.append(this.statusWrapper);
	this.html.append($('<div class="clear">'));
	
	var onChange = function(option) {
		console.log("set status: " + option.value);
		// connection.request({
			// action: 'setStatus',
			// args: {status: option.value}
		// });
	}
	
	this.statusSelector = new FT.Selector("status", {
		free: "Tillgänglig",
		busy: "Upptagen",
		home: "Hemma",
		auto: "Automatisk"
	}, "auto", onChange);

	this.statusWrapper.append(this.statusSelector.html);
	this.children.push(this.statusSelector);
};

FT.AppHeader.prototype = new FT.Element();