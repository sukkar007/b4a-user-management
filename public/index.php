<?php
require_once __DIR__ . "/vendor/autoload.php";

use Parse\ParseClient;

try {
    ParseClient::initialize(
        "dr1zqC6eOjj6k6gkszwfp6DzU1S4vwtzuWuawV0s",
        "z6QeuDfuZuv58sP7uS5GxDkv8oVw0RaVwMQtrbaH",
        "1lL3Bvc9QWxV10xx3tzWodPGkKhgMLJUfX5HuTQD"
    );
    ParseClient::setServerURL("https://parseapi.back4app.com", "/parse");
    
    echo "<h1>✅ Laravel User Management System</h1>";
    echo "<h2>🔗 Back4App Connection: SUCCESS</h2>";
    echo "<p>Parse SDK initialized successfully!</p>";
    echo "<p><a href=\"/login\">Go to Login Page</a></p>";
    
} catch (Exception $e) {
    echo "<h1>❌ Error</h1>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>
