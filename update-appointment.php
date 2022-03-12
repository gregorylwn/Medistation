<?php 
    require_once 'renewSession.php';
    require_once 'conn.php';

    if (isset($_COOKIE['accountType']) && $_COOKIE['accountType']== 'Physician'){
        $id = $_GET['id'];
        $status = $_GET['status'];

        $sql = "UPDATE physicianavailability SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $status, $id);
        $val = $stmt->execute();


        if ($val){
            header('Location: psychiatrist-dashboard.php?status=Open&success=true');
        } else {
            header('Location: psychiatrist-dashboard.php?status=Open&success=false');
        }
    }

?>