FT = function(){};

FT.Element = function(tagName, className, parent) {
	this.tagName = tagName || "div";
	this.className = className || "";
	this.parent = parent || {};
	this.children = [];
	this.html = $('<' + this.tagName + '>');
};

FT.Element.prototype.append = function(element) {
	this.children.push(element);
	this.html.append(element.html);
};