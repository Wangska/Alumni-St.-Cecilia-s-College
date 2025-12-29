<?php
declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Mailtrap SMTP Configuration
// Get these credentials from your Mailtrap account at https://mailtrap.io/
const MAILTRAP_HOST = 'sandbox.smtp.mailtrap.io';
const MAILTRAP_PORT = 2525;
const MAILTRAP_USERNAME = '18ab2ddab4169b'; // Update with your Mailtrap username
const MAILTRAP_PASSWORD = '2068d476c349ab'; // Update with your Mailtrap password
const MAIL_FROM_EMAIL = 'noreply@stcecilia.edu.ph';
const MAIL_FROM_NAME = 'St. Cecilia\'s College Alumni System';

/**
 * Send an email using PHPMailer and Mailtrap
 */
function sendEmail(string $to, string $toName, string $subject, string $htmlBody, string $textBody = '', bool $embedLogo = true): bool
{
    try {
        $mail = new PHPMailer(true);

        // Enable verbose debug output (comment out in production)
        // $mail->SMTPDebug = 2; // Uncomment to see detailed SMTP errors
        
        // Server settings
        $mail->isSMTP();
        $mail->Host       = MAILTRAP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = MAILTRAP_USERNAME;
        $mail->Password   = MAILTRAP_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = MAILTRAP_PORT;
        $mail->Timeout    = 30; // Increase timeout to 30 seconds

        // Recipients
        $mail->setFrom(MAIL_FROM_EMAIL, MAIL_FROM_NAME);
        $mail->addAddress($to, $toName);

        // Embed logo if requested
        if ($embedLogo) {
            $logoPath = __DIR__ . '/../images/scc.png';
            if (file_exists($logoPath)) {
                $mail->addEmbeddedImage($logoPath, 'logo_cid', 'scc-logo.png');
            }
        }

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $htmlBody;
        $mail->AltBody = $textBody ?: strip_tags($htmlBody);

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Log detailed error information
        error_log("Email sending failed to $to: " . $e->getMessage());
        error_log("PHPMailer ErrorInfo: " . $mail->ErrorInfo);
        return false;
    }
}

/**
 * Send registration success email
 */
function sendRegistrationEmail(string $email, string $name, string $username): bool
{
    $subject = 'Welcome to St. Cecilia\'s College Alumni Network!';
    
    $htmlBody = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0; }
            .header-logo { display: flex; align-items: center; justify-content: center; gap: 15px; }
            .logo-img { width: 60px; height: 60px; object-fit: contain; }
            .content { background: #ffffff; padding: 30px; border: 1px solid #e5e7eb; border-top: none; }
            .footer { background: #f9fafb; padding: 20px; text-align: center; font-size: 12px; color: #6b7280; border-radius: 0 0 8px 8px; }
            .button { display: inline-block; padding: 12px 24px; background: #dc2626; color: white; text-decoration: none; border-radius: 8px; margin: 20px 0; }
            .info-box { background: #f0fdf4; border-left: 4px solid #10b981; padding: 15px; margin: 20px 0; border-radius: 4px; }
            .warning-box { background: #fffbeb; border-left: 4px solid #fbbf24; padding: 15px; margin: 20px 0; border-radius: 4px; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <div class="header-logo">
                    <img src="cid:logo_cid" alt="St. Cecilia\'s College Logo" class="logo-img" />
                    <h1 style="margin: 0; font-size: 26px;">Welcome to Alumni Network!</h1>
                </div>
            </div>
            <div class="content">
                <h2 style="color: #dc2626;">Registration Successful!</h2>
                <p>Dear <strong>' . htmlspecialchars($name) . '</strong>,</p>
                
                <p>Thank you for registering with the St. Cecilia\'s College Alumni Network! Your account has been successfully created.</p>
                
                <div class="info-box">
                    <strong> Your Account Details:</strong><br>
                    <strong>Username:</strong> ' . htmlspecialchars($username) . '<br>
                    <strong>Email:</strong> ' . htmlspecialchars($email) . '
                </div>
                
                <div class="warning-box">
                    <strong> Account Under Review</strong><br>
                    Your account is currently being reviewed by our administrators. You\'ll receive another email notification once your account has been verified and activated.
                </div>
                
                <p><strong>What happens next?</strong></p>
                <ul>
                    <li>Our team will review your submitted documents</li>
                    <li>Verification typically takes 1-3 business days</li>
                    <li>You\'ll receive an email once your account is approved</li>
                    <li>After approval, you can log in and access all features</li>
                </ul>
                
                <p>If you have any questions or concerns, please don\'t hesitate to contact us.</p>
                
                <p>Best regards,<br>
                <strong>St. Cecilia\'s College Alumni Network Team</strong></p>
            </div>
            <div class="footer">
                <p>© ' . date('Y') . ' St. Cecilia\'s College - Cebu, Inc. All rights reserved.</p>
                <p>Cebu South National Highway, Ward II, Minglanilla, Cebu</p>
                <p style="color: #9ca3af; font-size: 11px;">This is an automated message, please do not reply to this email.</p>
            </div>
        </div>
    </body>
    </html>
    ';
    
    $textBody = "Welcome to St. Cecilia's College Alumni Network!\n\n"
              . "Registration Successful!\n\n"
              . "Dear $name,\n\n"
              . "Thank you for registering with the St. Cecilia's College Alumni Network!\n\n"
              . "Your Account Details:\n"
              . "Username: $username\n"
              . "Email: $email\n\n"
              . "Your account is currently being reviewed by our administrators. "
              . "You'll receive another email notification once your account has been verified and activated.\n\n"
              . "Best regards,\n"
              . "St. Cecilia's College Alumni Network Team";
    
    return sendEmail($email, $name, $subject, $htmlBody, $textBody);
}

/**
 * Send account verification/approval email
 */
function sendVerificationEmail(string $email, string $name, string $username): bool
{
    $subject = ' Your Alumni Account Has Been Approved!';
    
    $htmlBody = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0; }
            .header-logo { display: flex; align-items: center; justify-content: center; gap: 15px; }
            .logo-img { width: 60px; height: 60px; object-fit: contain; }
            .content { background: #ffffff; padding: 30px; border: 1px solid #e5e7eb; border-top: none; }
            .footer { background: #f9fafb; padding: 20px; text-align: center; font-size: 12px; color: #6b7280; border-radius: 0 0 8px 8px; }
            .button { display: inline-block; padding: 12px 24px; background: #10b981; color: white; text-decoration: none; border-radius: 8px; margin: 20px 0; font-weight: bold; }
            .success-box { background: #f0fdf4; border-left: 4px solid #10b981; padding: 15px; margin: 20px 0; border-radius: 4px; }
            .features { background: #f9fafb; padding: 20px; border-radius: 8px; margin: 20px 0; }
            .checkmark { color: #10b981; margin-right: 8px; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <div class="header-logo">
                    <img src="cid:logo_cid" alt="St. Cecilia\'s College Logo" class="logo-img" />
                    <h1 style="margin: 0; font-size: 26px;">Account Approved!</h1>
                </div>
            </div>
            <div class="content">
                <h2 style="color: #10b981;">Congratulations, Your Account is Now Active!</h2>
                <p>Dear <strong>' . htmlspecialchars($name) . '</strong>,</p>
                
                <div class="success-box">
                    <strong> Great News!</strong><br>
                    Your St. Cecilia\'s College Alumni Network account has been verified and approved by our administrators. You can now log in and access all features!
                </div>
                
                <p><strong>Your Login Credentials:</strong></p>
                <ul style="list-style: none; padding-left: 0;">
                    <li><strong>Username:</strong> ' . htmlspecialchars($username) . '</li>
                    <li><strong>Email:</strong> ' . htmlspecialchars($email) . '</li>
                </ul>
                
                <div style="text-align: center;">
                    <a href="http://localhost/scratch/" class="button" style="color: white;">Login to Your Account</a>
                </div>
                
                <div class="features">
                    <p><strong>What you can do now:</strong></p>
                    <ul style="list-style: none; padding-left: 0;">
                        <li> Connect with fellow alumni</li>
                        <li> Join upcoming events and reunions</li>
                        <li> Share your success stories</li>
                        <li> Access career opportunities</li>
                        <li> Participate in forum discussions</li>
                        <li> Update your profile information</li>
                    </ul>
                </div>
                
                <p>We\'re excited to have you as part of our alumni community! If you have any questions or need assistance, please don\'t hesitate to reach out.</p>
                
                <p>Welcome home!<br>
                <strong>St. Cecilia\'s College Alumni Network Team</strong></p>
            </div>
            <div class="footer">
                <p>© ' . date('Y') . ' St. Cecilia\'s College - Cebu, Inc. All rights reserved.</p>
                <p>Cebu South National Highway, Ward II, Minglanilla, Cebu</p>
                <p style="color: #9ca3af; font-size: 11px;">This is an automated message, please do not reply to this email.</p>
            </div>
        </div>
    </body>
    </html>
    ';
    
    $textBody = "Your Alumni Account Has Been Approved!\n\n"
              . "Congratulations! Your Account is Now Active!\n\n"
              . "Dear $name,\n\n"
              . "Your St. Cecilia's College Alumni Network account has been verified and approved. "
              . "You can now log in and access all features!\n\n"
              . "Your Login Credentials:\n"
              . "Username: $username\n"
              . "Email: $email\n\n"
              . "Login at: http://localhost/scratch/\n\n"
              . "What you can do now:\n"
              . "- Connect with fellow alumni\n"
              . "- Join upcoming events and reunions\n"
              . "- Share your success stories\n"
              . "- Access career opportunities\n"
              . "- Participate in forum discussions\n"
              . "- Update your profile information\n\n"
              . "Welcome home!\n"
              . "St. Cecilia's College Alumni Network Team";
    
    return sendEmail($email, $name, $subject, $htmlBody, $textBody);
}

/**
 * Send event reminder email (1, 2, or 3 days before event)
 */
function sendEventReminderEmail(string $email, string $name, string $eventTitle, string $eventSchedule, string $eventContent, int $daysBefore = 1): bool
{
    // Customize subject and message based on days before event
    if ($daysBefore === 3) {
        $subject = 'Reminder: Event in 3 Days - ' . $eventTitle;
        $timeText = 'in <strong>3 days</strong>';
        $urgencyText = 'Mark your calendar! Your event is coming up soon.';
    } elseif ($daysBefore === 2) {
        $subject = 'Reminder: Event in 2 Days - ' . $eventTitle;
        $timeText = 'in <strong>2 days</strong>';
        $urgencyText = 'Don\'t forget! Your event is coming up soon.';
    } else {
        $subject = 'Reminder: Event Tomorrow - ' . $eventTitle;
        $timeText = '<strong>tomorrow</strong>';
        $urgencyText = 'Your event is happening very soon!';
    }
    
    // Format the event date nicely
    $eventDate = new DateTime($eventSchedule);
    $formattedDate = $eventDate->format('l, F j, Y \a\t g:i A');
    
    // Truncate event content for email
    $eventSummary = strlen($eventContent) > 300 ? substr($eventContent, 0, 300) . '...' : $eventContent;
    
    $htmlBody = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0; }
            .header-logo { display: flex; align-items: center; justify-content: center; gap: 15px; }
            .logo-img { width: 60px; height: 60px; object-fit: contain; }
            .content { background: #ffffff; padding: 30px; border: 1px solid #e5e7eb; border-top: none; }
            .footer { background: #f9fafb; padding: 20px; text-align: center; font-size: 12px; color: #6b7280; border-radius: 0 0 8px 8px; }
            .button { display: inline-block; padding: 12px 24px; background: #3b82f6; color: white; text-decoration: none; border-radius: 8px; margin: 20px 0; font-weight: bold; }
            .reminder-box { background: #dbeafe; border-left: 4px solid #3b82f6; padding: 20px; margin: 20px 0; border-radius: 4px; }
            .event-details { background: #f9fafb; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #10b981; }
            .clock-icon { font-size: 48px; margin-bottom: 10px; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <div class="header-logo">
                    <img src="cid:logo_cid" alt="St. Cecilia\'s College Logo" class="logo-img" />
                    <h1 style="margin: 0; font-size: 26px;">Event Reminder</h1>
                </div>
            </div>
            <div class="content">
                <div style="text-align: center; color: #3b82f6;" class="clock-icon"></div>
                <h2 style="color: #3b82f6; text-align: center;">' . ($daysBefore === 3 ? 'Event in 3 Days!' : ($daysBefore === 2 ? 'Event in 2 Days!' : 'Your Event is Tomorrow!')) . '</h2>
                <p>Dear <strong>' . htmlspecialchars($name) . '</strong>,</p>
                
                <div class="reminder-box">
                    <strong>Friendly Reminder</strong><br>
                    ' . $urgencyText . ' This is a reminder that you have registered for an event happening ' . $timeText . '!
                </div>
                
                <div class="event-details">
                    <h3 style="color: #2d3142; margin-top: 0;">Event Details</h3>
                    <p style="margin: 10px 0;"><strong>Event:</strong> ' . htmlspecialchars($eventTitle) . '</p>
                    <p style="margin: 10px 0;"><strong>Date & Time:</strong> ' . $formattedDate . '</p>
                    <p style="margin: 10px 0;"><strong>Description:</strong></p>
                    <p style="color: #4b5563; line-height: 1.6;">' . nl2br(htmlspecialchars($eventSummary)) . '</p>
                </div>
                
                <div style="background: #fffbeb; border-left: 4px solid #fbbf24; padding: 15px; margin: 20px 0; border-radius: 4px;">
                    <strong>Important Reminders:</strong>
                    <ul style="margin: 10px 0; padding-left: 20px;">
                        <li>Please arrive on time</li>
                        <li>Bring any necessary materials or documents</li>
                        <li>Contact the organizer if you need to cancel</li>
                    </ul>
                </div>
                
                <div style="text-align: center;">
                    <a href="http://localhost/scratch/events/" class="button" style="color: white;">View Event Details</a>
                </div>
                
                <p style="margin-top: 30px;">We look forward to seeing you at the event! If you have any questions, please don\'t hesitate to contact us.</p>
                
                <p>Best regards,<br>
                <strong>St. Cecilia\'s College Alumni Network Team</strong></p>
            </div>
            <div class="footer">
                <p>© ' . date('Y') . ' St. Cecilia\'s College - Cebu, Inc. All rights reserved.</p>
                <p>Cebu South National Highway, Ward II, Minglanilla, Cebu</p>
                <p style="color: #9ca3af; font-size: 11px;">This is an automated reminder. Please do not reply to this email.</p>
            </div>
        </div>
    </body>
    </html>
    ';
    
    $timeTextPlain = $daysBefore === 2 ? 'in 2 days' : 'tomorrow';
    $headerText = $daysBefore === 2 ? 'Event Reminder - Event in 2 Days!' : 'Event Reminder - Your Event is Tomorrow!';
    
    $textBody = "$headerText\n\n"
              . "Dear $name,\n\n"
              . "This is a reminder that you have registered for an event happening $timeTextPlain!\n\n"
              . "EVENT DETAILS:\n"
              . "Event: $eventTitle\n"
              . "Date & Time: $formattedDate\n\n"
              . "Description:\n$eventSummary\n\n"
              . "Important Reminders:\n"
              . "- Please arrive on time\n"
              . "- Bring any necessary materials or documents\n"
              . "- Contact the organizer if you need to cancel\n\n"
              . "View event details at: http://localhost/scratch/events/\n\n"
              . "We look forward to seeing you at the event!\n\n"
              . "Best regards,\n"
              . "St. Cecilia's College Alumni Network Team";
    
    return sendEmail($email, $name, $subject, $htmlBody, $textBody);
}

