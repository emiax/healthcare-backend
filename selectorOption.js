FT.SelectorOption = function(value, name, parent) {
	FT.Element.call(this, "div", "option", null, parent);
	
	var scope = this;
	
	this.value = value;
	this.name = name;
	
	this.html.addClass(value).append(name);
	this.html.click(function() {
		scope.parent.select(scope);
	});
};

FT.SelectorOption.prototype = new FT.Element();