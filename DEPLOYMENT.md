# Auto-Deployment Setup Guide

This document explains how to set up automatic deployment from GitHub to your Google Cloud VM.

## 🚀 How Auto-Deploy Works

When you push code to the `main` branch, GitHub Actions will:
1. Package your code
2. Transfer it to your Google Cloud VM (`apps-io-server`)
3. Deploy it to `/var/www/html/taskforce/`
4. Set proper permissions
5. Make it live at `https://aplikasi-io.com/taskforce`

## 🔐 Required GitHub Secrets

You need to set up these secrets in your GitHub repository:

### 1. Go to Repository Settings
- Navigate to [https://github.com/mrpixel04/taxtrek/settings/secrets/actions](https://github.com/mrpixel04/taxtrek/settings/secrets/actions)
- Click "New repository secret"

### 2. Add Required Secrets

#### `GCP_PROJECT_ID`
- **Value**: `serverdev-428904`
- **Description**: Your Google Cloud project ID

#### `GCP_SERVICE_ACCOUNT_KEY`
- **Description**: Google Cloud service account key (JSON format)
- **How to get it**:
  1. Go to [Google Cloud Console](https://console.cloud.google.com/)
  2. Navigate to IAM & Admin → Service Accounts
  3. Create new service account or use existing one
  4. Grant these roles:
     - Compute Instance Admin (v1)
     - Service Account User
  5. Create a JSON key
  6. Copy the entire JSON content and paste as secret value

## 📋 Setting Up Service Account

### Option 1: Use Existing Credentials
If you're already authenticated with gcloud, you can create a service account:

```bash
# Create service account
gcloud iam service-accounts create github-deploy \
  --display-name="GitHub Auto Deploy"

# Grant necessary permissions
gcloud projects add-iam-policy-binding serverdev-428904 \
  --member="serviceAccount:github-deploy@serverdev-428904.iam.gserviceaccount.com" \
  --role="roles/compute.instanceAdmin.v1"

gcloud projects add-iam-policy-binding serverdev-428904 \
  --member="serviceAccount:github-deploy@serverdev-428904.iam.gserviceaccount.com" \
  --role="roles/iam.serviceAccountUser"

# Create JSON key
gcloud iam service-accounts keys create key.json \
  --iam-account=github-deploy@serverdev-428904.iam.gserviceaccount.com
```

### Option 2: Manual Setup via Console
1. Go to [Google Cloud Console](https://console.cloud.google.com/iam-admin/serviceaccounts?project=serverdev-428904)
2. Click "Create Service Account"
3. Name: `github-deploy`
4. Grant roles: `Compute Instance Admin (v1)` and `Service Account User`
5. Create JSON key
6. Download and copy the JSON content

## 🧪 Testing the Deployment

1. **Set up the secrets** as described above
2. **Make a small change** to any file (like README.md)
3. **Commit and push**:
   ```bash
   git add .
   git commit -m "Test auto-deployment"
   git push origin main
   ```
4. **Check GitHub Actions**: Go to [https://github.com/mrpixel04/taxtrek/actions](https://github.com/mrpixel04/taxtrek/actions)
5. **Verify deployment**: Visit `https://aplikasi-io.com/taskforce`

## 🛠️ Development Workflow

Once set up, your workflow becomes:

1. **Develop locally** in XAMPP: `http://localhost/taxtrek/`
2. **Test your changes**
3. **Commit and push** to GitHub
4. **Automatic deployment** to production
5. **Verify** at `https://aplikasi-io.com/taskforce`

## 🚨 Troubleshooting

### If deployment fails:
1. Check GitHub Actions logs
2. Verify your VM is running
3. Ensure secrets are properly set
4. Check VM firewall rules

### Manual deployment fallback:
If needed, you can always deploy manually using the commands we used earlier.

## 📝 Notes

- The workflow backs up your current deployment before updating
- Only pushes to `main` branch trigger deployment
- You can also trigger deployment manually from GitHub Actions tab
- Deployment typically takes 2-3 minutes 