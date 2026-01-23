<?php
// Cari berdasarkan nama parsial karena ID tidak pasti
$vSaparua = \App\Models\Venue::where('name', 'like', '%Saparua%')->first();
$vPajajaran = \App\Models\Venue::where('name', 'like', '%Pajajaran%')->first();
$vAll = \App\Models\Venue::where('name', 'not like', '%Saparua%')
    ->where('name', 'not like', '%Pajajaran%')
    ->get();

$sFutsal = \App\Models\Sport::where('name', 'Futsal')->first();
$sBasket = \App\Models\Sport::where('name', 'Basketball')->first();

if ($vSaparua && $sFutsal) {
    echo "SET [{$vSaparua->name}] (ID: {$vSaparua->id}) -> Futsal Only\n";
    $vSaparua->sports()->sync([$sFutsal->id]);
} else {
    echo "ERROR: GOR Saparua / Futsal not found.\n";
}

if ($vPajajaran && $sBasket) {
    echo "SET [{$vPajajaran->name}] (ID: {$vPajajaran->id}) -> Basketball Only\n";
    $vPajajaran->sports()->sync([$sBasket->id]);
} else {
    echo "ERROR: GOR Pajajaran / Basketball not found.\n";
}

// Sisanya biarkan default (All Sports) atau reset
$allSportIds = \App\Models\Sport::all()->pluck('id');
foreach ($vAll as $v) {
    echo "RESET [{$v->name}] -> All Sports\n";
    $v->sports()->syncWithoutDetaching($allSportIds);
}
