#!/bin/bash
# Start Laravel development server with custom PHP configuration for large file uploads

echo "Starting Laravel server with custom PHP configuration..."
echo "Upload limits: 2GB"
echo "Post limits: 2GB"
echo "Memory limit: 2GB"
echo "Max execution time: 30 minutes"
echo ""

# Use system-wide PHP configuration (now includes 2GB limits)
php artisan serve --host=0.0.0.0 --port=8000
