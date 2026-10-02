<?php
// Newsletter Subscription Handler

// Start session
session_start();

// Set headers
header('Content-Type: application/json');

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Get email from POST data
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    
    // Validate email
    if (empty($email)) {
        echo json_encode([
            'success' => false,
            'message' => 'Email address is required'
        ]);
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid email format'
        ]);
        exit;
    }
    
    // Check if email already exists in database
    // Note: You'll need to implement database connection
    $email_exists = check_email_exists($email);
    
    if ($email_exists) {
        echo json_encode([
            'success' => false,
            'message' => 'This email is already subscribed to our newsletter'
        ]);
        exit;
    }
    
    // Add email to database
    $subscription_result = add_to_newsletter($email);
    
    if ($subscription_result) {
        // Send welcome email
        send_welcome_email($email);
        
        // Log subscription
        log_subscription($email);
        
        echo json_encode([
            'success' => true,
            'message' => 'Thank you for subscribing to our newsletter!'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'An error occurred. Please try again later.'
        ]);
    }
    
} else {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}

// Function to check if email exists in database
function check_email_exists($email) {
    // TODO: Implement database check
    // Example:
    /*
    $conn = new mysqli($servername, $username, $password, $dbname);
    $stmt = $conn->prepare("SELECT id FROM newsletter_subscribers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
    */
    return false; // Placeholder
}

// Function to add email to database
function add_to_newsletter($email) {
    // TODO: Implement database insertion
    // Example:
    /*
    $conn = new mysqli($servername, $username, $password, $dbname);
    $stmt = $conn->prepare("INSERT INTO newsletter_subscribers (email, subscribed_date) VALUES (?, NOW())");
    $stmt->bind_param("s", $email);
    return $stmt->execute();
    */
    return true; // Placeholder
}

// Function to send welcome email
function send_welcome_email($email) {
    $subject = "Welcome to Borno State Office of the Head of Service Newsletter";
    
    $message = "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #1e3a8a, #0f172a); color: white; padding: 20px; text-align: center; }
            .content { background: #f8f9fa; padding: 20px; margin: 20px 0; border-radius: 5px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Borno State Office of the Head of Service</h2>
            </div>
            <div class='content'>
                <h3>Welcome to Our Newsletter!</h3>
                <p>Dear Subscriber,</p>
                <p>Thank you for subscribing to the Borno State Office of the Head of Service newsletter.</p>
                <p>You will now receive regular updates about:</p>
                <ul>
                    <li>Latest news and announcements</li>
                    <li>Upcoming events and training</li>
                    <li>Service updates and innovations</li>
                    <li>Important circulars and publications</li>
                </ul>
                <p>Stay informed and connected with the Borno State Civil Service.</p>
                <p>For any inquiries, please contact us at info@headofservice.borno.gov.ng</p>
                <p>Best regards,<br>
                Borno State Office of the Head of Service</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: Borno State Office of the Head of Service <info@headofservice.borno.gov.ng>";
    
    // Uncomment to actually send email
    // mail($email, $subject, $message, $headers);
}

// Function to log subscription
function log_subscription($email) {
    $log_data = [
        'timestamp' => date('Y-m-d H:i:s'),
        'email' => $email,
        'ip_address' => $_SERVER['REMOTE_ADDR'],
        'user_agent' => $_SERVER['HTTP_USER_AGENT']
    ];
    
    file_put_contents('newsletter_subscriptions.log', json_encode($log_data) . PHP_EOL, FILE_APPEND);
}
?>