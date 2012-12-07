FT.User = function(args) {

	this.userName = args.userName;
	this.firstName = args.firstName;
	this.lastName = args.lastName;
	
	this.address = args.address || "";
	this.homeTelephone = args.homeTelephone || "";
	this.mobileTelephone = args.mobileTelephone || "";
	
	this.status = args.status || "";
	
	this.profilePicture = args.profilePicture || "";
	
};