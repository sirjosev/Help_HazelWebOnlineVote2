<?php
// File: admin/candidate_delete_handler.php
session_start();
require_once '../config/database.php';

if (isset($_GET['id'])) {
    $candidate_id = intval($_GET['id']);
    $event_id = intval($_GET['event_id']); // Untuk redirect kembali

    $sql = "DELETE FROM candidates WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $candidate_id);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    header("Location: manage_candidates.php?event_id=" . $event_id);
}
?>
