jFramework Deployment

To deploy jframework:

1) Create a  database, import the sql inside /install/_db/yourdb.sql file into it for core jframework.

2) import /install/_db/plugin/*.sql into the same db (these are plugins and unnecessary, but wont hurt)

3) edit /app/config/application.php and supply online/development version conditions and database settings.

4) point your browser to jFramework folder, voila! 

Customs Deployment 

1. open app/config/final.php and set the IP for MSSQL server (to enable pulling, optional)

2. run app once in development state to populate the database

