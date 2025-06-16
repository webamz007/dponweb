# DPOnWeb - Numbers Gaming Platform

A comprehensive web-based numbers gaming platform that allows users to play numbers across different markets with real-time results and secure payment processing.

🌐 **Live Demo:** [http://dponweb.com](http://dponweb.com)

## 📋 Overview

DPOnWeb is a feature-rich gaming platform where users can participate in number-based games across various markets. The platform operates on scheduled market timings and provides a complete ecosystem for both players and administrators.

## ✨ Key Features

### User Features
- **Multi-Market Gaming**: Play numbers across different markets with specific opening times
- **Secure Payments**: Multiple payment methods including Razorpay integration
- **Wallet System**: Deposit and withdrawal functionality with real-time balance tracking
- **Game Rules & Combinations**: Various number combinations and winning strategies
- **Mobile Responsive**: Optimized for both desktop and mobile devices

### Admin Features
- **Complete Admin Panel**: Full control over platform operations
- **User Management**: Comprehensive user administration and monitoring
- **Market Control**: Manage different gaming markets and their schedules
- **Winners Management**: Track and manage winning lists and payouts
- **Ratio Configuration**: Set and adjust winning ratios and game rules
- **Role-based Authentication**: Secure admin access with different permission levels

### Technical Features
- **REST API**: Complete API support for Android mobile application
- **Real-time Updates**: Live market status and results
- **Data Export**: Excel export functionality for reports and analytics
- **Responsive Design**: Mobile-first approach with Tailwind CSS

## 🛠️ Technology Stack

### Backend
- **Framework**: Laravel 9.x (PHP 8.0+)
- **Database**: MySQL/PostgreSQL
- **Authentication**: Laravel Sanctum
- **API**: RESTful API architecture
- **Payment Gateway**: Razorpay integration

### Frontend
- **Framework**: Vue.js 3.x
- **Routing**: Inertia.js for SPA experience
- **Styling**: Tailwind CSS + Sass
- **UI Components**: Flowbite component library
- **Build Tool**: Vite

### Key Packages
- **Data Tables**: Yajra Laravel DataTables
- **Excel Export**: Maatwebsite Excel
- **HTTP Client**: Guzzle
- **CORS**: Laravel CORS
- **Development**: Laravel Debugbar, Breeze

## 🚀 Installation

### Prerequisites
- PHP >= 8.0.2
- Composer
- Node.js & npm
- MySQL/PostgreSQL database

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone https://github.com/webamz007/dponweb.git
   cd dponweb
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure your `.env` file**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=dponweb
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   
   # Razorpay Configuration
   RAZORPAY_KEY=your_razorpay_key
   RAZORPAY_SECRET=your_razorpay_secret
   ```

6. **Run database migrations**
   ```bash
   php artisan migrate
   ```

7. **Seed the database (optional)**
   ```bash
   php artisan db:seed
   ```

8. **Build frontend assets**
   ```bash
   npm run build
   ```

9. **Start the development server**
   ```bash
   php artisan serve
   npm run dev
   ```

## 📱 Mobile App Integration

The platform includes a complete REST API for Android mobile application integration. API endpoints cover:

- User authentication and registration
- Market data and schedules
- Game participation and results
- Wallet operations
- Transaction history

## 🔧 Configuration

### Payment Gateway Setup
Configure Razorpay in your `.env` file with your merchant credentials:
```env
RAZORPAY_KEY=rzp_test_your_key_here
RAZORPAY_SECRET=your_secret_key_here
```

### Market Configuration
Admin users can configure:
- Market opening/closing times
- Winning ratios and multipliers
- Game rules and combinations
- Minimum/maximum bet amounts

## 📊 Admin Panel

Access the admin panel at `/admin` (or configured route) with administrator credentials. The panel provides:

- **Dashboard**: Overview of platform statistics
- **Users**: Manage user accounts and permissions
- **Markets**: Configure gaming markets and schedules
- **Transactions**: Monitor deposits, withdrawals, and game transactions
- **Winners**: Manage winning lists and payouts
- **Reports**: Generate and export various reports

## 🔒 Security Features

- **Sanctum Authentication**: Secure API token management
- **CSRF Protection**: Cross-site request forgery protection
- **Role-based Access**: Different permission levels for users and admins
- **Secure Payments**: PCI-compliant payment processing
- **Data Validation**: Comprehensive input validation and sanitization

## 📈 Development

### Running in Development Mode
```bash
# Start Laravel development server
php artisan serve

# Start Vite development server (in separate terminal)
npm run dev
```

### Building for Production
```bash
# Build frontend assets
npm run build

# Optimize Laravel (production)
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 📞 Support

For support and inquiries:
- **Website**: [http://dponweb.com](http://dponweb.com)
- **Email**: websiteamz@gmail.com

---

**Note**: This platform is intended for entertainment purposes. Please ensure compliance with local gaming and gambling regulations in your jurisdiction.