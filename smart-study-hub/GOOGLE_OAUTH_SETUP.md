# Google OAuth 2.0 Setup Instructions

## Environment Variables Required

Add these variables to your `.env` file:

```env
# Google OAuth Configuration
GOOGLE_CLIENT_ID=your_google_client_id_here
GOOGLE_CLIENT_SECRET=your_google_client_secret_here
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

## Google Cloud Console Setup

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one
3. Enable the Google+ API
4. Go to "Credentials" → "Create Credentials" → "OAuth 2.0 Client IDs"
5. Set the application type to "Web application"
6. Add authorized redirect URIs:
   - `http://localhost:8000/auth/google/callback` (for local development)
   - `https://yourdomain.com/auth/google/callback` (for production)
7. Copy the Client ID and Client Secret to your `.env` file

## Features Implemented

✅ **Google OAuth Integration**
- Laravel Socialite package installed
- Google OAuth controller created
- Routes configured for authentication
- Login and registration forms updated with Google sign-in buttons

✅ **User Management**
- Automatic user creation for new Google users
- Existing user login for returning Google users
- Profile picture integration from Google
- Email verification (Google emails are pre-verified)

✅ **Role Management**
- New Google users default to 'student' role
- Existing users maintain their current role
- Automatic redirection based on user role

## Usage

Users can now:
1. Click "Continue with Google" on login/registration pages
2. Sign in with their Gmail account
3. Automatically be logged into their appropriate dashboard
4. Have their profile picture synced from Google

## Security Notes

- Google OAuth provides secure authentication
- User emails are verified through Google
- Random passwords are generated for Google users
- All standard Laravel security features remain intact













