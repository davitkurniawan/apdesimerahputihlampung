<?php
header('Content-Type: application/json');

// 1. Resolve API Key from .env
$api_key = '';
$env_path = __DIR__ . '/.env';
if (file_exists($env_path)) {
    $env = file_get_contents($env_path);
    if (preg_match('/RAJAONGKIR_API_KEY=(.*)/', $env, $m)) {
        $api_key = trim($m[1], "\"' ");
    }
}

// Allowed Province IDs in RajaOngkir corresponding to the registration database provinces (Lampung & Jateng)
$allowed_provinces = ['21', '10'];

// Allowed City IDs in RajaOngkir corresponding to the registration database kabupaten/cities (Lampung & Jepara)
$allowed_cities = [
    // Jawa Tengah
    '149' => ['city_name' => 'Jepara', 'province_id' => '10', 'province' => 'Jawa Tengah', 'type' => 'Kabupaten', 'postal_code' => '59411'],
    // Lampung
    '21'  => ['city_name' => 'Bandar Lampung', 'province_id' => '21', 'province' => 'Lampung', 'type' => 'Kota', 'postal_code' => '35111'],
    '268' => ['city_name' => 'Metro', 'province_id' => '21', 'province' => 'Lampung', 'type' => 'Kota', 'postal_code' => '34111'],
    '220' => ['city_name' => 'Lampung Selatan', 'province_id' => '21', 'province' => 'Lampung', 'type' => 'Kabupaten', 'postal_code' => '35511'],
    '221' => ['city_name' => 'Lampung Tengah', 'province_id' => '21', 'province' => 'Lampung', 'type' => 'Kabupaten', 'postal_code' => '34111'],
    '222' => ['city_name' => 'Lampung Utara', 'province_id' => '21', 'province' => 'Lampung', 'type' => 'Kabupaten', 'postal_code' => '34511'],
    '219' => ['city_name' => 'Lampung Barat', 'province_id' => '21', 'province' => 'Lampung', 'type' => 'Kabupaten', 'postal_code' => '34811'],
    '470' => ['city_name' => 'Tulang Bawang', 'province_id' => '21', 'province' => 'Lampung', 'type' => 'Kabupaten', 'postal_code' => '34611'],
    '446' => ['city_name' => 'Tanggamus', 'province_id' => '21', 'province' => 'Lampung', 'type' => 'Kabupaten', 'postal_code' => '35311'],
    '223' => ['city_name' => 'Lampung Timur', 'province_id' => '21', 'province' => 'Lampung', 'type' => 'Kabupaten', 'postal_code' => '34311'],
    '492' => ['city_name' => 'Way Kanan', 'province_id' => '21', 'province' => 'Lampung', 'type' => 'Kabupaten', 'postal_code' => '34711'],
    '342' => ['city_name' => 'Pesawaran', 'province_id' => '21', 'province' => 'Lampung', 'type' => 'Kabupaten', 'postal_code' => '35311'],
    '362' => ['city_name' => 'Pringsewu', 'province_id' => '21', 'province' => 'Lampung', 'type' => 'Kabupaten', 'postal_code' => '35371'],
    '267' => ['city_name' => 'Mesuji', 'province_id' => '21', 'province' => 'Lampung', 'type' => 'Kabupaten', 'postal_code' => '34611'],
    '471' => ['city_name' => 'Tulang Bawang Barat', 'province_id' => '21', 'province' => 'Lampung', 'type' => 'Kabupaten', 'postal_code' => '34611'],
    '343' => ['city_name' => 'Pesisir Barat', 'province_id' => '21', 'province' => 'Lampung', 'type' => 'Kabupaten', 'postal_code' => '34811']
];

// Convert allowed_cities to index-based results array for API output
$fallback_cities = [];
foreach ($allowed_cities as $id => $info) {
    $fallback_cities[] = array_merge(['city_id' => $id], $info);
}

// 3. Routing Handler based on Request Path
$req_path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '';
$req_path = str_replace('/index.php', '', $req_path);

// --- ENDPOINT: GET /api/rajaongkir/cities ---
if ($_SERVER['REQUEST_METHOD'] === 'GET' && stripos($req_path, '/api/rajaongkir/cities') !== false) {
    if (empty($api_key)) {
        echo json_encode([
            'status' => 'success',
            'source' => 'local_fallback',
            'results' => $fallback_cities
        ]);
        exit;
    }

    // Try fetching from RajaOngkir API
    $ch = curl_init('https://api.rajaongkir.com/starter/city');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["key: $api_key"]);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $data = json_decode($response, true);
    if ($http_code === 200 && isset($data['rajaongkir']['results'])) {
        // Filter output to match ONLY allowed cities from the database
        $filtered_results = [];
        foreach ($data['rajaongkir']['results'] as $c) {
            if (array_key_exists((string)$c['city_id'], $allowed_cities)) {
                $filtered_results[] = $c;
            }
        }
        echo json_encode([
            'status' => 'success',
            'source' => 'rajaongkir_api',
            'results' => $filtered_results
        ]);
    } else {
        // Fallback on API failure
        echo json_encode([
            'status' => 'success',
            'source' => 'local_fallback_on_api_error',
            'results' => $fallback_cities
        ]);
    }
    exit;
}

// --- ENDPOINT: POST /api/rajaongkir/cost ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && stripos($req_path, '/api/rajaongkir/cost') !== false) {
    $destination = $_POST['destination'] ?? '';
    $courier = strtolower($_POST['courier'] ?? 'jne');
    $weight = (int)($_POST['weight'] ?? 1000); // weight in grams
    $origin = '55'; // Bantul, Yogyakarta as BUMDes central warehouse simulation

    if (empty($destination)) {
        echo json_encode(['status' => 'error', 'message' => 'Destination city ID is required.']);
        exit;
    }

    // Validate courier
    if (!in_array($courier, ['jne', 'pos', 'tiki'])) {
        $courier = 'jne';
    }

    if (empty($api_key)) {
        // Generate simulated dynamic cost based on destination city ID for test checkout robustness
        $mock_costs = getMockCosts($destination, $courier);
        echo json_encode([
            'status' => 'success',
            'source' => 'local_simulation',
            'results' => $mock_costs
        ]);
        exit;
    }

    // Call RajaOngkir API
    $post_data = http_build_query([
        'origin' => $origin,
        'destination' => $destination,
        'weight' => $weight,
        'courier' => $courier
    ]);

    $ch = curl_init('https://api.rajaongkir.com/starter/cost');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "key: $api_key",
        "content-type: application/x-www-form-urlencoded"
    ]);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $data = json_decode($response, true);
    if ($http_code === 200 && isset($data['rajaongkir']['results'][0]['costs'])) {
        echo json_encode([
            'status' => 'success',
            'source' => 'rajaongkir_api',
            'results' => $data['rajaongkir']['results'][0]['costs']
        ]);
    } else {
        // API failure fallback
        $mock_costs = getMockCosts($destination, $courier);
        echo json_encode([
            'status' => 'success',
            'source' => 'local_simulation_on_api_error',
            'results' => $mock_costs
        ]);
    }
    exit;
}

// 4. Helper function to generate mock costs
function getMockCosts($destination, $courier) {
    // Generate semi-random costs based on city ID to make it look dynamic and real
    $base_multiplier = (int)$destination % 3;
    
    if ($courier === 'jne') {
        return [
            [
                'service' => 'REG',
                'description' => 'Layanan Reguler JNE',
                'cost' => [['value' => 15000 + ($base_multiplier * 4000), 'etd' => '2-3 Hari', 'note' => '']]
            ],
            [
                'service' => 'YES',
                'description' => 'Yakin Esok Sampai JNE',
                'cost' => [['value' => 22000 + ($base_multiplier * 5000), 'etd' => '1 Hari', 'note' => '']]
            ]
        ];
    } elseif ($courier === 'pos') {
        return [
            [
                'service' => 'Pos Reguler',
                'description' => 'Pos Kilat Khusus',
                'cost' => [['value' => 12000 + ($base_multiplier * 3000), 'etd' => '3-5 Hari', 'note' => '']]
            ]
        ];
    } else { // tiki
        return [
            [
                'service' => 'REG',
                'description' => 'Tiki Regular Service',
                'cost' => [['value' => 14000 + ($base_multiplier * 3500), 'etd' => '2-4 Hari', 'note' => '']]
            ],
            [
                'service' => 'ONS',
                'description' => 'Tiki Over Night Service',
                'cost' => [['value' => 21000 + ($base_multiplier * 4500), 'etd' => '1 Hari', 'note' => '']]
            ]
        ];
    }
}
