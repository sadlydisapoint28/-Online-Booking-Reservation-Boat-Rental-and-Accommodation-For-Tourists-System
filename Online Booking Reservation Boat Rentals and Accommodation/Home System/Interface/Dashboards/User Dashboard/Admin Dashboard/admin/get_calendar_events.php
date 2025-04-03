<?php
session_start();
require_once '../../../../config/database.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    exit('Unauthorized');
}

// Get boat ID from request
$boat_id = isset($_GET['boat_id']) ? (int)$_GET['boat_id'] : 0;

if (!$boat_id) {
    http_response_code(400);
    exit('Invalid boat ID');
}

try {
    // Get bookings
    $stmt = $conn->prepare("
        SELECT 
            b.id,
            b.booking_date as start,
            DATE_ADD(b.booking_date, INTERVAL b.duration HOUR) as end,
            c.name as customer_name,
            b.status,
            b.total_amount
        FROM bookings b
        JOIN customers c ON b.customer_id = c.id
        WHERE b.boat_id = ?
        AND b.booking_date >= CURDATE()
        AND b.status != 'cancelled'
        ORDER BY b.booking_date ASC
    ");
    $stmt->execute([$boat_id]);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get maintenance schedules
    $stmt = $conn->prepare("
        SELECT 
            id,
            scheduled_date as start,
            DATE_ADD(scheduled_date, INTERVAL 2 HOUR) as end,
            maintenance_type,
            description,
            status
        FROM boat_maintenance
        WHERE boat_id = ?
        AND scheduled_date >= CURDATE()
        AND status != 'completed'
        ORDER BY scheduled_date ASC
    ");
    $stmt->execute([$boat_id]);
    $maintenance = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format events for FullCalendar
    $events = [];

    // Add bookings
    foreach ($bookings as $booking) {
        $events[] = [
            'id' => 'booking_' . $booking['id'],
            'title' => 'Booking: ' . $booking['customer_name'],
            'start' => $booking['start'],
            'end' => $booking['end'],
            'backgroundColor' => '#3B82F6', // Blue
            'borderColor' => '#2563EB',
            'extendedProps' => [
                'type' => 'booking',
                'amount' => $booking['total_amount'],
                'status' => $booking['status']
            ]
        ];
    }

    // Add maintenance
    foreach ($maintenance as $maint) {
        $events[] = [
            'id' => 'maintenance_' . $maint['id'],
            'title' => 'Maintenance: ' . ucfirst($maint['maintenance_type']),
            'start' => $maint['start'],
            'end' => $maint['end'],
            'backgroundColor' => '#EF4444', // Red
            'borderColor' => '#DC2626',
            'extendedProps' => [
                'type' => 'maintenance',
                'description' => $maint['description'],
                'status' => $maint['status']
            ]
        ];
    }

    // Send response
    header('Content-Type: application/json');
    echo json_encode($events);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
} 