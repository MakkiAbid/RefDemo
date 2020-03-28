# RefDemo
This Application will register the user & generate its UNIQUE referral link...
if a user new user register into the application based on a referral link, the referred user will get 5$

or any user uploads a video to their account he/she would get 10$ for each video.


## Configuration
```php
    session_start();
	$conn = mysqli_connect("SERVER_NAME","USERNAME","PASSWORD","DATABASE");
	define("WEBSITE_URI", "URL");
```

Replace following words with your own credentials

SERVER_NAME = Your Server Name e.g localhost
USERNAME = Username on the server e.g root
PASSWORD = Users Password
DATABASE = Name of the database
URL = Your Domain name e.g http://example.com