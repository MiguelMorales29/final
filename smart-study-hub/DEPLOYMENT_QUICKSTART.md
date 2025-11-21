# 🚀 Quick Deployment Guide - 5 Minutes

## Option 1: Render (Easiest) ⭐

### Step 1: Push to GitHub
```bash
git add .
git commit -m "Ready for deployment"
git push origin main
```

### Step 2: Deploy on Render
1. Go to https://render.com
2. Sign up with GitHub
3. Click "New +" → "Web Service"
4. Connect your repository: `smart-study-hub`
5. Use these settings:
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

### Step 3: Add Database
1. Click "New +" → "PostgreSQL"
2. Name: `smart-study-hub-db`
3. Copy the **Internal Database URL**

### Step 4: Set Environment Variables
In your web service → Environment → Add these:

```
APP_NAME=Smart Study Hub
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app-name.onrender.com

DB_CONNECTION=pgsql
DB_HOST=<from-database-url>
DB_PORT=5432
DB_DATABASE=<from-database-url>
DB_USERNAME=<from-database-url>
DB_PASSWORD=<from-database-url>
```

**Generate APP_KEY:**
```bash
# Run locally or in Render shell
php artisan key:generate --show
# Copy the output and add as APP_KEY
```

### Step 5: Deploy & Run Migrations
1. Click "Manual Deploy" → "Deploy latest commit"
2. Wait for build to complete
3. Go to Shell tab
4. Run:
   ```bash
   php artisan migrate --force
   php artisan storage:link
   ```

### Step 6: Done! 🎉
Your app should be live at: `https://your-app-name.onrender.com`

---

## Option 2: Railway (Alternative)

### Step 1: Install Railway CLI
```bash
npm i -g @railway/cli
```

### Step 2: Deploy
```bash
railway login
railway init
railway add --database mysql
railway up
```

### Step 3: Set Variables
```bash
railway variables
# Add all variables from .env.example
```

### Step 4: Run Migrations
```bash
railway run php artisan migrate --force
railway run php artisan storage:link
```

---

## ⚠️ Important Before Deploying

1. **Build assets locally first:**
   ```bash
   npm run build
   git add public/build
   git commit -m "Add built assets"
   git push
   ```

2. **Generate APP_KEY:**
   ```bash
   php artisan key:generate --show
   # Save this for environment variables
   ```

3. **Test locally:**
   ```bash
   php artisan serve
   # Make sure everything works
   ```

---

## 🐛 Troubleshooting

**Build fails?**
- Check PHP version (needs 8.2+)
- Ensure all dependencies in composer.json

**Database error?**
- Verify database credentials
- Check database is created
- Run migrations in shell

**Assets not loading?**
- Ensure `npm run build` completed
- Check `APP_URL` is correct
- Verify `public/build` exists

**500 Error?**
- Check logs in Render dashboard
- Verify APP_KEY is set
- Check file permissions

---

## 📝 Environment Variables Checklist

Required:
- [ ] APP_NAME
- [ ] APP_ENV=production
- [ ] APP_KEY (generate with `php artisan key:generate --show`)
- [ ] APP_DEBUG=false
- [ ] APP_URL (your deployment URL)
- [ ] DB_CONNECTION
- [ ] DB_HOST
- [ ] DB_PORT
- [ ] DB_DATABASE
- [ ] DB_USERNAME
- [ ] DB_PASSWORD

Optional:
- [ ] GOOGLE_CLIENT_ID (if using Google OAuth)
- [ ] GOOGLE_CLIENT_SECRET
- [ ] OPENAI_API_KEY (if using Smart Buddy)

---

## ✅ Post-Deployment Checklist

- [ ] App loads at URL
- [ ] Can register new user
- [ ] Can login
- [ ] Database works
- [ ] File uploads work
- [ ] Images display
- [ ] All pages accessible
- [ ] No console errors

---

**Need help?** Check the full `DEPLOYMENT_GUIDE.md` for detailed instructions.


