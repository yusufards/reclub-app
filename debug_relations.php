<?php
$venue = App\Models\Venue::first();
echo "Venue: " . $venue->name . "\n";
echo "Sports: " . $venue->sports->count() . "\n";
foreach ($venue->sports as $s) {
    echo " - " . $s->name . "\n";
}

$sport = App\Models\Sport::first();
echo "\nSport: " . $sport->name . "\n";
echo "Venues: " . $sport->venues->count() . "\n";
