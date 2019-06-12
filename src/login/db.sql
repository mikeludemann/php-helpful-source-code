CREATE TABLE `user` ( 
	`id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY , 
	`username` VARCHAR( 100 ) NOT NULL , 
	`email` VARCHAR( 255 ) NOT NULL , 
	`password` VARCHAR( 32 ) NOT NULL , 
	UNIQUE (`username`) 
); 


INSERT INTO `user` ( 
	`id` , 
	`username` , 
	`email` , 
	`password` 
) VALUES( 
	NULL , 'John Doe', 'john.doe@me.org', 'c0a03a69ef1f5cc5d81b5d10ce011e12' 
);

/* Password: john_doe-1990 */
