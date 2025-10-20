<?php
declare(strict_types=1);
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_login();

$user = current_user();
$pdo = get_pdo();

echo "<h2>Fix Event Images</h2>";
echo "<style>
    .container { max-width: 800px; margin: 20px auto; padding: 20px; }
    .success { color: green; }
    .error { color: red; }
    .info { color: blue; }
</style>";

echo "<div class='container'>";

try {
    // Create images directory
    $imagesDir = __DIR__ . '/../images/events';
    if (!is_dir($imagesDir)) {
        mkdir($imagesDir, 0755, true);
        echo "<p class='success'>✓ Created images/events directory</p>";
    }
    
    // Get all events
    $stmt = $pdo->prepare("SELECT * FROM events ORDER BY schedule ASC");
    $stmt->execute();
    $events = $stmt->fetchAll();
    
    echo "<h3>Found " . count($events) . " events</h3>";
    
    if (empty($events)) {
        echo "<p class='error'>No events found! Let's add some sample events first.</p>";
        
        // Add sample events
        $sampleEvents = [
            [
                'title' => 'Tree Planting Activity',
                'content' => 'The Tree Planting Activity aims to promote environmental awareness and sustainability by encouraging participants to take part in greening the community. This event provides an opportunity for volunteers to contribute to environmental conservation efforts.',
                'schedule' => date('Y-m-d H:i:s', strtotime('+10 days')),
                'banner' => 'images/events/tree_planting.jpg'
            ],
            [
                'title' => 'Alumni Homecoming 2024',
                'content' => 'Join us for our annual alumni homecoming event! Reconnect with old friends, meet new alumni, and celebrate our shared memories.',
                'schedule' => date('Y-m-d H:i:s', strtotime('+30 days')),
                'banner' => 'images/events/homecoming.jpg'
            ],
            [
                'title' => 'Career Development Workshop',
                'content' => 'Enhance your professional skills with our career development workshop. Learn about resume building, interview techniques, and networking strategies.',
                'schedule' => date('Y-m-d H:i:s', strtotime('+45 days')),
                'banner' => 'images/events/workshop.jpg'
            ]
        ];
        
        $stmt = $pdo->prepare("INSERT INTO events (title, content, schedule, banner, date_created) VALUES (?, ?, ?, ?, NOW())");
        
        foreach ($sampleEvents as $event) {
            $stmt->execute([
                $event['title'],
                $event['content'], 
                $event['schedule'],
                $event['banner']
            ]);
        }
        
        echo "<p class='success'>✓ Added " . count($sampleEvents) . " sample events</p>";
        
        // Refresh events list
        $stmt = $pdo->prepare("SELECT * FROM events ORDER BY schedule ASC");
        $stmt->execute();
        $events = $stmt->fetchAll();
    }
    
    // Create actual image files for each event
    $imageNames = [
        'tree_planting.jpg' => 'Tree Planting Activity',
        'homecoming.jpg' => 'Alumni Homecoming 2024',
        'workshop.jpg' => 'Career Development Workshop',
        'networking.jpg' => 'Networking Mixer',
        'conference.jpg' => 'Annual Conference'
    ];
    
    foreach ($imageNames as $filename => $title) {
        $filepath = $imagesDir . '/' . $filename;
        
        // Create a simple image
        $width = 400;
        $height = 250;
        $image = imagecreatetruecolor($width, $height);
        
        // Random background color
        $colors = [
            [16, 185, 129],   // Green
            [59, 130, 246],   // Blue
            [139, 92, 246],   // Purple
            [239, 68, 68],    // Red
            [245, 158, 11]    // Orange
        ];
        
        $color = $colors[array_rand($colors)];
        $bgColor = imagecolorallocate($image, $color[0], $color[1], $color[2]);
        imagefill($image, 0, 0, $bgColor);
        
        // Add white text
        $textColor = imagecolorallocate($image, 255, 255, 255);
        $fontSize = 5;
        $textX = ($width - strlen($title) * 10) / 2;
        $textY = $height / 2;
        
        imagestring($image, $fontSize, $textX, $textY, $title, $textColor);
        
        // Save image
        if (imagejpeg($image, $filepath, 90)) {
            echo "<p class='success'>✓ Created image: {$filename}</p>";
        } else {
            echo "<p class='error'>❌ Failed to create: {$filename}</p>";
        }
        
        imagedestroy($image);
    }
    
    // Update events with correct banner paths
    $stmt = $pdo->prepare("UPDATE events SET banner = ? WHERE id = ?");
    
    foreach ($events as $index => $event) {
        $bannerFiles = ['tree_planting.jpg', 'homecoming.jpg', 'workshop.jpg', 'networking.jpg', 'conference.jpg'];
        $bannerFile = $bannerFiles[$index % count($bannerFiles)];
        $bannerPath = 'images/events/' . $bannerFile;
        
        $stmt->execute([$bannerPath, $event['id']]);
        echo "<p class='success'>✓ Updated event '{$event['title']}' with banner: {$bannerPath}</p>";
    }
    
    echo "<h3>Test Results:</h3>";
    
    // Show all events with their images
    $stmt = $pdo->prepare("SELECT * FROM events ORDER BY schedule ASC");
    $stmt->execute();
    $allEvents = $stmt->fetchAll();
    
    foreach ($allEvents as $event) {
        echo "<div style='border: 1px solid #ddd; margin: 15px 0; padding: 15px; border-radius: 8px;'>";
        echo "<h4>" . htmlspecialchars($event['title']) . "</h4>";
        echo "<p><strong>Banner:</strong> " . htmlspecialchars($event['banner']) . "</p>";
        
        if ($event['banner']) {
            $imageUrl = '/scratch/' . $event['banner'];
            echo "<p><strong>Image URL:</strong> " . htmlspecialchars($imageUrl) . "</p>";
            
            // Check if file exists
            $fullPath = __DIR__ . '/../' . $event['banner'];
            if (file_exists($fullPath)) {
                echo "<p class='success'>✓ Image file exists</p>";
                echo "<img src='{$imageUrl}' style='max-width: 300px; max-height: 200px; border: 1px solid #ccc;' onerror='this.style.display=\"none\"; this.nextElementSibling.style.display=\"block\";'>";
                echo "<p style='display:none; color:red;'>❌ Image failed to load</p>";
            } else {
                echo "<p class='error'>❌ Image file does not exist at: " . htmlspecialchars($fullPath) . "</p>";
            }
        }
        echo "</div>";
    }
    
    echo "<p class='info'><strong>Next steps:</strong></p>";
    echo "<ol>";
    echo "<li>Go back to your <a href='index.php'>Events page</a></li>";
    echo "<li>Check your <a href='../dashboard.php'>Dashboard</a></li>";
    echo "<li>You should now see the event images!</li>";
    echo "</ol>";
    
} catch (Exception $e) {
    echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
}

echo "</div>";
?>
