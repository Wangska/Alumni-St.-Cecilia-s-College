<?php
declare(strict_types=1);
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_login();

$user = current_user();

echo "<h2>Create Placeholder Images</h2>";
echo "<style>
    .container { max-width: 800px; margin: 20px auto; padding: 20px; }
    .success { color: green; }
    .error { color: red; }
</style>";

echo "<div class='container'>";

try {
    // Create images directory if it doesn't exist
    $imagesDir = __DIR__ . '/../images/events';
    if (!is_dir($imagesDir)) {
        mkdir($imagesDir, 0755, true);
        echo "<p class='success'>✓ Created images/events directory</p>";
    }
    
    // Create placeholder images
    $placeholders = [
        'homecoming.jpg' => 'Alumni Homecoming 2024',
        'workshop.jpg' => 'Career Development Workshop', 
        'networking.jpg' => 'Networking Mixer',
        'conference.jpg' => 'Annual Conference',
        'seminar.jpg' => 'Professional Seminar'
    ];
    
    foreach ($placeholders as $filename => $title) {
        $filepath = $imagesDir . '/' . $filename;
        
        // Create a simple colored rectangle as placeholder
        $width = 400;
        $height = 250;
        $image = imagecreatetruecolor($width, $height);
        
        // Define colors
        $colors = [
            imagecolorallocate($image, 16, 185, 129), // Green
            imagecolorallocate($image, 59, 130, 246), // Blue  
            imagecolorallocate($image, 139, 92, 246), // Purple
            imagecolorallocate($image, 239, 68, 68),  // Red
            imagecolorallocate($image, 245, 158, 11)  // Orange
        ];
        
        $bgColor = $colors[array_rand($colors)];
        imagefill($image, 0, 0, $bgColor);
        
        // Add text
        $textColor = imagecolorallocate($image, 255, 255, 255);
        $fontSize = 5;
        $textX = ($width - strlen($title) * 10) / 2;
        $textY = $height / 2;
        
        imagestring($image, $fontSize, $textX, $textY, $title, $textColor);
        
        // Save image
        if (imagejpeg($image, $filepath, 90)) {
            echo "<p class='success'>✓ Created placeholder: {$filename}</p>";
        } else {
            echo "<p class='error'>❌ Failed to create: {$filename}</p>";
        }
        
        imagedestroy($image);
    }
    
    echo "<p class='success'>✓ All placeholder images created!</p>";
    echo "<p>You can now run <a href='add_sample_images.php'>add_sample_images.php</a> to assign these images to your events.</p>";
    
} catch (Exception $e) {
    echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
}

echo "</div>";
?>
