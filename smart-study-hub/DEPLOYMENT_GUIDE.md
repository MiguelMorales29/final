# 🚀 Deployment Guide - Smart Study Hub

## Free Hosting Platform Recommendations

### **Option 1: Render (Recommended) ⭐**
- **Pros**: Easy setup, automatic SSL, PostgreSQL included, good Laravel support
- **Free Tier**: 750 hours/month, 512MB RAM
- **Best for**: Production-ready testing
- **URL**: https://render.com

### **Option 2: Railway**
- **Pros**: Very easy deployment, good free tier, MySQL/PostgreSQL available
- **Free Tier**: $5 credit/month (usually enough for testing)
- **Best for**: Quick deployment
- **URL**: https://railway.app

### **Option 3: Fly.io**
- **Pros**: Global edge deployment, good performance
- **Free Tier**: 3 shared VMs, 3GB storage
- **Best for**: Performance-focused apps
- **URL**: https://fly.io

### **Option 4: InfinityFree + Free MySQL**
- **Pros**: Completely free, unlimited bandwidth
- **Cons**: Limited features, slower
- **Best for**: Basic testing only
- **URL**: https://infinityfree.net

---

## 📋 Pre-Deployment Checklist

### 1. Prepare Your Code
- [ ] Build assets: `npm run build`
- [ ] Test locally: `php artisan serve`
- [ ] Check all features work
- [ ] Remove debug code
- [ ] Commit all changes to Git

### 2. Environment Variables
- [ ] Create `.env.example` file
- [ ] Document all required variables
- [ ] Remove sensitive data from code

### 3. Database
- [ ] Choose database (MySQL/PostgreSQL recommended for hosting)
- [ ] Prepare migration files
- [ ] Create seeders if needed

### 4. Storage
- [ ] Ensure storage link is set up
- [ ] Check file upload paths
- [ ] Verify image assets are accessible

---

## 🎯 Recommended: Render Deployment (Step-by-Step)

### Step 1: Prepare Your Repository

1. **Create a GitHub repository** (if not already done):
   ```bash
   git init
   git add .
   git commit -m "Initial commit"
   git remote add origin https://github.com/yourusername/smart-study-hub.git
   git push -u origin main
   ```

2. **Create `.env.example` file** (see template below)

3. **Create `render.yaml`** for Render configuration:
   ```yaml
   services:
     - type: web
       name: smart-study-hub
       env: php
       buildCommand: composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache && npm ci && npm run build
       startCommand: php artisan serve --host=0.0.0.0 --port=$PORT
       envVars:
         - key: APP_ENV
           value: production
         - key: APP_DEBUG
           value: false
         - key: LOG_LEVEL
           value: error
   ```

### Step 2: Set Up Render Account

1. Go to https://render.com
2. Sign up with GitHub
3. Click "New +" → "Web Service"
4. Connect your GitHub repository
5. Configure:
   - **Name**: smart-study-hub
   - **Environment**: PHP
   - **Build Command**: 
     ```
     composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache && npm ci && npm run build
     ```
   - **Start Command**: 
     ```
     php artisan serve --host=0.0.0.0 --port=$PORT
     ```

### Step 3: Add PostgreSQL Database

1. In Render dashboard: "New +" → "PostgreSQL"
2. Name: `smart-study-hub-db`
3. Copy the **Internal Database URL**

### Step 4: Configure Environment Variables

In Render dashboard → Environment → Add:

```
APP_NAME=Smart Study Hub
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY
APP_DEBUG=false
APP_URL=https://your-app.onrender.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=pgsql
DB_HOST=your-db-host
DB_PORT=5432
DB_DATABASE=your-db-name
DB_USERNAME=your-db-user
DB_PASSWORD=your-db-password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@smartstudyhub.com"
MAIL_FROM_NAME="${APP_NAME}"

# Google OAuth (if using)
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI=https://your-app.onrender.com/auth/google/callback

# OpenAI (if using Smart Buddy)
OPENAI_API_KEY=your-openai-key
```

### Step 5: Generate APP_KEY

Run locally or in Render shell:
```bash
php artisan key:generate --show
```
Copy the key and add to `APP_KEY` in Render environment variables.

### Step 6: Run Migrations

In Render dashboard → Shell:
```bash
php artisan migrate --force
```

### Step 7: Create Storage Link

```bash
php artisan storage:link
```

---

## 🚂 Alternative: Railway Deployment

### Step 1: Install Railway CLI
```bash
npm i -g @railway/cli
```

### Step 2: Login
```bash
railway login
```

### Step 3: Initialize Project
```bash
railway init
```

### Step 4: Add Database
```bash
railway add --database mysql
```

### Step 5: Deploy
```bash
railway up
```

### Step 6: Set Environment Variables
```bash
railway variables
```
Add all variables from `.env.example`

### Step 7: Run Migrations
```bash
railway run php artisan migrate --force
railway run php artisan storage:link
```

---

## 📝 Required Files to Create

### 1. `.env.example` (Create this file)

```env
APP_NAME=Smart Study Hub
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# For production, use:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=your_database
# DB_USERNAME=your_username
# DB_PASSWORD=your_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@smartstudyhub.com"
MAIL_FROM_NAME="${APP_NAME}"

# Google OAuth
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=

# OpenAI (Smart Buddy)
OPENAI_API_KEY=
```

### 2. `Procfile` (For some platforms)

```
web: php artisan serve --host=0.0.0.0 --port=$PORT
```

### 3. `render.yaml` (For Render)

```yaml
services:
  - type: web
    name: smart-study-hub
    env: php
    buildCommand: composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache && npm ci && npm run build
    startCommand: php artisan serve --host=0.0.0.0 --port=$PORT
    envVars:
      - key: APP_ENV
        value: production
      - key: APP_DEBUG
        value: false
```

---

## 🔧 Pre-Deployment Commands

Run these locally before deploying:

```bash
# Build assets
npm run build

# Optimize for production
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Test everything works
php artisan serve
```

---

## ⚠️ Important Notes

1. **Database**: Switch from SQLite to MySQL/PostgreSQL for hosting
2. **Storage**: Some free hosts don't persist storage - consider cloud storage (S3)
3. **Queue**: Free tiers usually don't support queues - use `sync` driver
4. **Cron Jobs**: Most free hosts don't support cron - remove scheduled tasks
5. **File Uploads**: Check upload limits (usually 2-10MB on free tiers)
6. **SSL**: Render and Railway provide free SSL automatically

---

## 🐛 Common Issues & Solutions

### Issue: "APP_KEY not set"
**Solution**: Generate key and add to environment variables
```bash
php artisan key:generate --show
```

### Issue: "Storage link not found"
**Solution**: Run in deployment shell
```bash
php artisan storage:link
```

### Issue: "Database connection failed"
**Solution**: 
- Check database credentials
- Ensure database is created
- Verify connection string format

### Issue: "Assets not loading"
**Solution**: 
- Ensure `npm run build` runs during deployment
- Check `APP_URL` is set correctly
- Verify `public/build` directory exists

### Issue: "Permission denied"
**Solution**: 
- Check file permissions
- Ensure storage directories are writable
- Some hosts require specific permission settings

---

## 📊 Post-Deployment Checklist

- [ ] App loads correctly
- [ ] Database migrations ran successfully
- [ ] User registration works
- [ ] File uploads work
- [ ] Images display correctly
- [ ] Email sending works (if configured)
- [ ] All routes accessible
- [ ] SSL certificate active
- [ ] Performance acceptable

---

## 🔗 Useful Links

- **Render Docs**: https://render.com/docs
- **Railway Docs**: https://docs.railway.app
- **Fly.io Docs**: https://fly.io/docs
- **Laravel Deployment**: https://laravel.com/docs/deployment

---

## 💡 Tips for Free Hosting

1. **Start with Render** - Easiest for Laravel
2. **Use PostgreSQL** - Better than MySQL on free tiers
3. **Optimize assets** - Smaller builds = faster deployment
4. **Monitor usage** - Free tiers have limits
5. **Backup database** - Export regularly
6. **Use environment variables** - Never commit secrets
7. **Test thoroughly** - Free hosts can be slower

---

## 🎯 Quick Start (Render - 5 minutes)

1. Push code to GitHub
2. Sign up at render.com
3. Connect GitHub repo
4. Add PostgreSQL database
5. Set environment variables
6. Deploy!

Good luck! 🚀


