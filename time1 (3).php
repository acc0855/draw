<?php
session_start();

$filename = 'drawing.json';

$data = file_get_contents('php://input');
if ($data) {
    $drawData = json_decode($data, true);

    if (isset($drawData['clear'])) {
        // Clear the drawing data
        file_put_contents($filename, json_encode([]));
    } else if (isset($drawData['draw'])) {
        $currentDrawData = json_decode(file_get_contents($filename), true);
        if (!$currentDrawData) {
            $currentDrawData = [];
        }
        foreach ($drawData['draw'] as $draw) {
            $currentDrawData[] = $draw;
        }
        file_put_contents($filename, json_encode($currentDrawData));
    }
} else {
    // Retrieve and send the drawing data to the client
    if (file_exists($filename)) {
        echo file_get_contents($filename);
    } else {
        echo json_encode([]);
    }
}
?>
