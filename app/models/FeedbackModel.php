<?php
// app/models/FeedbackModel.php

class FeedbackModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Submit new feedback from candidate or company
     */
    public function submitFeedback($user_type, $user_id, $subject, $message) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO feedback (user_type, user_id, subject, message, status, created_at) 
                VALUES (?, ?, ?, ?, 'pending', NOW())
            ");
            
            return $stmt->execute([$user_type, $user_id, $subject, $message]);
        } catch (PDOException $e) {
            error_log("Error submitting feedback: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all feedback for admin (with user details)
     */
    public function getAllFeedback($status = null) {
        try {
            $query = "
                SELECT 
                    f.*,
                    CASE 
                        WHEN f.user_type = 'candidate' THEN c.c_name
                        WHEN f.user_type = 'company' THEN co.company_name
                    END as user_name,
                    CASE 
                        WHEN f.user_type = 'candidate' THEN c.c_email
                        WHEN f.user_type = 'company' THEN co.company_email
                    END as user_email
                FROM feedback f
                LEFT JOIN candidate c ON f.user_type = 'candidate' AND f.user_id = c.candidate_id
                LEFT JOIN company co ON f.user_type = 'company' AND f.user_id = co.company_id
            ";
            
            if ($status !== null) {
                $query .= " WHERE f.status = ?";
            }
            
            $query .= " ORDER BY f.created_at DESC";
            
            $stmt = $this->pdo->prepare($query);
            
            if ($status !== null) {
                $stmt->execute([$status]);
            } else {
                $stmt->execute();
            }
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching feedback: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get feedback by ID
     */
    public function getFeedbackById($feedback_id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    f.*,
                    CASE 
                        WHEN f.user_type = 'candidate' THEN c.c_name
                        WHEN f.user_type = 'company' THEN co.company_name
                    END as user_name,
                    CASE 
                        WHEN f.user_type = 'candidate' THEN c.c_email
                        WHEN f.user_type = 'company' THEN co.company_email
                    END as user_email
                FROM feedback f
                LEFT JOIN candidate c ON f.user_type = 'candidate' AND f.user_id = c.candidate_id
                LEFT JOIN company co ON f.user_type = 'company' AND f.user_id = co.company_id
                WHERE f.feedback_id = ?
            ");
            
            $stmt->execute([$feedback_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching feedback by ID: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Admin reply to feedback
     */
    public function replyToFeedback($feedback_id, $admin_reply) {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE feedback 
                SET admin_reply = ?, status = 'replied', replied_at = NOW() 
                WHERE feedback_id = ?
            ");
            
            return $stmt->execute([$admin_reply, $feedback_id]);
        } catch (PDOException $e) {
            error_log("Error replying to feedback: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get feedback for specific user (candidate or company)
     */
    public function getUserFeedback($user_type, $user_id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM feedback 
                WHERE user_type = ? AND user_id = ? 
                ORDER BY created_at DESC
            ");
            
            $stmt->execute([$user_type, $user_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching user feedback: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get feedback count by status for admin dashboard
     */
    public function getFeedbackCounts() {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'replied' THEN 1 ELSE 0 END) as replied
                FROM feedback
            ");
            
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching feedback counts: " . $e->getMessage());
            return ['total' => 0, 'pending' => 0, 'replied' => 0];
        }
    }

    /**
     * Delete feedback (if needed)
     */
    public function deleteFeedback($feedback_id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM feedback WHERE feedback_id = ?");
            return $stmt->execute([$feedback_id]);
        } catch (PDOException $e) {
            error_log("Error deleting feedback: " . $e->getMessage());
            return false;
        }
    }
}
