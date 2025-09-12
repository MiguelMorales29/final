<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a teacher if none exists
        $teacher = \App\Models\User::where('role', 'teacher')->first();
        if (!$teacher) {
            $teacher = \App\Models\User::create([
                'name' => 'Dr. Sarah Johnson',
                'email' => 'sarah.johnson@example.com',
                'password' => bcrypt('password'),
                'role' => 'teacher'
            ]);
        }

        // Create sample courses
        $courses = [
            [
                'title' => 'Advanced Mathematics',
                'description' => 'Learn advanced mathematical concepts including calculus, linear algebra, and differential equations. Perfect for students who want to master complex mathematical theories.',
                'image' => null
            ],
            [
                'title' => 'Web Development Fundamentals',
                'description' => 'Master the basics of web development including HTML, CSS, JavaScript, and modern frameworks. Build responsive and interactive websites.',
                'image' => null
            ],
            [
                'title' => 'Data Science with Python',
                'description' => 'Explore data analysis, machine learning, and statistical modeling using Python. Learn to extract insights from complex datasets.',
                'image' => null
            ],
            [
                'title' => 'Digital Marketing Strategy',
                'description' => 'Comprehensive course covering SEO, social media marketing, content strategy, and analytics. Perfect for modern marketers.',
                'image' => null
            ],
            [
                'title' => 'Mobile App Development',
                'description' => 'Build native and cross-platform mobile applications using React Native and Flutter. Learn app design and deployment.',
                'image' => null
            ],
            [
                'title' => 'Cybersecurity Essentials',
                'description' => 'Learn about network security, encryption, threat detection, and security best practices. Protect systems from cyber threats.',
                'image' => null
            ]
        ];

        foreach ($courses as $courseData) {
            $teacher->courses()->create($courseData);
        }

        echo "Created " . count($courses) . " sample courses for teacher: " . $teacher->name . "\n";
    }
}
