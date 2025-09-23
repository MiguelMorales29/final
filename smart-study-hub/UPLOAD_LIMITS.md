# File Upload Configuration

## Large File Upload Support

This project is configured to support very large file uploads (up to 2GB) for educational materials like PPT presentations, PDFs, videos, and other large files.

### Configuration Files

- `php.ini` - Custom PHP configuration with increased limits
- `start-server.sh` - Script to start server with correct configuration
- `.htaccess` - Apache configuration (for production)

### Current Limits

- **Upload Max Filesize**: 2GB
- **Post Max Size**: 2GB  
- **Memory Limit**: 2GB
- **Max Execution Time**: 30 minutes (1800 seconds)

### Starting the Server

For development with large file support:

```bash
# Option 1: Use the startup script
./start-server.sh

# Option 2: Manual command
php -c php.ini artisan serve --host=0.0.0.0 --port=8000
```

### Production Deployment

For production, ensure your web server (Apache/Nginx) is configured with similar limits:

```apache
# Apache (.htaccess)
php_value upload_max_filesize 2G
php_value post_max_size 2G
php_value memory_limit 2G
php_value max_execution_time 1800
```

```nginx
# Nginx
client_max_body_size 2G;
```

### Troubleshooting

If you still get "Content Too Large" errors:

1. Check PHP configuration: `php -c php.ini -i | grep -E "(upload_max_filesize|post_max_size)"`
2. Restart the server with the custom configuration
3. Clear browser cache and try again
4. Check Laravel validation limits in `CourseController.php`
