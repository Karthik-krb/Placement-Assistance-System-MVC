<?php
/**
 * Email Configuration File
 * 
 * Configure your email settings here.
 * For Gmail: You need to use an "App Password" not your regular password
 * How to get Gmail App Password:
 * 1. Go to Google Account settings
 * 2. Security > 2-Step Verification (enable it)
 * 3. Security > App passwords
 * 4. Generate a new app password for "Mail"
 * 5. Use that 16-character password below
 */

return [
    'email' => [
        // SMTP Server settings
        'smtp_host' => 'smtp.gmail.com',
        'smtp_port' => 587,
        'smtp_secure' => 'tls', // 'tls' or 'ssl'
        
        // Authentication
        'smtp_username' => 'your-email@gmail.com', // TODO: Change this
        'smtp_password' => 'your-app-password',     // TODO: Change this (use App Password, not regular password)
        
        // From address
        'from_email' => 'noreply@placement-system.com',
        'from_name' => 'Placement Assistance System',
        
        // Batch settings for handling 100+ emails
        'batch_size' => 10,          // Send 10 emails per batch
        'batch_delay_ms' => 500,      // 500ms delay between batches
        'email_delay_ms' => 100,      // 100ms delay between individual emails
        
        // Rate limiting
        'max_emails_per_hour' => 100, // Maximum emails per company per hour
    ]
];
