<?php
// Contact Form Handler

// Start session for flash messages
session_start();

// Set headers
header('Content-Type: application/json');

// Check if form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Get and sanitize form data
    $name = isset($_POST['name']) ? sanitize_input($_POST['name']) : '';
    $email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? sanitize_input($_POST['phone']) : '';
    $subject = isset($_POST['subject']) ? sanitize_input($_POST['subject']) : '';
    $message = isset($_POST['message']) ? sanitize_input($_POST['message']) : '';
    $department = isset($_POST['department']) ? sanitize_input($_POST['department']) : '';
    
    // Validate input
    $errors = [];
    
    if (empty($name)) {
        $errors[] = 'Name is required';
    } elseif (strlen($name) < 2) {
        $errors[] = 'Name must be at least 2 characters';
    }
    
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }
    
    if (empty($subject)) {
        $errors[] = 'Subject is required';
    } elseif (strlen($subject) < 5) {
        $errors[] = 'Subject must be at least 5 characters';
    }
    
    if (empty($message)) {
        $errors[] = 'Message is required';
    } elseif (strlen($message) < 10) {
        $errors[] = 'Message must be at least 10 characters';
    }
    
    // If there are errors, return them
    if (!empty($errors)) {
        echo json_encode([
            'success' => false,
            'errors' => $errors
        ]);
        exit;
    }
    
    // Prepare email content
    $to = 'info@headofservice.borno.gov.ng'; // Change to actual email
    $admin_email = 'admin@headofservice.borno.gov.ng'; // Admin email
    
    $email_subject = "Contact Form Submission: $subject";
    
    $email_body = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #1e3a8a, #0f172a); color: white; padding: 20px; text-align: center; }
            .content { background: #f8f9fa; padding: 20px; margin: 20px 0; border-radius: 5px; }
            .field { margin: 15px 0; }
            .field-label { font-weight: bold; color: #1e3a8a; }
            .field-value { background: white; padding: 10px; border-radius: 3px; margin-top: 5px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Borno State Office of the Head of Service</h2>
                <p>Contact Form Submission</p>
            </div>
            <div class='content'>
                <div class='field'>
                    <div class='field-label'>Name:</div>
                    <div class='field-value'>$name</div>
                </div>
                <div class='field'>
                    <div class='field-label'>Email:</div>
                    <div class='field-value'>$email</div>
                </div>
                <div class='field'>
                    <div class='field-label'>Phone:</div>
                    <div class='field-value'>$phone</div>
                </div>
                <div class='field'>
                    <div class='field-label'>Department:</div>
                    <div class='field-value'>$department</div>
                </div>
                <div class='field'>
                    <div class='field-label'>Subject:</div>
                    <div class='field-value'>$subject</div>
                </div>
                <div class='field'>
                    <div class='field-label'>Message:</div>
                    <div class='field-value'>$message</div>
                </div>
                <div class='field'>
                    <div class='field-label'>Date/Time:</div>
                    <div class='field-value'>" . date('F j, Y - g:i a') . "</div>
                </div>
                <div class='field'>
                    <div class='field-label'>IP Address:</div>
                    <div class='field-value'>" . $_SERVER['REMOTE_ADDR'] . "</div>
                </div>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Email headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: Borno State Contact Form <$email>" . "\r\n";
    $headers .= "Reply-To: $name <$email>" . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    // Send email
    try {
        // Send to admin
        mail($admin_email, $email_subject, $email_body, $headers);
        
        // Send auto-reply to user
        $auto_reply_subject = "Thank you for contacting Borno State Office of the Head of Service";
        $auto_reply_body = "
        Dear $name,
        
        Thank you for contacting the Borno State Office of the Head of Service.
        
        We have received your message regarding: \"$subject\"
        
        Our team will review your inquiry and get back to you as soon as possible.
        
        For urgent matters, please contact us directly at:
        Phone: +234 801 234 5678
        Email: info@headofservice.borno.gov.ng
        
        Best regards,
        Borno State Office of the Head of Service
        Governor's Office, Musa Usman Secretariat
        Maiduguri, Borno State
        ";
        
        $auto_reply_headers = "From: Borno State Office of the Head of Service <info@headofservice.borno.gov.ng>";
        mail($email, $auto_reply_subject, $auto_reply_body, $auto_reply_headers);
        
        // Log the submission (optional)
        $log_data = [
            'timestamp' => date('Y-m-d H:i:s'),
            'name' => $name,
            'email' => $email,
            'subject' => $subject,
            'ip_address' => $_SERVER['REMOTE_ADDR']
        ];
        
        file_put_contents('contact_submissions.log', json_encode($log_data) . PHP_EOL, FILE_APPEND);
        
        // Return success response
        echo json_encode([
            'success' => true,
            'message' => 'Your message has been sent successfully. We will get back to you soon.'
        ]);
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'errors' => ['An error occurred while sending your message. Please try again later.']
        ]);
    }
    
} else {
    // Invalid request method
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'errors' => ['Invalid request method']
    ]);
}

// Helper function to sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>