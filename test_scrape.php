<?php
$url = 'https://badgebps.web.bps.go.id/card/id/cm4xU2pWa3doZFlqbE5sdmhCL3hjQT09';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8',
    'Accept-Language: en-US,en;q=0.9',
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response Length: " . strlen($response) . "\n";
echo "Snippet: " . substr(strip_tags($response), 0, 500) . "\n";
if (strpos($response, 'bobcmn') !== false) {
    echo "Challenge detected.\n";
} else {
    echo "No challenge detected.\n";
}
