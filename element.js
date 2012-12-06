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

FT.Element.prototype.detach = function(element) {
	var i = this.children.indexOf(element);
	if (i != -1) {
		this.children[i].onDetach();
		this.children.splice(i,1);
	}
}

FT.Element.prototype.onDetach = function() {}