show databases;

use belajar_laravel_eloquent;

show tables;

describe categories;
select * from categories;

describe vouchers;
select * from vouchers;

describe comments;
select * from comments;

show create table comments;

alter table comments add column `commentable_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL;
alter table comments add column `commentable_type` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL;

# one to one
describe customers;
describe wallets;
describe virtual_accounts;

select * from customers;
select * from wallets;
select * from virtual_accounts;

# one to many
describe categories;
describe products;
select * from categories;
select * from products;

describe reviews;
select * from reviews;

describe customers_likes_products;
show create table customers_likes_products;

select * from categories;
select * from customers;
select * from products;
select * from customers_likes_products;
show create table customers_likes_products;

describe images;
select * from images;

select * from comments;
select * from vouchers;
select * from products;

describe tags;
describe taggables;

select * from vouchers;
select * from products;
select * from tags;
select * from taggables;

describe person;
select * from person;
