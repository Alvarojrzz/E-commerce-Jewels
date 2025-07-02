<?php
header('Content-Type: application/json; charset=utf-8');
include '../includes/db.php';

$sql = "SELECT 
           id AS id,
           titulo    AS title,
           fecha_inicio AS start,
           fecha_fin    AS end,
           hora      AS time,
           descripcion AS description
        FROM Eventos
        ORDER BY fecha_inicio ASC";

$result = $conn->query($sql);
$events = [];

while ($row = $result->fetch_assoc()) {
    $ev = [
        'id'          => (int)$row['id'],
        'title'       => $row['title'],
        'start'       => $row['start'],
        'end'         => $row['end']   ?: null,
        'extendedProps' => [
            'time'        => $row['time'],
            'description' => $row['description']
        ]
    ];
    $events[] = $ev;
}

echo json_encode($events);
