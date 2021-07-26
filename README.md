<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## create permissions with spatie after migration

php artisan permission:create-role user
php artisan permission:create-role admin

## modify
use simp2
db.apiClients.insert({    "name":"admin",    "last_name":"sandbox",    "role":"user",    "company_unique_id":16,    "enabled":true,    "api_key":"admin_api_key",    "id":"sandbox.admin_api_key" });

## insert first user after permissions creation

insert into users (name,api_key, email, password, enabled, company_unique_id, updated_at, created_at)
values ("admin","admin_api_key", "admin@dev.com", "$2y$10$rUZrxIbiE7l8VY8/THyVju/q1WTC.acP8Fp/jjhw9qHCI/nCqA2f2", 1, 16, "2021-07-12 18:31:05", "2021-07-12 18:31:05");

insert into model_has_roles values (2,"App\\Models\\User",1);

## modify env

API_SIMP2_URI=core.sandbox.simp2.com/api/v1/
API_KEY_SIMP2=admin_api_key
MONGODB_CS=mongodb://localhost:27000



