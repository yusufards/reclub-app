<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Venue;

class VenueSeeder extends Seeder
{
    public function run(): void
    {
        // Data Venue Pulau Jawa (Bandung, Jakarta, Bodetabek, Jateng, DIY, Jatim)
        // Harga dan Rating akan otomatis di-nol-kan di dalam looping di bawah.
        $venues = [
            // --- BANDUNG (30 Data) ---
            ['name' => 'GOR Saparua', 'address' => 'Jl. Banda No.28', 'city' => 'Bandung', 'latitude' => -6.908422, 'longitude' => 107.613615],
            ['name' => 'GOR Pajajaran', 'address' => 'Jl. Pajajaran No.37', 'city' => 'Bandung', 'latitude' => -6.905488, 'longitude' => 107.597288],
            ['name' => 'Sport Jabar Arcamanik', 'address' => 'Jl. Pacuan Kuda No.140', 'city' => 'Bandung', 'latitude' => -6.903083, 'longitude' => 107.674312],
            ['name' => 'Stadion Siliwangi', 'address' => 'Jl. Lombok No.10', 'city' => 'Bandung', 'latitude' => -6.909675, 'longitude' => 107.619098],
            ['name' => 'Stadion Sidolig', 'address' => 'Jl. A. Yani No.262', 'city' => 'Bandung', 'latitude' => -6.916892, 'longitude' => 107.632231],
            ['name' => 'GOR C-Tra Arena', 'address' => 'Jl. Cikutra No.278', 'city' => 'Bandung', 'latitude' => -6.890600, 'longitude' => 107.633500],
            ['name' => 'Lodaya Sports Center', 'address' => 'Jl. Lodaya No.15', 'city' => 'Bandung', 'latitude' => -6.931211, 'longitude' => 107.623122],
            ['name' => 'GBLA Stadium', 'address' => 'Gedebage', 'city' => 'Bandung', 'latitude' => -6.957583, 'longitude' => 107.712122],
            ['name' => 'Sabuga ITB', 'address' => 'Jl. Tamansari No.73', 'city' => 'Bandung', 'latitude' => -6.886777, 'longitude' => 107.608333],
            ['name' => 'Queen Futsal', 'address' => 'Jl. Brigjen Katamso No.66', 'city' => 'Bandung', 'latitude' => -6.898871, 'longitude' => 107.636789],
            ['name' => 'Bikasoga Sport Center', 'address' => 'Jl. Suryalai Timur No.1', 'city' => 'Bandung', 'latitude' => -6.944321, 'longitude' => 107.632111],
            ['name' => 'Progresif Futsal', 'address' => 'Jl. Soekarno Hatta No.785A', 'city' => 'Bandung', 'latitude' => -6.936000, 'longitude' => 107.671000],
            ['name' => 'Futsal 35', 'address' => 'Jl. Setiabudi No.35', 'city' => 'Bandung', 'latitude' => -6.887222, 'longitude' => 107.597888],
            ['name' => 'Sampoerna Sports', 'address' => 'Jl. Padasaluyu Indah', 'city' => 'Bandung', 'latitude' => -6.877891, 'longitude' => 107.593412],
            ['name' => 'Scudetto Futsal', 'address' => 'Jl. Jend. Sudirman No.629', 'city' => 'Bandung', 'latitude' => -6.919211, 'longitude' => 107.574332],
            ['name' => 'Muara Futsal', 'address' => 'Jl. Peta No.241', 'city' => 'Bandung', 'latitude' => -6.931888, 'longitude' => 107.587666],
            ['name' => 'D\'Groove Sport', 'address' => 'Jl. Soekarno Hatta No.27', 'city' => 'Bandung', 'latitude' => -6.924555, 'longitude' => 107.578999],
            ['name' => 'Pasaga ITB', 'address' => 'Jl. Cisitu Indah VI', 'city' => 'Bandung', 'latitude' => -6.876544, 'longitude' => 107.611233],
            ['name' => 'Tubagus Futsal', 'address' => 'Jl. Tubagus Ismail No.5', 'city' => 'Bandung', 'latitude' => -6.888765, 'longitude' => 107.620987],
            ['name' => 'Zone 73 Futsal', 'address' => 'Jl. A.H. Nasution No.73', 'city' => 'Bandung', 'latitude' => -6.906777, 'longitude' => 107.689888],
            ['name' => 'GOR Lodaya', 'address' => 'Jl. Lodaya No.20', 'city' => 'Bandung', 'latitude' => -6.931555, 'longitude' => 107.623444],
            ['name' => 'GOR Batununggal', 'address' => 'Jl. Batununggal Indah IX', 'city' => 'Bandung', 'latitude' => -6.961233, 'longitude' => 107.628777],
            ['name' => 'Saraga ITB', 'address' => 'Jl. Siliwangi', 'city' => 'Bandung', 'latitude' => -6.888333, 'longitude' => 107.610555],
            ['name' => 'Gymnasium UPI', 'address' => 'Jl. Dr. Setiabudi No.229', 'city' => 'Bandung', 'latitude' => -6.860456, 'longitude' => 107.590123],
            ['name' => 'Siliwangi Bowling', 'address' => 'Jl. Aceh No.66', 'city' => 'Bandung', 'latitude' => -6.912222, 'longitude' => 107.618555],
            ['name' => 'Graha Manggala', 'address' => 'Jl. Aceh No.66', 'city' => 'Bandung', 'latitude' => -6.912333, 'longitude' => 107.618777],
            ['name' => 'Taman Film', 'address' => 'Jl. Layang Pasupati', 'city' => 'Bandung', 'latitude' => -6.899876, 'longitude' => 107.608765],
            ['name' => 'GOR Cikutra', 'address' => 'Jl. Cikutra Baru', 'city' => 'Bandung', 'latitude' => -6.897888, 'longitude' => 107.645666],
            ['name' => 'El Cavana Sport', 'address' => 'Jl. Stasiun Barat No.25', 'city' => 'Bandung', 'latitude' => -6.916789, 'longitude' => 107.601234],
            ['name' => 'Click Square', 'address' => 'Jl. Naripan No.89', 'city' => 'Bandung', 'latitude' => -6.920123, 'longitude' => 107.615678],

            // --- DKI JAKARTA (20 Data) ---
            ['name' => 'Gelora Bung Karno', 'address' => 'Jl. Pintu Satu Senayan', 'city' => 'Jakarta Pusat', 'latitude' => -6.218335, 'longitude' => 106.802216],
            ['name' => 'Istora Senayan', 'address' => 'Komplek GBK', 'city' => 'Jakarta Pusat', 'latitude' => -6.221087, 'longitude' => 106.805510],
            ['name' => 'GOR Soemantri', 'address' => 'Jl. H.R. Rasuna Said', 'city' => 'Jakarta Selatan', 'latitude' => -6.217343, 'longitude' => 106.832267],
            ['name' => 'Cilandak Sport Center', 'address' => 'Jl. TB Simatupang', 'city' => 'Jakarta Selatan', 'latitude' => -6.292415, 'longitude' => 106.803328],
            ['name' => 'My Futsal Kebayoran', 'address' => 'Jl. Komp. Hankam', 'city' => 'Jakarta Selatan', 'latitude' => -6.245122, 'longitude' => 106.772115],
            ['name' => 'Britama Arena', 'address' => 'Kelapa Gading', 'city' => 'Jakarta Utara', 'latitude' => -6.150244, 'longitude' => 106.908233],
            ['name' => 'Velodrome Rawamangun', 'address' => 'Jl. Pemuda', 'city' => 'Jakarta Timur', 'latitude' => -6.192345, 'longitude' => 106.890123],
            ['name' => 'GOR Bulungan', 'address' => 'Jl. Bulungan No.1', 'city' => 'Jakarta Selatan', 'latitude' => -6.242111, 'longitude' => 106.796555],
            ['name' => 'Arcici Sport Center', 'address' => 'Cempaka Putih', 'city' => 'Jakarta Pusat', 'latitude' => -6.183444, 'longitude' => 106.864333],
            ['name' => 'Vidi Arena', 'address' => 'Pancoran', 'city' => 'Jakarta Selatan', 'latitude' => -6.250123, 'longitude' => 106.845678],
            ['name' => 'TIBI Futsal', 'address' => 'Pasar Minggu', 'city' => 'Jakarta Selatan', 'latitude' => -6.278999, 'longitude' => 106.840111],
            ['name' => 'GOR Ciracas', 'address' => 'Jl. Raya Bogor', 'city' => 'Jakarta Timur', 'latitude' => -6.321444, 'longitude' => 106.870222],
            ['name' => 'Orion Futsal', 'address' => 'Jl. Kebon Jeruk', 'city' => 'Jakarta Barat', 'latitude' => -6.190123, 'longitude' => 106.770123],
            ['name' => 'Cometa Arena', 'address' => 'Pluit', 'city' => 'Jakarta Utara', 'latitude' => -6.123456, 'longitude' => 106.790123],
            ['name' => 'Grand Futsal Kuningan', 'address' => 'Karet Kuningan', 'city' => 'Jakarta Selatan', 'latitude' => -6.223456, 'longitude' => 106.823456],
            ['name' => 'GOR Otista', 'address' => 'Jatinegara', 'city' => 'Jakarta Timur', 'latitude' => -6.234567, 'longitude' => 106.876543],
            ['name' => 'Lapangan Banteng', 'address' => 'Sawah Besar', 'city' => 'Jakarta Pusat', 'latitude' => -6.170123, 'longitude' => 106.834567],
            ['name' => 'GOR Grogol', 'address' => 'Grogol Petamburan', 'city' => 'Jakarta Barat', 'latitude' => -6.160123, 'longitude' => 106.784567],
            ['name' => 'Stadion PTIK', 'address' => 'Kebayoran Baru', 'city' => 'Jakarta Selatan', 'latitude' => -6.240123, 'longitude' => 106.804567],
            ['name' => 'GOR Cempaka Putih', 'address' => 'Cempaka Putih', 'city' => 'Jakarta Pusat', 'latitude' => -6.180123, 'longitude' => 106.874567],

            // --- BODETABEK (15 Data) ---
            ['name' => 'Indomilk Arena', 'address' => 'Kelapa Dua', 'city' => 'Tangerang', 'latitude' => -6.251234, 'longitude' => 106.598765],
            ['name' => 'MS Indoor Soccer', 'address' => 'BSD City', 'city' => 'Tangerang Selatan', 'latitude' => -6.301234, 'longitude' => 106.687654],
            ['name' => 'GOR Dimyati', 'address' => 'Sukasari', 'city' => 'Tangerang', 'latitude' => -6.176543, 'longitude' => 106.634567],
            ['name' => 'The Springs Club', 'address' => 'Gading Serpong', 'city' => 'Tangerang', 'latitude' => -6.265432, 'longitude' => 106.612345],
            ['name' => 'Bintaro Xchange Court', 'address' => 'Bintaro Jaya', 'city' => 'Tangerang Selatan', 'latitude' => -6.284567, 'longitude' => 106.721234],
            ['name' => 'Stadion Pakansari', 'address' => 'Cibinong', 'city' => 'Bogor', 'latitude' => -6.485678, 'longitude' => 106.834567],
            ['name' => 'GOR Padjajaran', 'address' => 'Tanah Sareal', 'city' => 'Bogor', 'latitude' => -6.581234, 'longitude' => 106.790123],
            ['name' => 'Stadion Patriot', 'address' => 'Kayuringin', 'city' => 'Bekasi', 'latitude' => -6.234567, 'longitude' => 106.990123],
            ['name' => 'Grand Futsal Bekasi', 'address' => 'Raya Kuningan', 'city' => 'Bekasi', 'latitude' => -6.256789, 'longitude' => 107.012345],
            ['name' => 'Stadion Wibawa Mukti', 'address' => 'Cikarang', 'city' => 'Bekasi', 'latitude' => -6.312345, 'longitude' => 107.167890],
            ['name' => 'Golden Stick', 'address' => 'Kelapa Dua', 'city' => 'Depok', 'latitude' => -6.356789, 'longitude' => 106.845678],
            ['name' => 'Futsal 88', 'address' => 'Margonda', 'city' => 'Depok', 'latitude' => -6.378901, 'longitude' => 106.834567],
            ['name' => 'Andik Futsal', 'address' => 'Plaza Depok', 'city' => 'Depok', 'latitude' => -6.390123, 'longitude' => 106.823456],
            ['name' => 'GOR Kota Tangerang', 'address' => 'Tangerang Kota', 'city' => 'Tangerang', 'latitude' => -6.170123, 'longitude' => 106.640123],
            ['name' => 'Venom Futsal', 'address' => 'Serpong', 'city' => 'Tangerang Selatan', 'latitude' => -6.310123, 'longitude' => 106.690123],

            // --- JAWA TENGAH (10 Data) ---
            ['name' => 'Stadion Jatidiri', 'address' => 'Gajahmungkur', 'city' => 'Semarang', 'latitude' => -7.025678, 'longitude' => 110.412345],
            ['name' => 'Knight Stadium', 'address' => 'Yos Sudarso', 'city' => 'Semarang', 'latitude' => -6.956789, 'longitude' => 110.398765],
            ['name' => 'GOR Tri Lomba Juang', 'address' => 'Mugassari', 'city' => 'Semarang', 'latitude' => -6.990123, 'longitude' => 110.423456],
            ['name' => 'Metro Sports Center', 'address' => 'MT Haryono', 'city' => 'Semarang', 'latitude' => -6.980123, 'longitude' => 110.434567],
            ['name' => 'Pamularsih Futsal', 'address' => 'Semarang Barat', 'city' => 'Semarang', 'latitude' => -6.985678, 'longitude' => 110.401234],
            ['name' => 'Stadion Manahan', 'address' => 'Banjarsari', 'city' => 'Solo', 'latitude' => -7.556789, 'longitude' => 110.812345],
            ['name' => 'Sritex Arena', 'address' => 'Kebangkitan Nasional', 'city' => 'Solo', 'latitude' => -7.578901, 'longitude' => 110.812345],
            ['name' => 'Bengawan Sport', 'address' => 'Jebres', 'city' => 'Solo', 'latitude' => -7.567890, 'longitude' => 110.834567],
            ['name' => 'GOR Satria', 'address' => 'Purwokerto', 'city' => 'Purwokerto', 'latitude' => -7.412345, 'longitude' => 109.245678],
            ['name' => 'Score Futsal', 'address' => 'Dukuhwaluh', 'city' => 'Purwokerto', 'latitude' => -7.423456, 'longitude' => 109.267890],

            // --- DIY YOGYAKARTA (10 Data) ---
            ['name' => 'GOR UNY', 'address' => 'Karangmalang', 'city' => 'Yogyakarta', 'latitude' => -7.773456, 'longitude' => 110.386789],
            ['name' => 'Stadion Maguwoharjo', 'address' => 'Sleman', 'city' => 'Sleman', 'latitude' => -7.745678, 'longitude' => 110.423456],
            ['name' => 'Stadion Sultan Agung', 'address' => 'Bantul', 'city' => 'Bantul', 'latitude' => -7.890123, 'longitude' => 110.378901],
            ['name' => 'Planet Futsal Jogja', 'address' => 'Ring Road Utara', 'city' => 'Yogyakarta', 'latitude' => -7.760123, 'longitude' => 110.389012],
            ['name' => 'GOR Among Rogo', 'address' => 'Semaki', 'city' => 'Yogyakarta', 'latitude' => -7.798765, 'longitude' => 110.387654],
            ['name' => 'Tennis Lembah UGM', 'address' => 'Kampus UGM', 'city' => 'Yogyakarta', 'latitude' => -7.768901, 'longitude' => 110.378901],
            ['name' => 'Jogja Futsal Land', 'address' => 'Umbulharjo', 'city' => 'Yogyakarta', 'latitude' => -7.812345, 'longitude' => 110.384567],
            ['name' => 'Muhibbin Futsal', 'address' => 'Sewon', 'city' => 'Bantul', 'latitude' => -7.845678, 'longitude' => 110.367890],
            ['name' => 'Bardosono Futsal', 'address' => 'Jl. Kaliurang', 'city' => 'Sleman', 'latitude' => -7.723456, 'longitude' => 110.401234],
            ['name' => 'GOR Klebengan', 'address' => 'Depok', 'city' => 'Sleman', 'latitude' => -7.765432, 'longitude' => 110.390123],

            // --- JAWA TIMUR (15 Data) ---
            ['name' => 'Gelora Bung Tomo', 'address' => 'Pakal', 'city' => 'Surabaya', 'latitude' => -7.223456, 'longitude' => 112.634567],
            ['name' => 'DBL Arena', 'address' => 'Jl. Ahmad Yani', 'city' => 'Surabaya', 'latitude' => -7.312345, 'longitude' => 112.723456],
            ['name' => 'GOR Kertajaya', 'address' => 'Kertajaya', 'city' => 'Surabaya', 'latitude' => -7.284567, 'longitude' => 112.784567],
            ['name' => 'Futsal Ole', 'address' => 'Ngagel', 'city' => 'Surabaya', 'latitude' => -7.290123, 'longitude' => 112.745678],
            ['name' => 'Gool Futsal', 'address' => 'Jagir', 'city' => 'Surabaya', 'latitude' => -7.301234, 'longitude' => 112.734567],
            ['name' => 'Gelora 10 November', 'address' => 'Tambaksari', 'city' => 'Surabaya', 'latitude' => -7.251234, 'longitude' => 112.756789],
            ['name' => 'Kodam Brawijaya', 'address' => 'Sawunggaling', 'city' => 'Surabaya', 'latitude' => -7.295678, 'longitude' => 112.712345],
            ['name' => 'Ubaya Sports Center', 'address' => 'Tenggilis', 'city' => 'Surabaya', 'latitude' => -7.320123, 'longitude' => 112.767890],
            ['name' => 'GOR Ken Arok', 'address' => 'Kedungkandang', 'city' => 'Malang', 'latitude' => -8.012345, 'longitude' => 112.645678],
            ['name' => 'Stadion Gajayana', 'address' => 'Klojen', 'city' => 'Malang', 'latitude' => -7.976543, 'longitude' => 112.623456],
            ['name' => 'Champions Futsal', 'address' => 'Soekarno Hatta', 'city' => 'Malang', 'latitude' => -7.945678, 'longitude' => 112.612345],
            ['name' => 'Stadion Kanjuruhan', 'address' => 'Kepanjen', 'city' => 'Malang', 'latitude' => -8.156789, 'longitude' => 112.578901],
            ['name' => 'GOR Delta', 'address' => 'Pahlawan', 'city' => 'Sidoarjo', 'latitude' => -7.456789, 'longitude' => 112.712345],
            ['name' => 'Stadion Surajaya', 'address' => 'Lamongan', 'city' => 'Lamongan', 'latitude' => -7.112345, 'longitude' => 112.423456],
            ['name' => 'GOR Jayabaya', 'address' => 'Pesantren', 'city' => 'Kediri', 'latitude' => -7.834567, 'longitude' => 112.034567],
        ];

        foreach ($venues as $v) {
            // Override price dan rating menjadi 0
            $v['price_per_hour'] = 0;
            $v['rating'] = 0;
            
            Venue::create($v);
        }
    }
}