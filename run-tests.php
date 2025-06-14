<?php

/**
 * Test Runner Script untuk Rental Blacklist Application
 * 
 * Script ini menjalankan semua unit test dan feature test
 * serta memberikan laporan hasil test yang komprehensif.
 */

echo "=== RENTAL BLACKLIST APPLICATION TEST SUITE ===\n\n";

// Test categories dengan penjelasan
$testCategories = [
    'Unit Tests' => [
        'description' => 'Test individual components dan business logic',
        'tests' => [
            'tests/Unit/Models/UserTest.php' => 'Test User model - balance management, unlock functionality',
            'tests/Unit/Models/TopupRequestTest.php' => 'Test TopupRequest model - invoice generation, status management',
            'tests/Unit/Models/RentalBlacklistTest.php' => 'Test RentalBlacklist model - phone normalization, search',
            'tests/Unit/Controllers/TopupControllerTest.php' => 'Test Admin TopupController - approve/reject functionality',
            'tests/Unit/Helpers/PhoneHelperTest.php' => 'Test PhoneHelper - phone number normalization',
        ]
    ],
    'Feature Tests' => [
        'description' => 'Test end-to-end functionality dan user workflows',
        'tests' => [
            'tests/Feature/AuthenticationTest.php' => 'Test authentication flow - login, register, role access',
            'tests/Feature/BlacklistManagementTest.php' => 'Test blacklist CRUD, search, data censoring',
            'tests/Feature/TopupFeatureTest.php' => 'Test topup workflow - create, upload proof, admin approval',
            'tests/Feature/ApiTest.php' => 'Test API endpoints - public search, authenticated operations',
        ]
    ]
];

echo "Test Categories:\n";
foreach ($testCategories as $category => $info) {
    echo "• {$category}: {$info['description']}\n";
}
echo "\n";

// Function to run a single test file
function runTest($testFile, $description) {
    echo "Running: {$testFile}\n";
    echo "Description: {$description}\n";
    
    $command = "php artisan test {$testFile} --verbose";
    $output = [];
    $returnCode = 0;
    
    exec($command . " 2>&1", $output, $returnCode);
    
    $result = [
        'file' => $testFile,
        'description' => $description,
        'success' => $returnCode === 0,
        'output' => implode("\n", $output)
    ];
    
    if ($result['success']) {
        echo "✅ PASSED\n";
    } else {
        echo "❌ FAILED\n";
        echo "Error Output:\n" . $result['output'] . "\n";
    }
    
    echo str_repeat("-", 80) . "\n\n";
    
    return $result;
}

// Run all tests
$allResults = [];
$totalTests = 0;
$passedTests = 0;

foreach ($testCategories as $category => $info) {
    echo "=== {$category} ===\n\n";
    
    foreach ($info['tests'] as $testFile => $description) {
        $result = runTest($testFile, $description);
        $allResults[] = $result;
        $totalTests++;
        
        if ($result['success']) {
            $passedTests++;
        }
    }
    
    echo "\n";
}

// Generate summary report
echo "=== TEST SUMMARY REPORT ===\n\n";

echo "Total Tests: {$totalTests}\n";
echo "Passed: {$passedTests}\n";
echo "Failed: " . ($totalTests - $passedTests) . "\n";
echo "Success Rate: " . round(($passedTests / $totalTests) * 100, 2) . "%\n\n";

// Detailed results
echo "=== DETAILED RESULTS ===\n\n";

foreach ($allResults as $result) {
    $status = $result['success'] ? '✅ PASSED' : '❌ FAILED';
    echo "{$status} - {$result['file']}\n";
    echo "   {$result['description']}\n";
    
    if (!$result['success']) {
        echo "   Error: " . substr($result['output'], 0, 200) . "...\n";
    }
    
    echo "\n";
}

// Test coverage information
echo "=== TEST COVERAGE INFORMATION ===\n\n";

$coverageAreas = [
    'Models' => [
        'User' => 'Balance management, user unlock functionality, relationships',
        'TopupRequest' => 'Invoice generation, status management, expiration handling',
        'RentalBlacklist' => 'Phone normalization, search functionality, data relationships',
    ],
    'Controllers' => [
        'Admin/TopupController' => 'Approve/reject topup, balance updates, status filtering',
        'Authentication' => 'Login, register, password reset, role-based access',
        'BlacklistController' => 'CRUD operations, file upload, data validation',
    ],
    'Features' => [
        'Authentication Flow' => 'Complete user authentication and authorization',
        'Blacklist Management' => 'Create, search, data censoring, user access control',
        'Topup System' => 'Request creation, payment proof upload, admin approval',
        'API Endpoints' => 'Public search, authenticated operations, rate limiting',
    ],
    'Helpers' => [
        'PhoneHelper' => 'Phone number normalization for various formats',
    ]
];

foreach ($coverageAreas as $area => $components) {
    echo "{$area}:\n";
    foreach ($components as $component => $description) {
        echo "  • {$component}: {$description}\n";
    }
    echo "\n";
}

// Performance considerations
echo "=== PERFORMANCE TEST NOTES ===\n\n";
echo "• PhoneHelper normalization tested with 1000 operations\n";
echo "• Search functionality tested with pagination\n";
echo "• API rate limiting tested (requires actual rate limiting setup)\n";
echo "• Database operations tested with factory data\n\n";

// Security test coverage
echo "=== SECURITY TEST COVERAGE ===\n\n";
echo "• Authentication and authorization\n";
echo "• Role-based access control\n";
echo "• Data censoring for non-authorized users\n";
echo "• API authentication requirements\n";
echo "• Mass assignment protection\n";
echo "• File upload validation\n";
echo "• Input validation and sanitization\n\n";

// Final recommendations
echo "=== RECOMMENDATIONS ===\n\n";

if ($passedTests === $totalTests) {
    echo "🎉 All tests passed! Your application is well-tested.\n\n";
    echo "Next steps:\n";
    echo "• Run tests regularly during development\n";
    echo "• Add integration tests for complex workflows\n";
    echo "• Consider adding performance benchmarks\n";
    echo "• Set up continuous integration (CI) pipeline\n";
} else {
    echo "⚠️  Some tests failed. Please review and fix the issues.\n\n";
    echo "Debugging steps:\n";
    echo "• Check database migrations are up to date\n";
    echo "• Verify all required dependencies are installed\n";
    echo "• Ensure test database is properly configured\n";
    echo "• Review failed test output for specific errors\n";
}

echo "\n=== END OF TEST REPORT ===\n";
