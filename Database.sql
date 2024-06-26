show databases;

use belajar_laravel_eloquent;

show tables;

describe categories;
select * from categories;

describe vouchers;
select * from vouchers;

describe comments;
select * from comments;

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
