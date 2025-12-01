<?php
// app/models/EmailModel.php

require_once __DIR__ . '/../../vendor/phpmailer/PHPMailer.php';
require_once __DIR__ . '/../../vendor/phpmailer/SMTP.php';
require_once __DIR__ . '/../../vendor/phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailModel {
    private $pdo;
    private $emailConfig;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->emailConfig = require __DIR__ . '/../../config/email.php';
    }
    
    /**
     * Send email to shortlisted candidate(s) for a specific job
     * Handles batch sending for 100+ emails with proper error handling
     */
    public function sendShortlistEmail($company_id, $job_id, $candidate_id, $subject = null, $message = null) {
        try {
            // Get job and company details
            $stmt = $this->pdo->prepare("
                SELECT j.job_title, j.location, j.job_description,
                       c.company_name, c.company_email
                FROM job j
                JOIN company c ON j.company_id = c.company_id
                WHERE j.job_id = ? AND j.company_id = ?
            ");
            $stmt->execute([$job_id, $company_id]);
            $jobDetails = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$jobDetails) {
                return ['success' => false, 'message' => 'Job not found'];
            }
            
            // Get candidate details
            $stmt = $this->pdo->prepare("
                SELECT c.c_name as name, c.c_email as email, c.c_phone as phone
                FROM candidate c
                JOIN apply a ON c.candidate_id = a.candidate_id
                JOIN shortlist s ON a.apply_id = s.apply_id
                WHERE c.candidate_id = ? AND a.job_id = ? AND s.status = 'Shortlisted'
            ");
            $stmt->execute([$candidate_id, $job_id]);
            $candidate = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$candidate) {
                return ['success' => false, 'message' => 'Candidate not shortlisted for this job'];
            }
            
            // Check if email already sent
            $stmt = $this->pdo->prepare("
                SELECT email_id FROM email 
                WHERE company_id = ? AND job_id = ? AND candidate_id = ? AND email_status = 'sent'
            ");
            $stmt->execute([$company_id, $job_id, $candidate_id]);
            if ($stmt->fetch()) {
                return ['success' => false, 'message' => 'Email already sent to this candidate'];
            }
            
            // Prepare email content
            $emailSubject = $subject ?? "Congratulations! Shortlisted for {$jobDetails['job_title']} at {$jobDetails['company_name']}";
            
            $emailBody = $message ?? $this->getDefaultEmailTemplate(
                $candidate['name'],
                $jobDetails['job_title'],
                $jobDetails['company_name'],
                $jobDetails['department'],
                $jobDetails['location'],
                $jobDetails['ctc']
            );
            
            // Send email using PHPMailer
            $emailSent = $this->sendEmailViaPHPMailer(
                $candidate['email'],
                $candidate['name'],
                $emailSubject,
                $emailBody,
                $jobDetails['company_name'],
                $jobDetails['company_email']
            );
            
            // Log email attempt
            $status = $emailSent ? 'sent' : 'failed';
            $stmt = $this->pdo->prepare("
                INSERT INTO email (company_id, job_id, candidate_id, subject, message, email_status, sent_at)
                VALUES (?, ?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([$company_id, $job_id, $candidate_id, $emailSubject, $emailBody, $status]);
            
            return [
                'success' => $emailSent,
                'message' => $emailSent ? 'Email sent successfully' : 'Failed to send email'
            ];
            
        } catch (Exception $e) {
            error_log("Email sending error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }
    
    /**
     * Send emails to multiple candidates in batches to handle 100+ emails
     */
    public function sendBulkEmails($company_id, $job_id, $candidate_ids, $subject = null, $message = null) {
        $emailCfg = $this->emailConfig['email'];
        
        $results = [
            'total' => count($candidate_ids),
            'sent' => 0,
            'failed' => 0,
            'errors' => []
        ];
        
        // Process in configurable batches
        $batchSize = $emailCfg['batch_size'] ?? 10;
        $batches = array_chunk($candidate_ids, $batchSize);
        
        foreach ($batches as $batchIndex => $batch) {
            foreach ($batch as $candidate_id) {
                $result = $this->sendShortlistEmail($company_id, $job_id, $candidate_id, $subject, $message);
                
                if ($result['success']) {
                    $results['sent']++;
                } else {
                    $results['failed']++;
                    $results['errors'][] = "Candidate ID {$candidate_id}: {$result['message']}";
                }
                
                // Small delay to prevent server overload
                $emailDelay = ($emailCfg['email_delay_ms'] ?? 100) * 1000;
                usleep($emailDelay);
            }
            
            // Longer delay between batches
            if ($batchIndex < count($batches) - 1) {
                $batchDelay = ($emailCfg['batch_delay_ms'] ?? 500) * 1000;
                usleep($batchDelay);
            }
        }
        
        return $results;
    }
    
    /**
     * Send email using PHPMailer with SMTP
     */
    private function sendEmailViaPHPMailer($toEmail, $toName, $subject, $body, $fromName, $replyTo) {
        try {
            $mail = new PHPMailer(true);
            
            $emailCfg = $this->emailConfig['email'];
            
            // Server settings
            $mail->isSMTP();
            $mail->Host       = $emailCfg['smtp_host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $emailCfg['smtp_username'];
            $mail->Password   = $emailCfg['smtp_password'];
            $mail->SMTPSecure = $emailCfg['smtp_secure'];
            $mail->Port       = $emailCfg['smtp_port'];
            
            // Recipients
            $mail->setFrom($emailCfg['from_email'], $emailCfg['from_name']);
            $mail->addAddress($toEmail, $toName);
            $mail->addReplyTo($replyTo, $fromName);
            
            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = strip_tags($body);
            
            $mail->send();
            return true;
            
        } catch (Exception $e) {
            error_log("PHPMailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }
    
    /**
     * Get default email template
     */
    private function getDefaultEmailTemplate($candidateName, $jobTitle, $companyName, $department, $location, $ctc) {
        return "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #4CAF50; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
                .content { background: #f9f9f9; padding: 30px; border: 1px solid #ddd; }
                .job-details { background: white; padding: 15px; margin: 20px 0; border-left: 4px solid #4CAF50; }
                .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
                .btn { display: inline-block; padding: 12px 30px; background: #4CAF50; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>🎉 Congratulations!</h1>
                </div>
                <div class='content'>
                    <p>Dear <strong>{$candidateName}</strong>,</p>
                    
                    <p>We are delighted to inform you that you have been <strong>shortlisted</strong> for the following position:</p>
                    
                    <div class='job-details'>
                        <h3 style='margin-top: 0; color: #4CAF50;'>{$jobTitle}</h3>
                        <p><strong>Company:</strong> {$companyName}</p>
                        <p><strong>Department:</strong> {$department}</p>
                        <p><strong>Location:</strong> {$location}</p>
                        <p><strong>CTC:</strong> ₹{$ctc} LPA</p>
                    </div>
                    
                    <p>This is an exciting opportunity, and we believe you are a great fit for this role based on your qualifications and profile.</p>
                    
                    <p><strong>Next Steps:</strong></p>
                    <ul>
                        <li>Log in to your account to view complete job details</li>
                        <li>Prepare for the upcoming interview/assessment</li>
                        <li>Keep your documents ready</li>
                        <li>Wait for further communication from the company</li>
                    </ul>
                    
                    <center>
                        <a href='http://localhost/PAS/public/candidate/dashboard' class='btn'>View Dashboard</a>
                    </center>
                    
                    <p>We wish you all the best for the next steps in the selection process!</p>
                    
                    <p>Best regards,<br>
                    <strong>{$companyName}</strong><br>
                    Placement Assistance System</p>
                </div>
                <div class='footer'>
                    <p>This is an automated email. Please do not reply to this message.</p>
                    <p>&copy; 2025 Placement Assistance System. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
    
    /**
     * Get email sending history for a company
     */
    public function getEmailHistory($company_id, $job_id = null) {
        try {
            $query = "
                SELECT e.*, 
                       c.c_name as candidate_name, c.c_email as candidate_email,
                       j.job_title
                FROM email e
                JOIN candidate c ON e.candidate_id = c.candidate_id
                JOIN job j ON e.job_id = j.job_id
                WHERE e.company_id = ?
            ";
            
            $params = [$company_id];
            
            if ($job_id) {
                $query .= " AND e.job_id = ?";
                $params[] = $job_id;
            }
            
            $query .= " ORDER BY e.sent_at DESC";
            
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error fetching email history: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get shortlisted candidates for a job with email status
     */
    public function getShortlistedWithEmailStatus($company_id, $job_id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT c.candidate_id, c.c_name as name, c.c_email as email, 
                       c.c_phone as phone, c.c_cgpa as cgpa,
                       s.created_at as shortlisted_at,
                       e.email_id, e.email_status, e.sent_at,
                       IF(e.email_id IS NOT NULL AND e.email_status = 'sent', 1, 0) as email_sent
                FROM apply a
                JOIN shortlist s ON a.apply_id = s.apply_id
                JOIN candidate c ON a.candidate_id = c.candidate_id
                LEFT JOIN email e ON (e.candidate_id = c.candidate_id AND e.job_id = a.job_id AND e.company_id = ?)
                WHERE a.job_id = ? AND s.status = 'Shortlisted'
                ORDER BY s.created_at DESC
            ");
            $stmt->execute([$company_id, $job_id]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error fetching shortlisted candidates: " . $e->getMessage());
            return [];
        }
    }
}
