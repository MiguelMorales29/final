# 🎓 Smart Study Hub - Learning Management System

<div align="center">

![Smart Study Hub](public/images/sshlogo.png)

**A comprehensive, AI-powered Learning Management System built with Laravel**

[![Laravel](https://img.shields.io/badge/Laravel-12.0-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

[Features](#-features) • [Smart Buddy](#-smart-buddy-ai-assistant) • [Installation](#-installation) • [Documentation](#-documentation)

</div>

---

## 📋 Table of Contents

- [Overview](#-overview)
- [Features](#-features)
- [Smart Buddy AI Assistant](#-smart-buddy-ai-assistant)
- [Progress Tracking](#-progress-tracking)
- [System Architecture](#-system-architecture)
- [Technology Stack](#-technology-stack)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Usage](#-usage)
- [API Documentation](#-api-documentation)
- [Deployment](#-deployment)
- [Contributing](#-contributing)
- [License](#-license)

---

## 🎯 Overview

**Smart Study Hub** is a modern, feature-rich Learning Management System (LMS) designed to facilitate seamless online education. The platform provides comprehensive tools for teachers to manage courses, materials, assignments, and student progress, while offering students an intuitive interface for learning, tracking progress, and interacting with AI-powered study assistance.

### Key Highlights

- ✅ **Role-Based Access Control** - Separate dashboards for Teachers and Students
- ✅ **AI-Powered Study Assistant** - Smart Buddy with Groq API integration
- ✅ **Real-Time Progress Tracking** - Material completion and course progress monitoring
- ✅ **Comprehensive Course Management** - Terms, Sub-terms, Weeks, Materials, and Assignments
- ✅ **Notification System** - Real-time notifications for all important events
- ✅ **Google OAuth Integration** - Quick sign-in with Google accounts
- ✅ **File Management** - Support for PDFs, PPTs, videos, links, and text materials
- ✅ **Assignment System** - Create, submit, grade, and track assignments
- ✅ **Attendance Tracking** - Digital roll call and attendance management
- ✅ **Calendar Integration** - Visual calendar with upcoming tasks and events

---

## ✨ Features

### 👨‍🏫 Teacher Features

#### Course Management
- **Create & Manage Courses**
  - Course creation with title, description, section, and cover image
  - Hierarchical structure: Terms → Sub-terms → Weeks
  - Course editing and deletion
  - Course visibility and enrollment settings

- **Material Management**
  - Upload multiple material types:
    - 📄 **PDF Files** - Documents, presentations
    - 🎥 **Videos** - YouTube embeds
    - 🔗 **External Links** - Web resources
    - 📝 **Text Content** - Rich text materials
  - Organize materials by week
  - Edit and delete materials
  - Material ordering and required/optional flags

- **Assignment Management**
  - Create assignments with:
    - Multiple submission types (text, file, or both)
    - File type restrictions (PDF, DOC, DOCX, TXT, images)
    - File size limits (up to 100MB)
    - Multiple file uploads (up to 10 files)
    - Due dates and point values
    - Maximum attempts (1-10)
    - Publishing control
  - View all submissions
  - Grade assignments with feedback
  - Track submission status

- **Student Management**
  - View enrolled students
  - Approve/reject course applications
  - Drop students from courses
  - Archive applications
  - Bulk operations (archive all, bulk archive)

- **Attendance System**
  - Digital roll call interface
  - Mark attendance by date
  - View attendance history
  - Filter by section
  - Monthly attendance reports

- **Announcements**
  - Create course announcements
  - Event types: Quiz, Exam, Assignment, Other
  - Set event dates
  - Calendar integration
  - Automatic notifications to enrolled students

- **Dashboard Analytics**
  - Total courses created
  - Total enrolled students
  - Pending applications count
  - Recent activity feed
  - Quick access to all features

### 👨‍🎓 Student Features

#### Course Discovery & Enrollment
- **Browse Courses**
  - View all available courses
  - Filter by category
  - Course details with teacher information
  - Enrollment status indicators

- **Course Applications**
  - Apply to courses requiring approval
  - Direct enrollment for open courses
  - Application status tracking
  - Re-apply after rejection (with cooldown)

#### Learning Experience
- **Material Access**
  - View all course materials organized by week
  - PDF viewer with inline display
  - Video playback (YouTube embeds)
  - External link access
  - Text content with rich formatting
  - Material completion tracking

- **Assignment Management**
  - View all assignments
  - Filter by course
  - Submission interface
  - File upload support
  - View grades and feedback
  - Resubmission (if allowed)

- **Progress Tracking**
  - Material completion status
  - Course progress percentage
  - Visual progress indicators
  - Completion timestamps

- **Dashboard**
  - Upcoming tasks (assignments & announcements)
  - Recent activity feed
  - Calendar view with events
  - Course progress overview
  - Quick access to enrolled courses

#### Smart Buddy Integration
- Access Smart Buddy from any material page
- Four interactive modes (see [Smart Buddy](#-smart-buddy-ai-assistant) section)

### 🔔 Notification System

#### Notification Types
- **Course Application** - Application approved/rejected
- **New Material** - Teacher uploaded new material (PDF, video, link, text)
- **New Assignment** - Assignment created and published
- **Assignment Graded** - Teacher graded submission
- **Announcement** - New course announcement
- **Quiz/Exam Reminder** - Upcoming quiz/exam notifications

#### Notification Features
- Real-time notification dropdown in navbar
- Dedicated notifications page
- Mark as read/unread
- Mark all as read
- Unread count badge
- Icon-based visual indicators
- Click to navigate to related content

### 👤 User Management

#### Authentication
- Email/password registration
- Email verification
- Password reset
- Google OAuth integration
- Role-based access (teacher/student)

#### Profile Management
- Edit profile information
- Upload profile picture
- Theme preferences
- Account deletion

---

## 🤖 Smart Buddy AI Assistant

**Smart Buddy** is an AI-powered study companion integrated into every course material page, providing personalized, contextual learning assistance.

### 🎯 Core Features

#### 1. **Personalized Experience**
- **Time-Based Greetings**
  - Morning (5am-12pm): "Good morning, [Name]! ☀️"
  - Afternoon (12pm-5pm): "Good afternoon, [Name]! 👋"
  - Evening (5pm-9pm): "Good evening, [Name]! 🌆"
  - Night (9pm-5am): "Hey there, [Name]! Burning the midnight oil? 🌙"

- **Name Personalization**
  - Uses student's first name from profile
  - Context-aware responses
  - Warm, encouraging tone

- **Date/Time Context**
  - Current date and time awareness
  - Contextual responses based on time

#### 2. **Module-Only Boundaries** 🛡️
- **Strict Content Enforcement**
  - Only answers questions from the provided module content
  - Politely redirects off-topic questions
  - No hallucinations or external information
  - Example: *"Hey Maria! I'd love to help with that, but I only have access to this specific module about Photosynthesis. Want to ask me something about the Calvin cycle instead? 📚"*

#### 3. **Four Interactive Modes**

##### 💬 **Chat Mode** - Conversational Learning
- Ask any question about the module
- 2-4 paragraph detailed answers
- Warm, encouraging responses
- Command detection:
  - "summarize" → Auto-switches to Reviewer
  - "flashcard" → Auto-switches to Flashcards
  - "quiz" → Auto-switches to Quiz

##### 🧠 **Reviewer Mode** - Key Study Points
- Generates **exactly 5** key study points
- Each point includes:
  - **Title**: 3-6 words
  - **Content**: Max 140 characters
- Focuses on core concepts
- No redundancy
- JSON-structured output

##### 🗂️ **Flashcards Mode** - Active Recall
- Generates **exactly 6** flashcards
- Mix of card types:
  - 30-40% Definition cards
  - 25-30% Application cards
  - 15-20% Comparison cards
  - 10-15% Example cards
- Progressive difficulty (easy → challenging)
- Flip animation
- Previous/Next navigation
- JSON-structured output

##### ❓ **Quiz Mode** - Knowledge Assessment
- Generates **exactly 5** multiple-choice questions
- **4 options each** (A, B, C, D)
- **All options are plausible** and related to module
- **No obvious answers** - requires understanding
- Quality distribution:
  - 40% Conceptual understanding
  - 30% Application
  - 20% Analysis
  - 10% Factual recall
- Difficulty: 2 easy, 2 medium, 1 challenging
- JSON-structured output

### 🔧 Technical Implementation

#### API Integration
- **Provider**: Groq API
- **Model**: `llama-3.1-8b-instant` (ultra-fast, free tier available)
- **Endpoint**: `https://api.groq.com/openai/v1`
- **Fallback System**: Heuristic responses if API unavailable

#### Request Flow
```
Student Opens Material
        ↓
Smart Buddy Widget Loads
        ↓
User Interacts (Chat/Reviewer/Flashcards/Quiz)
        ↓
Frontend sends: {mode, content, question, studentName, dateTime}
        ↓
SmartBuddyController processes request
        ↓
Builds comprehensive system prompt with:
  - Student's first name
  - Current date/time
  - Time-based greeting
  - Personality traits
  - Module-only boundary
  - Mode-specific instructions
        ↓
Calls Groq API (llama-3.1-8b-instant)
        ↓
Parses response (text or JSON)
        ↓
Returns to frontend
        ↓
Student sees personalized, module-focused response
```

#### Configuration
- API key configured via `.env`: `GROQ_API_KEY`
- Configurable model in `config/services.php`
- Timeout: 30 seconds
- Error handling with graceful fallback

#### Personality Traits
- Warm, friendly, conversational
- Uses emojis naturally (📚, ✨, 🎯, 💡, 🌟)
- Celebrates progress
- Encouraging and patient
- Never robotic

---

## 📊 Progress Tracking

### Material Completion Tracking

#### Features
- **Mark as Done/Undone**
  - Students can mark materials as completed
  - Visual checkmark indicators
  - Completion timestamps
  - Per-student tracking

- **Course Progress Calculation**
  - Percentage-based progress
  - Formula: `(Completed Materials / Total Materials) × 100`
  - Real-time updates
  - Visual progress bars

- **Progress Display**
  - Course-level progress
  - Material-level status
  - Dashboard overview
  - Course detail pages

#### Database Structure
- `MaterialCompletion` model tracks:
  - `student_id`
  - `material_id`
  - `course_id`
  - `completed_at` (timestamp)

#### API Endpoints
- `POST /student/materials/{material}/mark-done` - Mark material as completed
- `DELETE /student/materials/{material}/unmark-done` - Unmark material
- `GET /student/materials/{material}/completion-status` - Get completion status
- `GET /student/courses/{course}/progress` - Get course progress

### Assignment Progress

#### Tracking Features
- Submission status (submitted/pending)
- Grade tracking
- Attempt tracking
- Due date monitoring
- Completion percentage

### Dashboard Analytics

#### Student Dashboard
- Upcoming assignments (with due dates)
- Upcoming announcements (with event dates)
- Recent activity feed
- Calendar view with events
- Course progress overview

#### Teacher Dashboard
- Total courses created
- Total enrolled students
- Pending applications
- Recent activity
- Quick statistics

---

## 🏗️ System Architecture

### Database Structure

#### Core Models
- **User** - Teachers and Students
- **Course** - Course information
- **CourseTerm** - Academic terms
- **CourseSubTerm** - Sub-terms within terms
- **CourseWeek** - Weekly structure
- **CourseMaterial** - Learning materials
- **Assignment** - Course assignments
- **AssignmentSubmission** - Student submissions
- **Enrollment** - Student-course relationships
- **CourseApplication** - Application requests
- **MaterialCompletion** - Progress tracking
- **Notification** - User notifications
- **Announcement** - Course announcements
- **Attendance** - Attendance records

### File Structure
```
smart-study-hub/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── SmartBuddyController.php
│   │   │   ├── StudentDashboardController.php
│   │   │   ├── TeacherDashboardController.php
│   │   │   ├── CourseController.php
│   │   │   ├── AssignmentController.php
│   │   │   ├── StudentModuleController.php
│   │   │   └── ...
│   │   └── Middleware/
│   └── Models/
├── resources/
│   └── views/
│       ├── student/
│       ├── teacher/
│       └── components/
│           └── smart-buddy.blade.php
├── routes/
│   └── web.php
├── config/
│   └── services.php
└── database/
    └── migrations/
```

### MVC Architecture
- **Models** - Database relationships and business logic
- **Views** - Blade templates with Alpine.js
- **Controllers** - Request handling and response generation
- **Routes** - Role-based route groups
- **Middleware** - Authentication and authorization

---

## 🛠️ Technology Stack

### Backend
- **Framework**: Laravel 12.0
- **PHP**: 8.2+
- **Database**: SQLite (development), PostgreSQL/MySQL (production)
- **Authentication**: Laravel Breeze
- **OAuth**: Laravel Socialite (Google)

### Frontend
- **CSS Framework**: Tailwind CSS 3.1+
- **JavaScript**: Alpine.js 3.4+
- **Build Tool**: Vite 7.0+
- **Rich Text**: Quill.js 2.0+

### AI Integration
- **Provider**: Groq API
- **Model**: llama-3.1-8b-instant
- **HTTP Client**: Laravel HTTP Facade

### Additional Packages
- **PDF Generation**: DomPDF
- **HTML Purification**: HTMLPurifier
- **File Storage**: Laravel Filesystem

---

## 📦 Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js and NPM
- SQLite (development) or PostgreSQL/MySQL (production)

### Step 1: Clone Repository
```bash
git clone https://github.com/yourusername/smart-study-hub.git
cd smart-study-hub
```

### Step 2: Install Dependencies
```bash
# PHP dependencies
composer install

# JavaScript dependencies
npm install
```

### Step 3: Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Configure Environment
Edit `.env` file:
```env
APP_NAME="Smart Study Hub"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
# Or for MySQL/PostgreSQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=smart_study_hub
# DB_USERNAME=root
# DB_PASSWORD=

# Groq API (for Smart Buddy)
GROQ_API_KEY=your_groq_api_key_here
GROQ_API_URL=https://api.groq.com/openai/v1
GROQ_MODEL=llama-3.1-8b-instant

# Google OAuth (optional)
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

### Step 5: Database Setup
```bash
# Create SQLite database (if using SQLite)
touch database/database.sqlite

# Run migrations
php artisan migrate

# (Optional) Seed database
php artisan db:seed
```

### Step 6: Build Assets
```bash
npm run build
# Or for development:
npm run dev
```

### Step 7: Storage Link
```bash
php artisan storage:link
```

### Step 8: Start Server
```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

---

## ⚙️ Configuration

### Smart Buddy Configuration

#### Get Groq API Key
1. Visit [Groq Console](https://console.groq.com/)
2. Sign up/login
3. Create API key
4. Add to `.env`: `GROQ_API_KEY=your_key_here`

#### Alternative Models
Edit `config/services.php` or `.env`:
```env
GROQ_MODEL=llama-3.1-70b-versatile  # More powerful
GROQ_MODEL=llama-3.1-405b-reasoning # Best reasoning
GROQ_MODEL=mixtral-8x7b-32768       # Longer context
```

### Google OAuth Setup
See [GOOGLE_OAUTH_SETUP.md](GOOGLE_OAUTH_SETUP.md) for detailed instructions.

### File Upload Limits
See [UPLOAD_LIMITS.md](UPLOAD_LIMITS.md) for file size and type configurations.

---

## 📖 Usage

### For Teachers

1. **Register/Login** - Create account or sign in
2. **Create Course** - Go to "My Courses" → "Create Course"
3. **Add Structure** - Add Terms, Sub-terms, and Weeks
4. **Upload Materials** - Add PDFs, videos, links, or text
5. **Create Assignments** - Set due dates, points, and requirements
6. **Manage Students** - Approve applications, view enrollments
7. **Track Progress** - Monitor student completion and grades
8. **Create Announcements** - Notify students of important events

### For Students

1. **Register/Login** - Create account or sign in with Google
2. **Browse Courses** - View available courses
3. **Enroll** - Apply or directly enroll in courses
4. **Access Materials** - View course materials organized by week
5. **Use Smart Buddy** - Get AI assistance while studying
6. **Submit Assignments** - Upload files or text submissions
7. **Track Progress** - Monitor completion and grades
8. **View Calendar** - See upcoming tasks and events

---

## 🔌 API Documentation

### Smart Buddy API

#### Endpoint
```
POST /smart-buddy/nlp
```

#### Request Body
```json
{
  "mode": "answer|reviewer|flashcards|quiz",
  "content": "module text content (max 8000 chars)",
  "question": "student's question (for answer mode)",
  "studentName": "John Doe",
  "dateTime": "Tuesday, November 04, 2025 2:30 PM"
}
```

#### Response Format

**Chat Mode:**
```json
{
  "mode": "answer",
  "response": "Detailed answer text..."
}
```

**Reviewer Mode:**
```json
{
  "mode": "reviewer",
  "reviewers": [
    {
      "title": "Key Point Title",
      "content": "Brief explanation..."
    }
  ]
}
```

**Flashcards Mode:**
```json
{
  "mode": "flashcards",
  "flashcards": [
    {
      "question": "Question text?",
      "answer": "Answer text..."
    }
  ]
}
```

**Quiz Mode:**
```json
{
  "mode": "quiz",
  "quiz": [
    {
      "question": "Question text?",
      "options": ["Option A", "Option B", "Option C", "Option D"],
      "correctAnswer": 1
    }
  ]
}
```

### Progress Tracking API

#### Mark Material Complete
```
POST /student/materials/{material}/mark-done
```

#### Get Course Progress
```
GET /student/courses/{course}/progress
```

Response:
```json
{
  "progress": 75.5,
  "completed": 15,
  "total": 20
}
```

---

## 🚀 Deployment

### Quick Deployment Guide
See [DEPLOYMENT_QUICKSTART.md](DEPLOYMENT_QUICKSTART.md) for step-by-step deployment instructions.

### Supported Platforms
- **Render** (Recommended) - Free tier available
- **Railway** - Easy deployment
- **Fly.io** - Global edge deployment
- **Heroku** - Traditional PaaS

### Environment Variables for Production
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=pgsql
DB_HOST=your_db_host
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

GROQ_API_KEY=your_production_key
```

### Build Commands
```bash
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm ci
npm run build
```

### Post-Deployment
```bash
php artisan migrate --force
php artisan storage:link
```

---

## 📝 Project Progress

### ✅ Completed Features

#### Core System
- [x] User authentication and authorization
- [x] Role-based access control (Teacher/Student)
- [x] Google OAuth integration
- [x] Profile management

#### Course Management
- [x] Course creation and management
- [x] Hierarchical structure (Terms → Sub-terms → Weeks)
- [x] Material upload (PDF, Video, Link, Text)
- [x] Material editing and deletion
- [x] Assignment creation and management
- [x] Assignment grading system

#### Student Features
- [x] Course browsing and enrollment
- [x] Course applications (approval workflow)
- [x] Material access and viewing
- [x] Assignment submission
- [x] Progress tracking
- [x] Calendar integration

#### Smart Buddy
- [x] Groq API integration
- [x] Four interactive modes (Chat, Reviewer, Flashcards, Quiz)
- [x] Personalized greetings
- [x] Module-only boundaries
- [x] Fallback system

#### Notifications
- [x] Real-time notification system
- [x] Multiple notification types
- [x] Notification dropdown
- [x] Dedicated notifications page

#### Additional Features
- [x] Attendance tracking
- [x] Announcements system
- [x] Dashboard analytics
- [x] Recent activity feed
- [x] File management
- [x] PDF viewer

### 🔄 Future Enhancements

- [ ] Real-time chat between students and teachers
- [ ] Discussion forums per course
- [ ] Video conferencing integration
- [ ] Mobile app (React Native)
- [ ] Advanced analytics and reporting
- [ ] Gamification (badges, achievements)
- [ ] Peer review system
- [ ] Export progress reports
- [ ] Multi-language support
- [ ] Dark mode toggle

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Guidelines
- Follow PSR-12 coding standards
- Write tests for new features
- Update documentation
- Ensure backward compatibility

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 👥 Authors

- **Development Team** - Smart Study Hub

---

## 🙏 Acknowledgments

- Laravel Framework
- Groq API for AI capabilities
- Tailwind CSS for styling
- Alpine.js for interactivity
- All contributors and testers

---

## 📞 Support

For support, email support@smartstudyhub.com or open an issue in the repository.

---

## 🔗 Links

- [Documentation](https://github.com/yourusername/smart-study-hub/wiki)
- [Issue Tracker](https://github.com/yourusername/smart-study-hub/issues)
- [Deployment Guide](DEPLOYMENT_QUICKSTART.md)
- [Google OAuth Setup](GOOGLE_OAUTH_SETUP.md)

---

<div align="center">

**Built with ❤️ for better education**

⭐ Star this repo if you find it helpful!

</div>
