<?php
require '../database/database.php';
session_start();

$db = new Database();

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit();
}

$pending_requests = $db->getPendingRentalRequests();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pending Rental Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #121212;
            color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }

        .container {
            margin-top: 40px;
        }

        .card {
            background-color: #1e1e1e;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,255,255,0.2);
        }

        h2 {
            color: #00e0ff;
            font-weight: bold;
        }

        .btn {
            font-weight: 600;
        }

        .btn-success {
            background-color: #28a745;
            border: none;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }

        .table {
            color: #fff;
        }

        .table thead {
            background-color: #343a40;
        }

        .table tbody tr:hover {
            background-color: #2d3238;
        }

        .text-muted {
            color: #aaa !important;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <h2><i class="fas fa-clock me-2"></i>Pending Rental Requests</h2>
        <a href="dashboard.php" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
        </a>

        <?php
        if (isset($_SESSION['admin_message'])) {
            echo "<script>Swal.fire('Success!', '".addslashes($_SESSION['admin_message'])."', 'success');</script>";
            unset($_SESSION['admin_message']);
        }
        if (isset($_SESSION['admin_error'])) {
            echo "<script>Swal.fire('Error!', '".addslashes($_SESSION['admin_error'])."', 'error');</script>";
            unset($_SESSION['admin_error']);
        }
        ?>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle text-center">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Space</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pending_requests)): ?>
                        <?php foreach ($pending_requests as $row): ?>
                        <tr>
                            <td class="text-start"><?= htmlspecialchars($row['Client_fn'].' '.$row['Client_ln']) ?></td>
                            <td><?= htmlspecialchars($row['Name']) ?></td>
                            <td><?= htmlspecialchars($row['StartDate']) ?></td>
                            <td><?= htmlspecialchars($row['EndDate']) ?></td>
                            <td>
                                <form method="post" action="process_request.php" class="d-inline">
                                    <input type="hidden" name="request_id" value="<?= $row['Request_ID'] ?>">
                                    <button name="action" value="accept" class="btn btn-success btn-sm me-1"
                                        onclick="return confirm('Are you sure you want to ACCEPT this rental request?')">
                                        <i class="fas fa-check-circle me-1"></i>Accept
                                    </button>
                                    <button name="action" value="reject" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to REJECT this rental request?')">
                                        <i class="fas fa-times-circle me-1"></i>Reject
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center text-muted">No pending rental requests found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
