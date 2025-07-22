# 🚀 TaxTrek Project Guide

## 📋 Quick Overview
This guide covers everything you need to know about your TaxTrek project deployment and development workflow.

## 🏠 Local Development
- **Location**: `/Applications/XAMPP/xamppfiles/htdocs/flutterapps/api/taxtrek`
- **Local URL**: `http://localhost/taxtrek/`
- **Database**: MySQL via XAMPP

## 🌐 Production Deployment
- **Live URL**: https://aplikasi-io.com/taskforce/
- **VM**: Google Cloud Compute Engine
- **Auto-Deploy**: GitHub Actions → VM

## 🔄 Development Workflow

### 1. Make Changes Locally
```bash
# Work on your files in XAMPP
# Test locally at http://localhost/taxtrek/
```

### 2. Commit & Push
```bash
git add .
git commit -m "Your changes description"
git push origin main
```

### 3. Auto-Deploy
- GitHub Actions automatically deploys to production
- Check status: https://github.com/mrpixel04/taxtrek/actions
- Live site updates in ~2-3 minutes

## 🔧 SSH Access to VM

### Connect to VM
```bash
gcloud compute ssh apps-io-server --zone=asia-southeast1-a --project=serverdev-428904
```

### Exit SSH
```bash
exit
```

### Useful VM Commands
```bash
# Check deployment status
ls -la /var/www/html/taskforce/

# Check file permissions
ls -la /var/www/html/taskforce/index.php

# View Nginx logs
sudo tail -f /var/log/nginx/error.log

# Restart Nginx if needed
sudo systemctl restart nginx
```

## 📁 Project Structure
```
taxtrek/
├── index.php              # Main entry point
├── dashboard.php          # Dashboard page
├── data.php              # Data management
├── classes/              # PHP classes
├── api/                  # API endpoints
├── images/               # Static images
├── tcpdf/                # PDF generation
├── vendor/               # Dependencies
└── .github/workflows/    # Auto-deployment
```

## 🚨 Troubleshooting

### Website Not Loading
1. **Check GitHub Actions**: https://github.com/mrpixel04/taxtrek/actions
2. **SSH to VM** and check files:
   ```bash
   ls -la /var/www/html/taskforce/
   ```
3. **Check permissions**:
   ```bash
   sudo chown -R www-data:www-data /var/www/html/taskforce
   sudo chmod -R 755 /var/www/html/taskforce
   ```

### Auto-Deploy Failing
1. Check GitHub Secrets are set:
   - `GCP_PROJECT_ID`: `serverdev-428904`
   - `GCP_SERVICE_ACCOUNT_KEY`: Your JSON key
2. Check Actions logs for specific errors

### Local Development Issues
1. **XAMPP not starting**: Check if ports 80/443 are free
2. **Database connection**: Verify MySQL is running in XAMPP
3. **File permissions**: Ensure XAMPP can read project files

## 🔐 Important Files
- `.github/workflows/deploy.yml` - Auto-deployment configuration
- `.gitignore` - Prevents sensitive files from being committed
- `composer.json` - PHP dependencies
- `README.md` - Project overview
- `DEPLOYMENT.md` - Detailed deployment setup

## 📞 Quick Commands Reference

### Git Operations
```bash
git status                    # Check current status
git add .                     # Stage all changes
git commit -m "message"       # Commit changes
git push origin main          # Push to GitHub
```

### Google Cloud
```bash
gcloud auth login             # Login to Google Cloud
gcloud config set project serverdev-428904  # Set project
gcloud compute instances list # List VMs
```

### Local Development
```bash
# Start XAMPP
# Access: http://localhost/taxtrek/

# Check PHP version
php -v

# Install dependencies (if needed)
composer install
```

## 🎯 Next Steps
1. **Develop features** locally in XAMPP
2. **Test thoroughly** before pushing
3. **Push to GitHub** for auto-deployment
4. **Monitor** production site

## 📞 Support
- **GitHub Issues**: https://github.com/mrpixel04/taxtrek/issues
- **Google Cloud Console**: https://console.cloud.google.com/
- **GitHub Actions**: https://github.com/mrpixel04/taxtrek/actions

---

**Last Updated**: January 2025  
**Project**: TaxTrek - Tax Management System  
**Status**: ✅ Production Ready 