# Pet Adoption System

A full-stack web application for managing pet adoption applications built with HTML5, CSS3, JavaScript, jQuery, PHP, and MySQL.

## 🐾 Features

- **Pet Adoption Form** - Submit applications for Dog, Cat, or Rabbit adoption
- **CRUD Operations** - Create, Read, Update, Delete adoption applications
- **Input Validation** - Email format, phone number, and uniqueness validation
- **Responsive Design** - Modern gradient UI with mobile-first approach
- **Security** - SQL injection prevention, XSS protection, input sanitization

## 🚀 Technologies Used

- **Frontend**: HTML5, CSS3, JavaScript ES6, jQuery 3.6.0
- **Backend**: PHP 7.4+, MySQL 8.0
- **Server**: Apache (XAMPP)
- **Design**: CSS Grid/Flexbox, CSS Variables, Gradient Backgrounds

## 📋 Installation & Setup

### Prerequisites
- XAMPP (Apache + MySQL + PHP)
- Web browser

### Local Setup
1. Clone this repository:
   ```bash
   git clone https://github.com/RAGHAVENDRASINGBHAJERI/pet-adoption-system.git
   ```

2. Move files to XAMPP htdocs:
   ```
   C:\xampp\htdocs\pet-adoption-system\
   ```

3. Start XAMPP services:
   - Apache
   - MySQL

4. Create database:
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create database named `registration_db`

5. Run migration (if needed):
   - Visit: http://localhost/pet-adoption-system/migrate_database.php

6. Access application:
   - Main form: http://localhost/pet-adoption-system/
   - Management: http://localhost/pet-adoption-system/manage.php

## 🗄️ Database Schema

```sql
CREATE TABLE registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    pet_type VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## 🔒 Security Features

- **Input Validation**: HTML5 patterns + PHP server-side validation
- **SQL Injection Prevention**: PDO prepared statements
- **XSS Protection**: htmlspecialchars() output escaping
- **Email Uniqueness**: Database constraints + application logic
- **Phone Validation**: 10-digit format enforcement

## 📱 Responsive Design

- Mobile-first CSS approach
- CSS Grid and Flexbox layouts
- Modern gradient backgrounds
- Hover effects and animations
- Cross-browser compatibility

## 🎨 UI/UX Features

- Clean, modern interface
- Gradient color schemes
- Smooth animations
- Form validation feedback
- Success/error messaging
- Intuitive navigation

## 📊 Project Structure

```
pet-adoption-system/
├── index.html          # Main adoption form
├── process.php         # Form processing & validation
├── manage.php          # CRUD management interface
├── config.php          # Database configuration
├��─ style.css           # Application styling
├── migrate_database.php # Database migration script
└── README.md           # Project documentation
```

## 🌐 Live Demo

*Note: This is a PHP application requiring a server environment. For a live demo, deploy to:*
- Heroku (with ClearDB MySQL addon)
- 000webhost (free PHP hosting)
- InfinityFree (free PHP hosting)

## 👨‍💻 Developer

**Raghavendra Singh Bhajeri**
- GitHub: [@RAGHAVENDRASINGBHAJERI](https://github.com/RAGHAVENDRASINGBHAJERI)
- Email: raghavendrasingbhajeri@gmail.com

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 🔮 Future Enhancements

- [ ] Add file upload for pet photos
- [ ] Email notifications for new applications
- [ ] Search and filtering capabilities
- [ ] Admin dashboard with analytics
- [ ] Application status tracking
- [ ] REST API endpoints
- [ ] Unit testing implementation

---

⭐ **Star this repository if you found it helpful!**
