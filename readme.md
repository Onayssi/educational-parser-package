# educational-parser-package
A web based application package that create entries to the Database using Attributes or Elements from a provided XML File, using Laravel 5.4 Framework.
# Description
The bundle allows to upload and parse an xml configuration file based on the requirements set from an Online Educational Tool Provider. Built using Laravel 5.4 Framework.

# Process
The package accepts an [xml] file format that can be upload into a web server. It saves the file in the storage repository (```/resources/uploads```) for the history. 
It looks into the provided file to parse the nodes schema and extract the required fields (```title,description,launch_url,icon_url```). Then, it stores the values to the database in the <output> table.
The package takes into consideration the issues below:
- Require a file type: It requires an attachment with [xml] extension.
- Accept only [xml] file format: No other file format is allowed. Only [xml] files format can be uploaded.
- Restriction on the structure: The provided [xml] file should meet the requirements from the (Educational Tool Provider), meaning, it should included the expected nodes.
- Avoid redundancy: No records with the same quadruplet values are accepted to avoid repetition of data.

# Application Requirements
- PHP Version 5.6.x and greater
- Apache or Nginx Web Server
- MySQL Version 5.6.17 and greater
- PDO extension for MySQL must be loaded
- Online web server or local web server, e.g (XAMPP, WAMP, MAMP, LAMP)

# Application Settings

The bundle should be configured for the settings URL and database connection.<br>
It needs to be set from the ```.env``` configuration file by setting the parameters ```DB_DATABASE,DB_USERNAME,DB_PASSWORD``` for the database connection and the ```APP_URL``` for the base URL.<br>
Also, it shoud be set from ```config/database``` and the ```config/app``` for the same objective.<br>
The ```mode_rewrite``` should be enable for the approach (if using Apache server) or editing the ``` default.conf``` (if using Nginex)

### Database Migration:
The SQL Database file is attached within the application.<br>
It's possible to do migration from the artisan command line tool for Laraval to import the tables directly with all criteria.<br>
Just create the proper database and set the configuration part, then run the command below (after accessing the relative path of the application):
```php artisan migrate```

# Copyright
Copyright (c) 2010 - 2017 Mouhamad Ounayssi.<br>
Blog: https://www.mouhamadonayssi.wordpress.com.
