This is a Laravel project. Follow these steps to set it up locally.

## Requirements

- PHP >= 8.1
- Composer
- Laravel 10
- MySQL 

## Installation

1. **Clone the repository**

git clone https://github.com/homersalazar/blog-website.git
cd blog-website

2. Install dependencies
- Run composer install to install all PHP packages required by the project.

3. Create .env file
- Copy .env.example to .env and configure your database credentials.

4. Generate application key
- Run php artisan key:generate to generate the Laravel application key.
- 
5. Set up storage folders
- Make sure the folders storage/app/public/avatar and storage/app/public/post exist.

6. Link storage
- Run php artisan storage:link to create a symbolic link so uploaded files can be publicly accessible.
  
7. Run the application
- Use php artisan serve and open http://127.0.0.1:8000 in your browser.

Notes:

User avatars should be uploaded to storage/app/public/avatar.
Post images should be uploaded to storage/app/public/post.
Make sure the storage folder has proper write permissions.
