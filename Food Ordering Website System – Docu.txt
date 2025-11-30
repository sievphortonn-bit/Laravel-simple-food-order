Food Ordering Website System – Documentation (Windows Version)

Laravel + Composer + Git + XAMPP

1. Installation Guide (Windows)
1.1 Install Required Software

បងត្រូវដំឡើង 4 មុខសិន៖

1. XAMPP (MySQL + PHP)

Download from: https://www.apachefriends.org

Install → Start Apache និង MySQL

2. Composer

Download from: https://getcomposer.org/download

Install normally → Composer ផ្ដល់ PHP path (auto).

3. Git for Windows

Download from: https://git-scm.com/download/win

Install → Git Bash available

4. VS Code (Optional)

Download: https://code.visualstudio.com/

2. Clone Project from GitHub

បើបងមាន GitHub Repo រួច៖

បើក CMD / PowerShell

cd C:\xampp\htdocs
git clone https://github.com/yourname/food-order-system.git


ចូលទៅក្នុង folder៖

cd food-order-system

3. Install Laravel Dependencies (Windows)

Run:

composer install


បើ error "zip extension" → enable in php.ini (Windows)

4. Setup Environment (.env)

Copy file:

copy .env.example .env


Edit .env:

DB_DATABASE=food_order
DB_USERNAME=root
DB_PASSWORD=


Create database:

Open browser → http://localhost/phpmyadmin

Create database name: food_order

Generate application key:

php artisan key:generate

5. Run Migration
php artisan migrate


If error → run:

php artisan migrate:fresh

6. Running the System on Windows

Start Laravel server:

php artisan serve


Access website:

http://127.0.0.1:8000


Access Admin Panel:

http://127.0.0.1:8000/admin

7. User Guide (Windows User)
⭐ 7.1 Customer User Guide
✔ Register Account

ចូលទៅ Home page

ចុច Register

បញ្ចូល៖ Fullname, Email, Password

Submit

✔ Login

Email + Password

Click login

✔ Browse Food Menu

មើលប្រភេទ (Category)

មើលតម្លៃ

មើលរូបភាព

✔ Add to Cart

ចុច "Add to Cart"

កែបរិមាណ

✔ Checkout Order

Review order

Shipping info

Confirm Order

✔ View Order History

Go to My Orders

Check status (Pending → Cooking → Completed)

⭐ 7.2 Admin User Guide (Windows)
✔ Admin Login
/admin/login


Enter email + password

✔ Manage Category

Add new category

Update category name

Delete category

✔ Manage Food Items

Add food

Upload image

Edit food information

Delete food

✔ Manage Orders

View all customer orders

Update order status:

Pending

Confirmed

Cooking

Delivering

Completed

Cancel order

✔ Reports

View sales by date

Export PDF/Excel (if enabled)

8. Troubleshooting (Windows Version)
❗ Error 1: Composer Not Recognized

Solution:

Reinstall Composer

Tick “Add to PATH”

Restart PC

❗ Error 2: Migration Error
php artisan migrate:fresh

❗ Error 3: PHP Extension Missing

Go to:

C:\xampp\php\php.ini


Enable:

extension=zip

extension=mysqli

extension=fileinfo

Restart Apache.

❗ Error 4: Port 8000 Already Used

Run:

php artisan serve --port=8080


Open:

http://127.0.0.1:8080

9. Security Practices on Windows

Keep .env private

Use strong admin/password

Disable debug mode on production

Backup database via phpMyAdmin

10. Future Improvements

Add ABA/Bakong Online Payment

Add Telegram notification bot

Add Delivery Tracking

Build Android App