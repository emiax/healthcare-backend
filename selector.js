FT.Selector = function(name, options, selected, onChange) {
	FT.Element.call(this, "div", "selector");
	
	this.onChange = onChange || function(){};
	
	var scope = this;
			
	this.header = $('<div class="option">');
	this.options = $('<div class="options">');
	
	this.header.click(function() {
		scope.options.toggle();
	});
	
	this.html.append(this.header).append(this.options);
	
	this.html.addClass(name);
	var i = 0;
	for (key in options) {
		var option = new FT.SelectorOption(key, options[key], this);
		if (key == selected || (!selected && i == 0)) {
			scope.select(option);
		}
		this.options.append(option.html);
		this.children.push(option);
		i++;
	}
};

FT.Selector.prototype = new FT.Element();

FT.Selector.prototype.select = function(option) {
	if (!this.selected || this.selected != option) {
		this.selected = option;
		this.onChange(option);
		this.header.attr("class", "").html(option.name).addClass(option.html.attr("class"));
		this.options.children().removeClass("selected");
		option.html.addClass("selected");
	}
	this.options.hide();
}