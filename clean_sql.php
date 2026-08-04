<?php
$file = 'e:\programing\flutter project\store\bird_soomoda.sql';
$content = file_get_contents($file);

$tablesToRemoveData = [
    'banners', 'categories', 'main_categories', 'orders', 
    'products', 'product_images', 'sessions', 'settings',
    'cache', 'cache_locks', 'failed_jobs', 'jobs', 'job_batches'
];

foreach ($tablesToRemoveData as $table) {
    $pattern = '/--\s*\r?\n-- Dumping data for table \`'.$table.'\`\s*\r?\n--\s*\r?\n.*?(-- --------------------------------------------------------)/s';
    $content = preg_replace($pattern, '$1', $content);
}

// Handle the last table if it doesn't have a trailing dashed line
$patternLast = '/--\s*\r?\n-- Dumping data for table \`users\`\s*\r?\n--\s*\r?\n.*?(--\s*\r?\n-- Indexes for dumped tables)/s';
// But wait, users is kept! 

file_put_contents('e:\programing\flutter project\store\bird_soomoda_clean.sql', $content);
echo "Done!";
