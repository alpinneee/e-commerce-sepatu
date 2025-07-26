# E-Commerce Toko Sepatu

A modern e-commerce application built with Laravel for selling shoes online. This application provides a complete shopping experience with user authentication, product management, shopping cart, checkout process, and order management.

## 🚀 Features

### Customer Features
- **User Authentication & Registration** - Secure login and registration system
- **Product Browsing** - Browse products by category, search, and view details
- **Shopping Cart** - Add, update, and remove items from cart
- **Checkout Process** - Complete checkout with shipping address and payment
- **Order Management** - View order history and track order status
- **Profile Management** - Update profile information, change password
- **Address Management** - Manage multiple shipping addresses
- **Responsive Design** - Mobile-friendly interface with Tailwind CSS

### Admin Features
- **Dashboard** - Overview of sales, orders, and products
- **Product Management** - Add, edit, delete products with images
- **Category Management** - Organize products by categories
- **Order Management** - Process orders, update status, generate invoices
- **User Management** - View and manage customer accounts

## 🛠️ Technology Stack

- **Backend**: Laravel 12.x
- **Frontend**: Blade Templates with Tailwind CSS
- **Database**: MySQL/PostgreSQL
- **Authentication**: Laravel Breeze
- **File Storage**: Laravel Storage
- **Payment**: Integrated payment gateway (configurable)
- **Role Management**: Spatie Laravel Permission

## 📋 Requirements

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL/PostgreSQL
- Web Server (Apache/Nginx)

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/alpinneee/e-commerce-sepatu.git
   cd e-commerce-sepatu
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database**
   Edit `.env` file and set your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=ecommerce_sepatu
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run migrations and seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. **Create storage link**
   ```bash
   php artisan storage:link
   ```

8. **Build assets**
   ```bash
   npm run build
   ```

9. **Start the development server**
   ```bash
   php artisan serve
   ```

## 👥 Default Users

After running the seeders, you'll have these default users:

### Admin User
- Email: admin@example.com
- Password: password

### Customer Users
- Email: customer1@example.com
- Password: password
- Email: customer2@example.com
- Password: password

## 📁 Project Structure

```
e-commerce-sepatu/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Admin controllers
│   │   ├── Customer/       # Customer controllers
│   │   └── Auth/          # Authentication controllers
│   ├── Models/            # Eloquent models
│   ├── Services/          # Business logic services
│   └── Http/Middleware/   # Custom middleware
├── resources/views/
│   ├── admin/            # Admin views
│   ├── customer/         # Customer views
│   └── components/       # Reusable components
├── database/
│   ├── migrations/       # Database migrations
│   └── seeders/         # Database seeders
└── routes/
    ├── web.php          # Web routes
    └── api.php          # API routes
```

## 🔧 Configuration

### Payment Gateway
Configure your payment gateway in the `.env` file:
```env
PAYMENT_GATEWAY=midtrans
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
```

### File Storage
Configure file storage for product images and user avatars:
```env
FILESYSTEM_DISK=public
```

## 🧪 Testing

Run the test suite:
```bash
php artisan test
```

## 📝 API Documentation

The application includes RESTful APIs for:
- Product management
- Cart operations
- Order processing
- User authentication

API endpoints are available at `/api/` prefix.

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- [Laravel](https://laravel.com/) - The web framework used
- [Tailwind CSS](https://tailwindcss.com/) - CSS framework
- [Spatie Laravel Permission](https://github.com/spatie/laravel-permission) - Role and permission management
- [Laravel Breeze](https://laravel.com/docs/starter-kits#laravel-breeze) - Authentication scaffolding

## 📞 Support

If you have any questions or need support, please open an issue on GitHub or contact the development team.

---

**Happy Coding! 🎉**
