# 💄 Makeup E-commerce Website (Symfony):HASH&HASH store

A full-featured e-commerce website built with the **Symfony PHP framework**, focused on selling makeup and beauty products. This project includes user authentication, product browsing, shopping cart, admin dashboard, and more.

## 🛠️ Features

- User registration and login (ROLE_USER / ROLE_ADMIN)
- Product catalog with categories and filtering
- Shopping cart and order system
- Secure admin panel for managing products, users, and orders
- Profile management (edit info, view orders)
- Responsive design with Twig templates

## ⚙️ Tech Stack

- **Backend:** Symfony 6+, PHP 8.1+
- **Frontend:** Twig, HTML5, CSS3, Bootstrap,js
- **Database:** MySQL (hosted on railway)
- **ORM:** Doctrine
- **Authentication:** Symfony Security (Login / Registration / Roles)
- **Dev Tools:** Symfony CLI, Composer

## 📦 Installation

### Requirements

- PHP >= 8.1
- Composer
- Symfony CLI
- MySQL 

### Steps

```bash
# Clone the repository
git clone https://github.com/islembriki/makeup_project.git
cd makeup_project

# Install PHP dependencies
composer install

# Set up environment variables
cp .env .env.local
# Edit .env.local with your database credentials

# Create the database
php bin/console doctrine:database:create

# Run migrations
php bin/console doctrine:migrations:migrate

# Load fixtures (optional)
php bin/console doctrine:fixtures:load

# Start the Symfony server
symfony server:start
Visit http://localhost:8000 to explore the app.

🔐 Default Admin Access
To access the admin panel, log in with admin account (email:admin123@gmail.com   , password:456)

