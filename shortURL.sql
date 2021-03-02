drop schema if exists shortURL;
create schema shortURL;
use shortURL;

create table shorturl.URL (
	url_id int auto_increment not null,
	longURL varchar(512) null,
	shortURL varchar(18) null,
	numRedirects int not null,
	constraint url_pk primary key (url_id)
)

ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;