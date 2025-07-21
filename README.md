# TaxTrek - Tax Management System

A PHP-based tax management and document processing system.

## Features
- Tax document management
- PDF report generation using TCPDF
- Image processing and editing
- Data upload and management
- Print notification system

## Requirements
- PHP 7.4 or higher
- MySQL/MariaDB
- Web server (Apache/Nginx)
- TCPDF library
- PHPExcel library

## Installation

### Local Development
1. Clone this repository
2. Place in your web server directory (e.g., XAMPP htdocs)
3. Import database if available
4. Configure database connection in `classes/conn.php`
5. Access via `http://localhost/taxtrek`

### Production Deployment
This project is set up for automatic deployment to Google Cloud VM via GitHub Actions.

## File Structure
- `api/` - API endpoints
- `classes/` - Core PHP classes and database connection
- `images/` - Static images
- `tcpdf/` - PDF generation library
- `vendor/` - Composer dependencies
- Main PHP files for different functionalities

## Development
1. Make changes locally
2. Test on localhost
3. Push to GitHub for automatic deployment

## Auto-Deploy Setup
This repository is configured for automatic deployment to:
- **Production**: https://aplikasi-io.com/taskforce

Push to `main` branch triggers automatic deployment to production server. 