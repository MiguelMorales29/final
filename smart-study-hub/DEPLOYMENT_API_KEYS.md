# Complete API Keys & Environment Variables Guide for Render Deployment

This guide covers **ALL** environment variables and API keys needed to deploy Smart Study Hub on Render.com with full functionality.

---

## 📋 Table of Contents

1. [Required Database Variables](#required-database-variables)
2. [Required Application Variables](#required-application-variables)
3. [Smart Buddy AI (Groq API)](#smart-buddy-ai-groq-api)
4. [Google OAuth (Optional)](#google-oauth-optional)
5. [Email Configuration (Optional)](#email-configuration-optional)
6. [AWS S3 Storage (Optional)](#aws-s3-storage-optional)
7. [Complete Environment Variables List](#complete-environment-variables-list)
8. [How to Add Variables in Render](#how-to-add-variables-in-render)

---

## 🔴 Required Database Variables

These are **already set** from your PostgreSQL database setup:

```env
DB_CONNECTION=pgsql
DB_HOST=dpg-d4g5u26fu37c739osgd0-a.singapore-postgres.render.com
DB_PORT=5432
DB_DATABASE=smart_study_hub
DB_USERNAME=smart_study_hub_user
DB_PASSWORD=OeCUsQNjndb01aLNkOYrdkLY0kI0W1dn
```

✅ **Status**: Already configured in your Render environment.

---

## 🔴 Required Application Variables

These are **already set**:

```env
APP_NAME=Smart Study Hub
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:1c3RPtQPHNU+V5B/A/Jgsx6hKhCdZYyTj2f9XQac1dc=
APP_URL=https://smart-study-hub-a5xr.onrender.com
```

✅ **Status**: Already configured in your Render environment.

---

## 🟡 Smart Buddy AI (Groq API) - **REQUIRED FOR SMART BUDDY**

Smart Buddy is the AI assistant feature that helps students with questions. You need a Groq API key to enable it.

### Step 1: Get Groq API Key

1. Go to [https://console.groq.com/](https://console.groq.com/)
2. Sign up or log in
3. Navigate to **API Keys** section
4. Click **Create API Key**
5. Copy the API key (starts with `gsk_...`)

### Step 2: Add to Render

1. Go to your Render dashboard → `smart-study-hub` → **Environment**
2. Click **Add Environment Variable**
3. Add:
   ```
   KEY: GROQ_API_KEY
   VALUE: gsk_your_actual_api_key_here
   ```
4. Click **Save**

### Environment Variable:

```env
GROQ_API_KEY=gsk_your_actual_api_key_here
```

⚠️ **Note**: Without this key, Smart Buddy will not work. The app will still run, but AI features will be disabled.

---

## 🟢 Google OAuth (Optional but Recommended)

Google OAuth allows users to sign in with their Google account.

### Step 1: Create Google OAuth Credentials

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing
3. Enable **Google+ API**
4. Go to **Credentials** → **Create Credentials** → **OAuth 2.0 Client ID**
5. Choose **Web application**
6. Add **Authorized redirect URIs**:
   ```
   https://smart-study-hub-a5xr.onrender.com/auth/google/callback
   ```
7. Copy **Client ID** and **Client Secret**

### Step 2: Add to Render

1. Go to your Render dashboard → `smart-study-hub` → **Environment**
2. Add two variables:
   ```
   KEY: GOOGLE_CLIENT_ID
   VALUE: your_google_client_id_here
   
   KEY: GOOGLE_CLIENT_SECRET
   VALUE: your_google_client_secret_here
   ```
3. Click **Save**

### Environment Variables:

```env
GOOGLE_CLIENT_ID=your_google_client_id_here
GOOGLE_CLIENT_SECRET=your_google_client_secret_here
GOOGLE_REDIRECT_URI=https://smart-study-hub-a5xr.onrender.com/auth/google/callback
```

⚠️ **Note**: If you don't set these, Google OAuth login will not work, but regular email/password registration will still work.

---

## 🟢 Email Configuration (Optional)

Email is used for notifications, password resets, etc. You can use a service like Mailtrap, SendGrid, or SMTP.

### Option 1: Mailtrap (Testing)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@smartstudyhub.com
MAIL_FROM_NAME="Smart Study Hub"
```

### Option 2: SendGrid

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your_sendgrid_api_key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@smartstudyhub.com
MAIL_FROM_NAME="Smart Study Hub"
```

### Option 3: Gmail SMTP

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="Smart Study Hub"
```

⚠️ **Note**: If not configured, Laravel will use the `log` driver (emails saved to log files).

---

## 🟢 AWS S3 Storage (Optional)

If you want to store files (images, videos, PDFs) on AWS S3 instead of local storage.

### Step 1: Create AWS S3 Bucket

1. Go to [AWS Console](https://console.aws.amazon.com/)
2. Create S3 bucket
3. Create IAM user with S3 access
4. Get **Access Key ID** and **Secret Access Key**

### Step 2: Add to Render

```env
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your_aws_access_key
AWS_SECRET_ACCESS_KEY=your_aws_secret_key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your_bucket_name
AWS_URL=https://your_bucket_name.s3.amazonaws.com
```

⚠️ **Note**: If not configured, files will be stored locally (default: `local`).

---

## 📝 Complete Environment Variables List

Here's the **complete list** of all environment variables for your reference:

### ✅ Already Set (Required)

```env
# Database
DB_CONNECTION=pgsql
DB_HOST=dpg-d4g5u26fu37c739osgd0-a.singapore-postgres.render.com
DB_PORT=5432
DB_DATABASE=smart_study_hub
DB_USERNAME=smart_study_hub_user
DB_PASSWORD=OeCUsQNjndb01aLNkOYrdkLY0kI0W1dn

# Application
APP_NAME=Smart Study Hub
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:1c3RPtQPHNU+V5B/A/Jgsx6hKhCdZYyTj2f9XQac1dc=
APP_URL=https://smart-study-hub-a5xr.onrender.com

# Cache & Session
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=error

# Filesystem
FILESYSTEM_DISK=local
```

### 🟡 Need to Add (For Full Functionality)

```env
# Smart Buddy AI (REQUIRED for AI features)
GROQ_API_KEY=gsk_your_groq_api_key_here

# Google OAuth (Optional - for Google login)
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=https://smart-study-hub-a5xr.onrender.com/auth/google/callback

# Email (Optional - for notifications)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@smartstudyhub.com
MAIL_FROM_NAME="Smart Study Hub"

# AWS S3 (Optional - for cloud storage)
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your_aws_key
AWS_SECRET_ACCESS_KEY=your_aws_secret
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your_bucket_name
AWS_URL=https://your_bucket_name.s3.amazonaws.com
```

---

## 🚀 How to Add Variables in Render

### Step-by-Step:

1. **Go to Render Dashboard**
   - Navigate to [dashboard.render.com](https://dashboard.render.com)
   - Click on your `smart-study-hub` web service

2. **Open Environment Tab**
   - Click **"Environment"** in the left sidebar (under "MANAGE")

3. **Add New Variable**
   - Click **"Add Environment Variable"** button
   - Enter **KEY** (e.g., `GROQ_API_KEY`)
   - Enter **VALUE** (e.g., `gsk_your_key_here`)
   - Click **"Save"** or **"Save, rebuild, and deploy"**

4. **Wait for Deployment**
   - Render will automatically rebuild and deploy
   - Check the **"Events"** or **"Logs"** tab to monitor progress

---

## ✅ Priority Checklist

### Must Have (App won't work without these):
- ✅ Database variables (already set)
- ✅ APP_KEY, APP_URL, etc. (already set)

### Should Have (Core features):
- 🟡 **GROQ_API_KEY** - Required for Smart Buddy AI assistant

### Nice to Have (Enhanced features):
- 🟢 **GOOGLE_CLIENT_ID/SECRET** - Google OAuth login
- 🟢 **MAIL_*** - Email notifications
- 🟢 **AWS_*** - Cloud file storage

---

## 🔍 Testing After Adding Variables

1. **Test Smart Buddy**:
   - Log in as a student
   - Navigate to a course
   - Try asking Smart Buddy a question
   - If it works, you'll see AI responses

2. **Test Google OAuth**:
   - Go to login page
   - Click "Sign in with Google"
   - Should redirect to Google and back

3. **Test Email**:
   - Try password reset
   - Check if email is sent (or logged if using `log` driver)

---

## 📞 Need Help?

If you encounter issues:

1. Check **Render Logs** tab for error messages
2. Verify all environment variables are set correctly
3. Ensure API keys are valid and not expired
4. Check that redirect URIs match your Render URL

---

## 🎯 Quick Start (Minimum Setup)

**Minimum to get app working:**
1. ✅ Database variables (already done)
2. ✅ APP_* variables (already done)
3. 🟡 Add `GROQ_API_KEY` for Smart Buddy

**That's it!** The app will work with these. Other features are optional enhancements.

---

**Last Updated**: November 2025
**For**: Smart Study Hub v1.0
**Deployment Platform**: Render.com

