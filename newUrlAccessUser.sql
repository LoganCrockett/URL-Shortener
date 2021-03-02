CREATE USER 'shortUrlAccess'@'localhost';
ALTER USER 'shortUrlAccess'@'localhost'
IDENTIFIED BY '1234' ;
GRANT Usage ON *.* TO 'shortUrlAccess'@'localhost';
GRANT Delete ON shorturl.url TO 'shortUrlAccess'@'localhost';
GRANT Insert ON shorturl.url TO 'shortUrlAccess'@'localhost';
GRANT Select ON shorturl.url TO 'shortUrlAccess'@'localhost';
GRANT Update ON shorturl.url TO 'shortUrlAccess'@'localhost';
