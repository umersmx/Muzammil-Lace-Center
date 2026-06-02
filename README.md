# Muzammil Lace Center

## Overview

**Muzammil Lace Center** is a premium e-commerce platform specializing in luxury textiles, bridal laces, embroidery borders, decorative ribbons, garment accessories, and sewing materials. Operating since 2004 in Mian Channu, Pakistan, we serve designers and fashion enthusiasts with high-quality products and exceptional customer service.

🌐 **Website**: [Muzammil Lace Center](#)  
📞 **WhatsApp**: Available for direct orders  
📧 **Email**: [contact info]  
📍 **Location**: Mian Channu, Pakistan

---

## Features

### 👥 Customer Portal
- **User Authentication**: Secure registration and login
- **Product Browsing**: Explore thousands of lace products with advanced filtering
- **Shopping Cart**: Easy-to-use cart management with real-time updates
- **Order Management**: Track orders and view order history
- **Wishlist**: Save favorite products for later
- **Reviews & Ratings**: Transparent product ratings and customer reviews
- **Multiple Payment Methods**: Flexible checkout options

### 📱 Admin Dashboard
- **Product Management**: Add, edit, and manage product inventory
- **Category Management**: Organize products by category
- **Order Management**: Process, track, and manage customer orders
- **Customer Management**: View customer profiles and order history
- **Reviews Management**: Moderate and manage product reviews
- **Blog Management**: Create and manage blog content

### 🛍️ E-commerce Capabilities
- **Product Catalog**: 5,000+ premium lace and accessory products
- **Search & Filter**: Advanced search with multiple filtering options
- **Multi-language Support**: English and Urdu interface
- **Responsive Design**: Mobile-friendly and desktop-optimized
- **Security Features**: CSRF protection, secure password hashing
- **Coupon System**: Support for discount codes and promotional campaigns
- **Inventory Management**: Real-time stock tracking

### 📚 Additional Features
- **Blog Section**: Articles about fashion trends and lace products
- **Order Tracking**: Customer order status monitoring
- **About Page**: Company information and history
- **Contact System**: Customer inquiry management
- **Footer Content Management**: Dynamic footer configuration

---

## Tech Stack

### Backend
- **Language**: PHP 7.4+
- **Database**: MySQL/MariaDB
- **ORM**: PDO (PHP Data Objects)

### Frontend
- **HTML5 & CSS3**: Semantic markup and modern styling
- **JavaScript**: AJAX for dynamic interactions
- **Framework**: Bootstrap 5
- **Icons**: Font Awesome

### Security
- **CSRF Protection**: Built-in CSRF token validation
- **Password Hashing**: bcrypt password encryption
- **SQL Injection Prevention**: Prepared statements and parameterized queries
- **Input Validation**: Server-side input sanitization
- **Session Security**: HTTPOnly cookies with Lax SameSite policy

---

## Project Structure

```
Muzammil-Lace-Center/
├── admin/                      # Admin dashboard
│   ├── dashboard.php          # Main admin dashboard
│   ├── products.php           # Product management
│   ├── categories.php         # Category management
│   ├── orders.php             # Order management
│   ├── customers.php          # Customer management
│   ├── reviews.php            # Review management
│   ├── blog.php               # Blog management
│   ├── header.php             # Admin header template
│   ├── footer.php             # Admin footer template
│   └── login.php              # Admin login
│
├── includes/                   # Shared components and utilities
│   ├── header.php             # Main header template
│   ├── footer.php             # Main footer template
│   ├── db.php                 # Database configuration
│   ├── functions.php          # Helper functions
│   └── csrf.php               # CSRF protection
│
├── ajax/                       # AJAX endpoints
│   └── search_products.php    # Product search API
│
├── assets/                     # Static assets
│   ├── css/
│   │   └── style.css          # Main stylesheet
│   ├── js/
│   │   ├── main.js            # Main JavaScript
│   │   └── ajax.js            # AJAX utilities
│   └── images/                # Static images
│
├── database/                   # Database files
│   └── muzammil_lace_center.sql # Database schema and seed data
│
├── uploads/                    # User-uploaded files
│   └── [product images, etc]
│
├── index.php                   # Homepage
├── shop.php                    # Product listing
├── product.php                 # Product detail page
├── cart.php                    # Shopping cart
├── checkout.php                # Checkout process
├── login.php                   # User login
├── register.php                # User registration
├── account.php                 # User account page
├── wishlist.php                # Wishlist page
├── blog.php                    # Blog listing
├── post.php                    # Blog post detail
├── about.php                   # About us page
├── contact.php                 # Contact page
├── track-order.php             # Order tracking
├── logout.php                  # User logout
├── robots.txt                  # SEO robots file
├── sitemap.xml                 # XML sitemap
└── README.md                   # This file
```

---

## Prerequisites

Before setting up the project, ensure you have:

- **PHP**: Version 7.4 or higher
- **MySQL/MariaDB**: Version 5.7 or higher
- **Composer**: For dependency management (optional)
- **Web Server**: Apache, Nginx, or similar
- **Git**: For version control
- **Node.js** (optional): For asset compilation if needed

---

## Installation

### 1. Clone the Repository

```bash
git clone git@github.com:KhizarDoingProgramming/Muzammil-Lace-Center.git
cd Muzammil-Lace-Center
```

### 2. Configure Database Connection

Edit `includes/db.php` with your database credentials:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'muzammil_lace_center');
define('DB_USER', 'your_db_user');
define('DB_PASS', 'your_db_password');
```

### 3. Create Database

Create a new database and import the schema:

```bash
mysql -u your_db_user -p < database/muzammil_lace_center.sql
```

Or use phpMyAdmin:
1. Create a new database named `muzammil_lace_center`
2. Import `database/muzammil_lace_center.sql`

### 4. Set Permissions

Ensure the `uploads/` directory is writable:

```bash
chmod 755 uploads/
chmod 755 assets/
```

### 5. Configure Web Server

Set your document root to the project folder:

```apache
DocumentRoot /path/to/Muzammil-Lace-Center
```

### 6. Access the Application

- **Frontend**: `http://localhost/`
- **Admin Panel**: `http://localhost/admin/login.php`

---

## Configuration

### Default Admin Credentials

After database setup, log in with default admin credentials (found in the database):
- **Email**: [Set during database import]
- **Password**: [Set during database import]

**⚠️ Important**: Change default admin password immediately after first login.

### Environment Settings

Key configuration options in `includes/db.php`:

- `BASE_URL`: Application base URL for links and redirects
- Session security settings (HTTPOnly, SameSite policy)
- PDO error mode and fetch mode

---

## Database Schema

### Core Tables

- **users**: Customer accounts and profiles
- **admin**: Administrator accounts
- **categories**: Product categories
- **products**: Product catalog
- **product_images**: Product gallery images
- **orders**: Customer orders
- **order_items**: Individual items in orders
- **wishlist**: User wishlist items
- **reviews**: Product reviews and ratings
- **blog_posts**: Blog articles
- **coupons**: Discount codes (if applicable)

All tables use InnoDB engine with UTF-8MB4 encoding for international character support.

---

## Security Features

✅ **CSRF Protection**: All forms include CSRF token validation  
✅ **Password Security**: bcrypt hashing for secure password storage  
✅ **SQL Injection Prevention**: Prepared statements and parameterized queries  
✅ **Input Validation**: Server-side sanitization of all user inputs  
✅ **Session Management**: Secure session configuration with HTTPOnly cookies  
✅ **XSS Prevention**: HTML entity encoding for user content  

---

## Usage

### Customer Workflow

1. **Browse Products**: Navigate to shop and use filters
2. **View Details**: Click product for detailed information and reviews
3. **Add to Cart**: Select quantity and add to shopping cart
4. **Checkout**: Review cart and proceed to checkout
5. **Place Order**: Enter shipping/billing info and place order
6. **Track Order**: Use order tracking page to monitor delivery

### Admin Workflow

1. **Login**: Access admin panel with credentials
2. **Manage Products**: Add, edit, delete products and categories
3. **Process Orders**: View and manage customer orders
4. **Customer Support**: View customer information and communication
5. **Content Management**: Manage blog posts and reviews

---

## Development

### Project Dependencies

Core functionality uses only PHP standard library and MySQL. No external composer packages required for basic operation.

### Code Standards

- **PHP**: PSR-12 style guide recommended
- **Database**: Normalized relational design
- **Naming**: Camel case for variables, snake_case for database columns

### Common Functions

Located in `includes/functions.php`:

- `get_featured_products()`: Fetch featured products
- `get_categories()`: Get all product categories
- `get_product_by_id()`: Retrieve product details
- `create_order()`: Process new orders
- etc.

---

## Contributing

We welcome contributions from the community! 

To contribute:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## Troubleshooting

### Database Connection Issues
- Verify MySQL/MariaDB service is running
- Check credentials in `includes/db.php`
- Ensure database exists and is accessible

### Upload Issues
- Check `uploads/` directory permissions (should be 755)
- Verify web server user has write access
- Check file size limits in PHP configuration

### Session Issues
- Ensure PHP sessions are enabled
- Check session storage path permissions
- Verify cookie settings are compatible with your domain

---

## License

This project is proprietary software. All rights reserved by Muzammil Lace Center.

For licensing inquiries, please contact the project owners.

---

## Contact & Support

**Muzammil Lace Center**

- 📍 **Location**: Mian Channu, Pakistan
- 📞 **WhatsApp**: +92-300-1234567 (Example)
- 📧 **Email**: contact@muzammillacecenter.com (Example)
- 🌐 **Website**: muzammillacecenter.com (Example)

---

## Acknowledgments

- **Bootstrap 5**: Frontend framework
- **Font Awesome**: Icon library
- **PHP Community**: Open-source tools and libraries
- **Customers**: For supporting Muzammil Lace Center for 20+ years

---

**Last Updated**: June 2026  
**Version**: 1.0.0

---

*Muzammil Lace Center - Trusted Quality Since 2004*
