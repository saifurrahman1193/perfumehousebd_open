<img src="public/uploads/company/logo/company_logo.png" align="right" height="100" width="100" >

# Perfume House BD
<hr>


## Link

<!-- * <a href="https://www.perfumehousebd.com" target=_blank>www.perfumehousebd.com</a> -->




## Table of contents
- [Perfume House BD](#perfume-house-bd)
  - [Link](#link)
  - [Table of contents](#table-of-contents)
  - [About](#about)
  - [Modules](#modules)
    - [Discount Badges](#discount-badges)
  - [Prerequisites](#prerequisites)
    - [Installing](#installing)
  - [Running locally](#running-locally)
  - [Directory Structure](#directory-structure)
  - [Articles and Git repositories](#articles-and-git-repositories)
  - [Tools](#tools)
  - [Deployment](#deployment)
  - [Challanges](#challanges)
  - [Authors](#authors)
  - [License](#license)




## About




## Modules

| Modules                         | Completed |
| ------------------------------- | --------- |
| 🟢 **Frontend Panel**    🟢       |           |
| Home                            | ✓         |
| Dynamic pages                   | ✓         |
| Brands                          | ✓         |
| Product list                    | ✓         |
| Product details                 | ✓         |
| Wishlist                        | ✓         |
| Compare list                    | ✓         |
| Login                           | ✓         |
| Register                        | ✓         |
| Checkout                        | ✓         |
| Order History                   | ✓         |
|                                 |           |
| 🛂 **Super Admin**               |           |
| Login                           | ✓         |
| Dahsboard                       | ✓         |
| Menu                            | ✓         |
| - DB Backup                     | ✓         |
| - Storage Backup Backup         | ✓         |
| - Cache remove                  | ✓         |
| - Profile                       | ✓         |
| - Change Password               | ✓         |
| **Super admin**                | ✓         |
| - Sitemap generator             | ✓         |
| **Product**                     | ✓         |
| - Batch management              | ✓         |
| - Category                      | ✓         |
| - product type                  | ✓         |
| - Skin type                     | ✓         |
| - Application area              | ✓         |
| - Formulation                   | ✓         |
| - UOM                           | ✓         |
| - Brand                         | ✓         |
| - Supplier                      | ✓         |
| - Country                       | ✓         |
| **Frontend**                    | ✓         |
| - Advertisement Slider          | ✓         |
| - Featrued Products             | ✓         |
| - Latest Products               | ✓         |
| - Best Selling Products         | ✓         |
| - Top Rated Products            | ✓         |
| - Limited Editions              | ✓         |
| - New Arrivals                  | ✓         |
| - Company Settings              | ✓         |
| - Pages                         | ✓         |
| - Gifts                         | ✓         |
| **Carts**                       | ✓         |
| - Manual Cart                   | ✓         |
| - Delivery Settings             | ✓         |
| - Discount Settings             | ✓         |
| - Discount Badges               | ✓         |
| - Payment Settings              | ✓         |
| - Area                          | ✓         |
| **Reports**                     | ✓         |
| - Product Visit Report          | ✓         |
| - Customer Product Visit Report | ✓         |
| - Wishlist Report               | ✓         |
| - Compare List Report           | ✓         |
| - Low Stock Report              | ✓         |
| - Sales Report Graph            | ✓         |
| - Valuable Customers            | ✓         |
| - DMY Sales Report              | ✓         |



### Discount Badges

* **Tables**
  1. discountbadges
  2. discountpoints
  3. cart
  4. discbadgeuses

* **cart**
  - here after cart created cart column data will be update . But, it will be invalid first. after cart status processing and completed by the admins in the admin panel, an admin must **approve** that the point is valid.

* **User**
  * after **enableCustToHoldBadgeDiscount(discountBadgeId)** in frontend in user table customer will be **enabled with discountBadgeId**
  * after **cart complete** then in **user -> discountBadgeId = null** must be set.
  * then, in discbadgeuses table , cartId & point information must be added
  * then an frontend
    * total valid points - usage points = available badge points

* **subheading action**
  * getAllCartsValidPoints>item.reqPointsToAchieve && (userInfo.discountBadgeId||'')!=item.discountBadgeId 
    * then **Apply**
  * getAllCartsValidPoints>item.reqPointsToAchieve

* **Note:**
  *  if a user has 1 item discount then, the badge discount win't be applied for that customer's specific product
  *  if, a,b,c here 3 products
     *  if a has 5% or 100 taka discount then
        *  badge discount won't be applied to product a
        *  but will be applied to b and c , these 2 products




## Prerequisites

<!-- What things you need to install the software and how to install them -->

| Build With                   | Version          |
| ---------------------------- | ---------------- |
| php                          | 7.3.11 (minimum) |
| MySQL                        | 5.7 (minimum)    |
| Laravel                      | 6.18.41          |
| JWT                          |                  |
| Vue.js                       | 2.6.11           |
| vue-template-compiler        | 2.6.12           |
| Vuetify                      | 2.3.10           |
| Vuex                         | 3.5.1            |
| Vue-router                   | 3.3.4            |
| Bootstrap                    | 4.5.0            |
| Node                         | 12.13.1          |
| Axios                        | 0.19.2           |
| Material Icons               |                  |
| Sass                         | 1.26.10          |
| lodash                       | 4.17.20          |
| Tiptap-vuetify               | 2.24.0           |
| fortawesome/fontawesome-free | 5.14.0           |
| **Laravel Plugins**          |                  |
| Scribe (Api doc)             |                  |
| DomPDF                       |                  |
| Debugbar                     |                  |
| Intervention Image           |                  |




### Installing

<!-- A step by step series of examples that tell you how to get a development env running

Say what the step will be -->

```
laravel new laravelvuecrud
php artisan key:generate
npm install
npm run dev
npm install --save vue
npm install
npm run dev
composer require laravel/ui
php artisan ui vue --auth
npm install
npm run dev
npm install bootstrap
npm install
npm run dev
npm i sweetalert2
npm install
npm run dev

npm install vuetify 
npm run dev

npm install material-design-icons-iconfont -D
import 'material-design-icons-iconfont/dist/material-design-icons.css' // Ensure you are using css-loader

*. AppServiceProvider.php
    use Illuminate\Support\Facades\Schema;

    public function boot()
    {
        Schema::defaultStringLength(191);
    }

*. .env
    DB_DATABASE=laravelcrud
    DB_USERNAME=root
    DB_PASSWORD=root
```


## Running locally
```
php artisan serve
localhost:8000

php artisan serve --port=8001
localhost:8001

===vue compile====
npm run watch
npm run dev
===vue compile====

=========mysql===========
mysql -h localhost -u root -p
ALTER USER 'root'@'localhost' IDENTIFIED BY 'root';
=========mysql===========

```






## Directory Structure

| Directory              | Purposes                              |
| ---------------------- | ------------------------------------- |
| /documents             | to track all the notes of the project |
| /resources/js/backend  | all backend components                |
| /resources/js/frontend | all frontend components               |
| /resources/js/mixins   | all necessary mixins                  |
| /resources/js/errors   | all error pages components            |





## Articles and Git repositories

* https://github.com/adrobinoga/zk-protocol 
    * (documents)
* https://github.com/raihanafroz/zkteco
    * (documents)
* https://github.com/nurkarim/zkteco-sdk-php/blob/master/zktest.php
* https://github.com/zsmhub/zkteco_api
* https://github.com/mlrahman/ZKTeco_Attendance_Access_Using_PHP
* https://github.com/neel55555/ZKTeco
* https://github.com/adrobinoga/zk-protocol/blob/master/protocol.md
* https://github.com/vodvud/php_zklib



## Tools

* For server
    * Visual Studio Code
    * Putty
    * Filezilla
    * Redis
      * https://github.com/microsoftarchive/redis/releases
* For local
    * MySQL Workbench
    * Firefox
        * Firefox vue-dev-tool
        * inspector
        * console
    * Postman
    * Git
    * Redis
    * Xamp




## Deployment
* export mfw.sql from local environment
* import mfw.sql in server
* install **LAMP Stack** in server environment
* **Git clone** from repository
* **.env** file change
* **delete** /bootstrap/cache/*
* Use **Filezilla** 
* Run below commands using putty - 

<details >
<summary>Click to <strong>Expand/Collapse commands</strong></summary>

```
sudo apt update
sudo apt -y install apache2
sudo apt install php7.4-common php7.4-mysql php7.4-xml php7.4-xmlrpc php7.4-curl php7.4-gd php7.4-imagick php7.4-cli php7.4-dev php7.4-imap php7.4-mbstring php7.4-opcache php7.4-soap php7.4-zip php7.4-intl php7.4-fpm php7.4-tidy libapache2-mod-php7.4 -y
php --version

sudo apt-cache policy redis-server
sudo apt-get install redis-server
sudo apt-get update
sudo apt-get install build-essential tcl
redis-server

sudo service apache2 restart
sudo systemctl restart apache2

sudo apt install mysql-server


===========replace to 000-default.conf==============
path: /etc/apache2/sites-available/000-default.conf
DocumentRoot /var/www/html/public

<Directory /var/www/html/public>
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
===========replace to 000-default.conf==============

==========ufw=========
ufw allow "Apache Full"
ufw allow "Apache Secure"
ufw allow "Apache Secure"
ufw allow Apache
ufw allow 80
ufw allow 22
ufw allow 3306
ufw allow "Apache Full"
==========ufw=========

================git==================
git clone <clone url>
git add .
git commit -m <message>
================git==================

=================== move directory one step back =========================
mv * ../
=================== move directory one step back =========================


=================== remove cache =========================
/bootstrap/cache/*
=================== remove cache =========================


=================FILL Zilla=============
settings: Protocol: SFTP
Host: <host> port <22>
Logon Type: Key File
user: root <ubuntu user>
Browse:  ppk private file
Connect
=================FILL Zilla=============


==========permissions============

sudo chmod a+rwx app
sudo chmod a+rwx bootstrap
sudo chmod a+rwx config
sudo chmod a+rwx public
sudo chmod a+rwx resources
sudo chmod a+rwx routes
sudo chmod a+rwx storage
sudo chmod a+rwx vendor


sudo chown -R www-data app
sudo chown -R www-data bootstrap
sudo chown -R www-data config
sudo chown -R www-data public
sudo chown -R www-data resources
sudo chown -R www-data routes
sudo chown -R www-data storage
sudo chown -R www-data vendor
sudo chown -R www-data vendor

sudo chgrp -R www-data storage bootstrap/cache
sudo chmod -R ug+rwx storage bootstrap/cache

sudo a2enmod rewrite
service apache2 restart

sudo chmod -R ug+rwx public
sudo chown -R www-data public
sudo chmod a+rwx public
sudo chown -R 777 public

 sudo chown -R www-data:www-data public
sudo chgrp -R www-data storage public
sudo chmod -R ug+rwx storage public
chown -R www-data:www-data html/
chgrp -R www-data public
sudo chown root public
sudo chgrp root public
 sudo chown -R www-data:root public

sudo a2enmod rewrite
sudo a2enmod headers
service apache2 restart


sudo a2enmod headers

service apache2 restart



==========php.ini======================
/etc/php/7.3/apache2/php.ini
post_max_size = 8192M                                                                                                              
upload_max_filesize = 2000M
memory_limit = 4000M   

or
upload_max_filesize = 50M
memory_limit = 512M
max_input_time = -1
max_execution_time = 0
post_max_size = 100M

service apache2 restart
systemctl restart apache2



extension_dir="\xampp\php\ext" 
to
extension_dir="C:\xampp\php\ext"



extension=php_sockets.dll
==========php.ini======================



==========permissions============

```
</details>



<details >
<summary>Click to <strong>Expand/Collapse PIMS DB Backup Scheduler commands</strong></summary>

```

file name db_backup.bat

F:
cd F:\pims\softwares\cctl\cctl

rem start php artisan serve
start php artisan schedule:run

```
</details>



## Challanges





## Authors

* **Md. Saifur Rahman** - *Full project* 



## License
This project is licensed under the <strong>Vibotixbd</strong> 
