<?php
// XOSID Premium Seeder for Territory and Demographics
$db_host = 'db';
$db_user = 'root';
$db_pass = 'password';
$db_name = 'xosid';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    echo "Clearing existing demographic records...\n";
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    $pdo->exec("TRUNCATE TABLE tweb_keluarga");
    $pdo->exec("TRUNCATE TABLE tweb_penduduk");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    // Realistic Indonesian names for residents
    $male_names = [
        "Ahmad Prasetyo", "Budi Santoso", "Chandra Wijaya", "Dedi Hermawan", "Eko Susilo",
        "Fajar Nugroho", "Guntur Wibowo", "Hendra Setiawan", "Indra Kusuma", "Joko Riyadi",
        "Kurniawan Saputra", "Lukman Hakim", "Mulyono", "Nugroho Adi", "Oki Pratama",
        "Putra Utama", "Rian Hidayat", "Slamet Raharjo", "Taufik Hidayat", "Wahyu Hidayat"
    ];

    $female_names = [
        "Adelia Putri", "Bunga Lestari", "Citra Kirana", "Dewi Sartika", "Elisa Fitriani",
        "Fitriani", "Gita Gutawa", "Hani Safitri", "Indah Permatasari", "Julia Perez",
        "Kartika Sari", "Larasati", "Mega Utami", "Novianti", "Olivia",
        "Putri Lestari", "Rini Astuti", "Sari Indah", "Tari Kusuma", "Wulan Guritno"
    ];

    $last_names = [
        "Pratama", "Sanjaya", "Wibowo", "Hidayat", "Kusuma", "Saputra", "Santoso", "Wijaya",
        "Lestari", "Putri", "Sari", "Astuti", "Fitriani", "Nugroho", "Setiawan", "Utomo"
    ];

    // Get active clusters from database
    $stmt = $pdo->query("SELECT * FROM tweb_wil_clusterdesa ORDER BY id ASC");
    $clusters = $stmt->fetchAll();

    if (empty($clusters)) {
        die("ERROR: No clusters found in tweb_wil_clusterdesa. Please check database.\n");
    }

    echo "Found " . count($clusters) . " clusters in database.\n";

    // We will generate 30 families and around 90 residents
    $resident_id = 1;
    $family_id = 1;
    $residents_to_insert = [];
    $families_to_insert = [];
    $cluster_updates = [];

    // Helper to generate unique NIK/KK
    $base_nik = 3201021000000000;
    $base_kk = 3201024000000000;

    foreach ($clusters as $idx => $cluster) {
        $c_id = $cluster['id'];
        $dusun = $cluster['dusun'];
        $rw = $cluster['rw'];
        $rt = $cluster['rt'];

        // Only seed families in active RT level rows, or rows that represent base Dusun/RW if no child
        $should_seed = false;
        if ($rt !== '0' && $rt !== '-' && $rt !== '') {
            $should_seed = true;
        } elseif (($rt === '0' || $rt === '-' || $rt === '') && ($rw === '0' || $rw === '-' || $rw === '')) {
            // Seed a few on Dusun level just for testing
            $should_seed = ($idx % 3 === 0);
        }

        if (!$should_seed) {
            continue;
        }

        // Seed 1-3 families in this cluster
        $num_families = rand(1, 3);
        for ($f = 0; $f < $num_families; $f++) {
            $family_no = $base_kk + $family_id;
            
            // Father (Head of Family)
            $father_name = $male_names[array_rand($male_names)] . " " . $last_names[array_rand($last_names)];
            $father_nik = $base_nik + $resident_id;
            $father_id = $resident_id;
            
            $residents_to_insert[] = [
                'id' => $father_id,
                'nama' => $father_name,
                'nik' => (string)$father_nik,
                'sex' => 1, // Male
                'id_cluster' => $c_id,
                'id_kk' => $family_id,
                'kk_level' => 1 // Kepala Keluarga
            ];
            $resident_id++;

            // Mother
            $mother_name = $female_names[array_rand($female_names)] . " " . $last_names[array_rand($last_names)];
            $mother_nik = $base_nik + $resident_id;
            $mother_id = $resident_id;
            
            $residents_to_insert[] = [
                'id' => $mother_id,
                'nama' => $mother_name,
                'nik' => (string)$mother_nik,
                'sex' => 2, // Female
                'id_cluster' => $c_id,
                'id_kk' => $family_id,
                'kk_level' => 3 // Istri
            ];
            $resident_id++;

            // Children (0-2 children)
            $num_children = rand(0, 2);
            for ($c = 0; $c < $num_children; $c++) {
                $child_gender = rand(1, 2);
                $child_first = ($child_gender === 1) ? $male_names[array_rand($male_names)] : $female_names[array_rand($female_names)];
                $child_name = $child_first . " " . $last_names[array_rand($last_names)];
                $child_nik = $base_nik + $resident_id;
                
                $residents_to_insert[] = [
                    'id' => $resident_id,
                    'nama' => $child_name,
                    'nik' => (string)$child_nik,
                    'sex' => $child_gender,
                    'id_cluster' => $c_id,
                    'id_kk' => $family_id,
                    'kk_level' => 4 // Anak
                ];
                $resident_id++;
            }

            // Add Family
            $families_to_insert[] = [
                'id' => $family_id,
                'no_kk' => (string)$family_no,
                'nik_kepala' => $father_id,
                'id_cluster' => $c_id
            ];

            // Set this father as Head of Territory for this cluster occasionally
            if ($f === 0 && ($idx % 2 === 0)) {
                $cluster_updates[$c_id] = $father_id;
            }

            $family_id++;
        }
    }

    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

    echo "Inserting " . count($residents_to_insert) . " resident records...\n";
    $p_stmt = $pdo->prepare("INSERT INTO tweb_penduduk (
        id, config_id, nama, nik, id_kk, kk_level, sex, tempatlahir, tanggallahir,
        agama_id, pendidikan_kk_id, pekerjaan_id, status_kawin, warganegara_id,
        nama_ayah, nama_ibu, golongan_darah_id, id_cluster, status_dasar, is_historical
    ) VALUES (
        :id, 1, :nama, :nik, :id_kk, :kk_level, :sex, 'Jakarta', '1985-05-15',
        1, 1, 1, 1, 1,
        'Ayah', 'Ibu', '1', :id_cluster, 1, 0
    )");

    foreach ($residents_to_insert as $res) {
        $p_stmt->execute([
            ':id' => $res['id'],
            ':nama' => $res['nama'],
            ':nik' => $res['nik'],
            ':id_kk' => $res['id_kk'],
            ':kk_level' => $res['kk_level'],
            ':sex' => $res['sex'],
            ':id_cluster' => $res['id_cluster']
        ]);
    }

    echo "Inserting " . count($families_to_insert) . " family records...\n";
    $f_stmt = $pdo->prepare("INSERT INTO tweb_keluarga (
        id, config_id, no_kk, nik_kepala, id_cluster
    ) VALUES (
        :id, 1, :no_kk, :nik_kepala, :id_cluster
    )");

    foreach ($families_to_insert as $fam) {
        $f_stmt->execute([
            ':id' => $fam['id'],
            ':no_kk' => $fam['no_kk'],
            ':nik_kepala' => $fam['nik_kepala'],
            ':id_cluster' => $fam['id_cluster']
        ]);
    }

    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    echo "Updating cluster heads in tweb_wil_clusterdesa...\n";
    $c_stmt = $pdo->prepare("UPDATE tweb_wil_clusterdesa SET id_kepala = :id_kepala WHERE id = :id");
    foreach ($cluster_updates as $c_id => $k_id) {
        $c_stmt->execute([
            ':id_kepala' => $k_id,
            ':id' => $c_id
        ]);
    }

    // Set heads for main Dusuns too, to show realistic names in listing
    echo "Setting Kepala Dusun heads...\n";
    $dusuns_stmt = $pdo->query("SELECT DISTINCT dusun FROM tweb_wil_clusterdesa");
    $dusuns = $dusuns_stmt->fetchAll(PDO::FETCH_COLUMN);

    foreach ($dusuns as $d) {
        // Find a seeded resident in this Dusun to be the Kepala Dusun
        $find_stmt = $pdo->prepare("SELECT p.id FROM tweb_penduduk p JOIN tweb_wil_clusterdesa c ON p.id_cluster = c.id WHERE c.dusun = ? LIMIT 1");
        $find_stmt->execute([$d]);
        $row = $find_stmt->fetch();
        if ($row) {
            $k_id = $row['id'];
            // Update the main Dusun cluster row (where rw='0' and rt='0')
            $up_stmt = $pdo->prepare("UPDATE tweb_wil_clusterdesa SET id_kepala = ? WHERE dusun = ? AND rw IN ('0', '-', '') AND rt IN ('0', '-', '')");
            $up_stmt->execute([$k_id, $d]);
        }
    }

    echo "SUCCESS: Seeding completed dynamically!\n";
    echo "Total Residents: " . count($residents_to_insert) . "\n";
    echo "Total Families: " . count($families_to_insert) . "\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
