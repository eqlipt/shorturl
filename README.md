# Shorten URL service

Validate input URLs\
Create crc32 hashes as short versions of input URLs\
Redirect short URLs to original URLs\
Keep track of hits for each short URL\
Configure short URLs lifetime

PHP + MySQL\
Apache + modRewrite

# Deployment

1. Run Apache and MySQL servers locally or choose a respective hosting
2. Put project files and folders to desired directory keeping project structure
3. Run shorturl.sql script to create database
4. Configure db_credentials.php for database connection
5. That's it! Locate your project folder or web address in a browser

You may set the lifiteme of URLs in `url.class.php` as `LINK_ACTIVE_DURATION` value\
If you wish to use an algorithm other than crc32 for shortening URLs, you may reconfigure the `generateUniqueHash` method inside `url.class.php`
