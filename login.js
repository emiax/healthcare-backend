FT.Login = function() {
	FT.Element.call(this, "section", "", "login");

	var scope = this;

	this.form = $('<form name="login">');

	this.username = $('<input type="text" name="username" id="username"/>');
	this.password = $('<input type="password" name="password" id="password"/>');
	this.submit = $('<input type="submit" value="Logga in" id="submit"/>');
	
	this.form.append(this.username);
	this.form.append(this.password);
	this.form.append(this.submit);
	
	this.form.submit(function() {
		connection.request({
			action: 'logIn',
			args: {username: scope.username.val(), password: scope.password.val()},
			callback: function (data) {
				console.log("login data");
				console.log(data);
			}
		});
		return false;
	});
	
	this.html.append(this.form);

};

FT.Login.prototype = new FT.Element();
