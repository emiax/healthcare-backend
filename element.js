FT = function(){};

FT.Element = function(tagName, className, idName, parent) {
	this.tagName = tagName || "div";
	this.className = className || "";
	this.idName = idName || "";
	this.parent = parent || {};
	this.children = [];
	this.html = $('<' + this.tagName + '>');
	this.html.addClass(this.className);
	if (this.idName) this.html.attr("id", this.idName);
};

FT.Element.prototype.append = function(element) {
	this.children.push(element);
	this.html.append(element.html);
	return this;
};

FT.Element.prototype.detach = function(element) {
	var i = this.children.indexOf(element);
	if (i != -1) {
		element.html.detach();
		this.children[i].onDetach();
		this.children.splice(i,1);
		return true;
	}
	return false;
}

FT.Element.prototype.onDetach = function() {}