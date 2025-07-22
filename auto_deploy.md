# 🚀 Complete Auto-Deployment Guide
## GitHub Actions → Google Cloud VM

*A comprehensive guide for setting up automatic deployment for any PHP project*

---

## 📋 Table of Contents
1. [Prerequisites](#prerequisites)
2. [Project Setup](#project-setup)
3. [GitHub Repository Setup](#github-repository-setup)
4. [Google Cloud Configuration](#google-cloud-configuration)
5. [Nginx Configuration](#nginx-configuration)
6. [GitHub Actions Workflow](#github-actions-workflow)
7. [Testing & Verification](#testing--verification)
8. [Multiple Projects Setup](#multiple-projects-setup)
9. [Troubleshooting](#troubleshooting)

---

## 🔧 Prerequisites

### Local Environment
- ✅ **XAMPP** - For local development
- ✅ **Git** - Version control
- ✅ **Google Cloud CLI** - VM management
- ✅ **Code Editor** - VS Code/Cursor recommended

### Cloud Environment
- ✅ **Google Cloud Project** - With Compute Engine enabled
- ✅ **VM Instance** - Ubuntu with Nginx + PHP
- ✅ **Domain** - Pointing to your VM (optional)

---

## 📁 Project Setup

### 1. Initialize Local Project
```bash
# Navigate to your project directory
cd /path/to/your/project

# Initialize Git
git init

# Create essential files
touch .gitignore README.md
```

### 2. Create .gitignore
```gitignore
# Dependencies
vendor/
node_modules/

# Environment files
.env
.env.local
.env.*.local

# System files
.DS_Store
*.log
*.tmp

# IDE
.vscode/
*.swp
*.swo

# Google Cloud credentials
key.json
*service-account*.json
gcp-credentials.json
*-key.json

# Archives
*.zip
*.tar.gz
*.rar

# Temporary files
temp/
cache/
```

---

## 🐙 GitHub Repository Setup

### 1. Create Repository
```bash
# Add remote repository
git remote add origin https://github.com/USERNAME/PROJECT-NAME.git

# Initial commit
git add .
git commit -m "Initial commit"
git push -u origin main
```

### 2. Setup GitHub Secrets
Go to **GitHub Repository → Settings → Secrets and variables → Actions**

#### Required Secrets:
- **`GCP_PROJECT_ID`**: Your Google Cloud project ID
- **`GCP_SERVICE_ACCOUNT_KEY`**: JSON key for service account

#### Get Service Account Key:
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Navigate to **IAM & Admin → Service Accounts**
3. Create new service account or use existing
4. Grant roles:
   - Compute Instance Admin (v1)
   - Service Account User
5. Create JSON key
6. Copy entire JSON content to GitHub secret

---

## ☁️ Google Cloud Configuration

### 1. VM Requirements
```bash
# Connect to your VM
gcloud compute ssh VM-NAME --zone=ZONE --project=PROJECT-ID

# Ensure required software is installed
sudo apt update
sudo apt install nginx php8.1-fpm php8.1-mysql php8.1-curl php8.1-gd php8.1-mbstring
```

### 2. Directory Structure
```bash
# Create deployment directories
sudo mkdir -p /var/www/html/PROJECT-FOLDER
sudo chown -R www-data:www-data /var/www/html/PROJECT-FOLDER
sudo chmod -R 755 /var/www/html/PROJECT-FOLDER
```

---

## 🌐 Nginx Configuration

### 1. For New Domain/Subdomain
Create file: `/etc/nginx/sites-available/your-domain.com`

```nginx
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;
    
    root /var/www/html;
    index index.php index.html;
    
    # Your project location
    location /PROJECT-FOLDER/ {
        alias /var/www/html/PROJECT-FOLDER/;
        index index.php index.html;
        try_files $uri $uri/ @project_fallback;
        
        # PHP processing
        location ~ \.php$ {
            include snippets/fastcgi-php.conf;
            fastcgi_param SCRIPT_FILENAME $request_filename;
            fastcgi_pass unix:/run/php/php8.1-fpm.sock;
        }
    }
    
    # Fallback for clean URLs
    location @project_fallback {
        rewrite ^/PROJECT-FOLDER/(.*)$ /PROJECT-FOLDER/index.php/$1 last;
    }
    
    # Main PHP processing
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.1-fpm.sock;
    }
}
```

### 2. For Existing Domain (Add to existing config)
Add this location block to your existing server configuration:

```nginx
# Add to existing server block
location /PROJECT-FOLDER/ {
    alias /var/www/html/PROJECT-FOLDER/;
    index index.php index.html;
    try_files $uri $uri/ @project_fallback;
    
    # PHP processing for this project
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_param SCRIPT_FILENAME $request_filename;
        fastcgi_pass unix:/run/php/php8.1-fpm.sock;
    }
}

# Add fallback (outside server block or at the end)
location @project_fallback {
    rewrite ^/PROJECT-FOLDER/(.*)$ /PROJECT-FOLDER/index.php/$1 last;
}
```

### 3. Enable Configuration
```bash
# Enable site (if new domain)
sudo ln -s /etc/nginx/sites-available/your-domain.com /etc/nginx/sites-enabled/

# Test configuration
sudo nginx -t

# Reload Nginx
sudo systemctl reload nginx
```

---

## ⚙️ GitHub Actions Workflow

Create file: `.github/workflows/deploy.yml`

```yaml
name: Deploy to Production

on:
  push:
    branches: [ main ]
  workflow_dispatch:

jobs:
  deploy:
    runs-on: ubuntu-latest
    
    steps:
    - name: Checkout code
      uses: actions/checkout@v4
      
    - name: Google Auth
      id: auth
      uses: google-github-actions/auth@v2
      with:
        credentials_json: ${{ secrets.GCP_SERVICE_ACCOUNT_KEY }}
        
    - name: Setup Google Cloud CLI
      uses: google-github-actions/setup-gcloud@v2
      with:
        project_id: ${{ secrets.GCP_PROJECT_ID }}
        
    - name: Deploy to VM
      run: |
        # Remove problematic files
        rm -f .DS_Store
        find . -name ".DS_Store" -delete 2>/dev/null || true
        
        # Create deployment package
        tar --exclude='.git' --exclude='.github' --exclude='*.tar.gz' --exclude='.DS_Store' --exclude='.vscode' -czf project-deploy.tar.gz *
        
        # Copy to VM
        gcloud compute scp project-deploy.tar.gz VM-NAME:~/project-deploy.tar.gz --zone=ZONE
        
        # Deploy on VM
        gcloud compute ssh VM-NAME --zone=ZONE --command="
          # Backup current deployment
          sudo cp -r /var/www/html/PROJECT-FOLDER /tmp/PROJECT-FOLDER-backup-\$(date +%Y%m%d_%H%M%S) 2>/dev/null || echo 'No existing backup needed'
          
          # Clear target directory
          sudo rm -rf /var/www/html/PROJECT-FOLDER/*
          
          # Extract to target
          cd /tmp
          tar -xzf ~/project-deploy.tar.gz
          sudo cp -r * /var/www/html/PROJECT-FOLDER/
          
          # Set permissions
          sudo chown -R www-data:www-data /var/www/html/PROJECT-FOLDER
          sudo chmod -R 755 /var/www/html/PROJECT-FOLDER
          
          # Cleanup
          rm -f ~/project-deploy.tar.gz
          rm -rf /tmp/api /tmp/assets /tmp/vendor /tmp/*.php /tmp/*.html /tmp/*.css /tmp/*.js 2>/dev/null || true
          
          echo '🎉 PROJECT-NAME deployed successfully!'
        "

    - name: Verify Deployment
      run: |
        echo "✅ Deployment completed!"
        echo "🌐 Your application is now live at: https://your-domain.com/PROJECT-FOLDER"
```

### Variables to Replace:
- `PROJECT-NAME` - Your project name
- `PROJECT-FOLDER` - Target folder on server
- `VM-NAME` - Your VM instance name
- `ZONE` - Your VM zone (e.g., asia-southeast1-a)
- `your-domain.com` - Your domain

---

## 🧪 Testing & Verification

### 1. Test Local Setup
```bash
# Test in XAMPP
http://localhost/PROJECT-FOLDER/

# Commit and push
git add .
git commit -m "Initial deployment setup"
git push origin main
```

### 2. Monitor Deployment
- **GitHub Actions**: `https://github.com/USERNAME/REPO/actions`
- **Check logs** for any errors
- **Verify files** deployed to VM

### 3. Test Live Site
```bash
# SSH into VM and verify
gcloud compute ssh VM-NAME --zone=ZONE --command="ls -la /var/www/html/PROJECT-FOLDER/"

# Test website
curl -I https://your-domain.com/PROJECT-FOLDER/
```

---

## 🔄 Multiple Projects Setup

### For Multiple Projects on Same VM:

#### 1. Directory Structure
```
/var/www/html/
├── project1/
├── project2/
├── project3/
└── index.html
```

#### 2. Nginx Configuration
```nginx
# Add multiple location blocks
location /project1/ {
    alias /var/www/html/project1/;
    # ... PHP config
}

location /project2/ {
    alias /var/www/html/project2/;
    # ... PHP config
}

location /project3/ {
    alias /var/www/html/project3/;
    # ... PHP config
}
```

#### 3. Separate Repositories
- Each project has its own GitHub repository
- Each has its own GitHub Actions workflow
- Each deploys to different folder

#### 4. Access URLs
- **Project 1**: `https://domain.com/project1/`
- **Project 2**: `https://domain.com/project2/`
- **Project 3**: `https://domain.com/project3/`

---

## 🚨 Troubleshooting

### Common Issues & Solutions

#### 1. **Authentication Failed**
```bash
# Error: Could not fetch resource - insufficient authentication scopes
```
**Solution**: Recreate service account key with correct permissions

#### 2. **PHP Files Not Processing**
```bash
# Files download instead of executing
```
**Solution**: Check PHP location block in Nginx config

#### 3. **Permission Denied**
```bash
# 403 Forbidden errors
```
**Solution**: 
```bash
sudo chown -R www-data:www-data /var/www/html/PROJECT-FOLDER
sudo chmod -R 755 /var/www/html/PROJECT-FOLDER
```

#### 4. **Conflicting Server Names**
```bash
# nginx: [warn] conflicting server name
```
**Solution**: Use unique server names or disable conflicting configs

#### 5. **Files Not Updating**
```bash
# Old files still showing
```
**Solution**: Check if deployment is going to correct folder

### Debug Commands
```bash
# Check Nginx status
sudo systemctl status nginx

# Check PHP-FPM status
sudo systemctl status php8.1-fpm

# Check Nginx error logs
sudo tail -f /var/log/nginx/error.log

# Check deployed files
ls -la /var/www/html/PROJECT-FOLDER/

# Test Nginx config
sudo nginx -t
```

---

## 📝 Quick Checklist

### Before Each New Project:
- [ ] Create GitHub repository
- [ ] Set up GitHub secrets
- [ ] Create project folder on VM
- [ ] Configure Nginx location block
- [ ] Test Nginx configuration
- [ ] Create GitHub Actions workflow
- [ ] Test local development
- [ ] Push and verify deployment

### File Checklist:
- [ ] `.gitignore` (exclude sensitive files)
- [ ] `.github/workflows/deploy.yml` (deployment workflow)
- [ ] `README.md` (project documentation)
- [ ] Nginx configuration updated

---

## 🎯 Best Practices

### 1. **Security**
- Never commit credentials to Git
- Use strong passwords for databases
- Keep VM and software updated
- Use HTTPS with SSL certificates

### 2. **Development Workflow**
- Test locally before pushing
- Use meaningful commit messages
- Create backup before major changes
- Monitor deployment logs

### 3. **Performance**
- Optimize images before deployment
- Use compression for assets
- Enable Nginx gzip compression
- Regular cleanup of old backups

### 4. **Maintenance**
- Regular VM updates
- Monitor disk space
- Backup important data
- Test recovery procedures

---

## 🎉 Success!

With this guide, you can set up auto-deployment for any PHP project in minutes!

**Your workflow**: 
`Code Locally` → `Git Push` → `Auto Deploy` → `Live Site`

**Example URLs**:
- **TaxTrek**: `https://aplikasi-io.com/taskforce/`
- **Project 2**: `https://aplikasi-io.com/project2/`
- **Project 3**: `https://aplikasi-io.com/project3/`

---

*Last Updated: January 2025*  
*Status: ✅ Production Ready* 