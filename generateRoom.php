<?php
    function generate_id() {
        $s = '';
        for ($i = 0; $i < 3; $i++) {
            if ($s) {
                $s .= '-';
            }
            $s .= bin2hex(openssl_random_pseudo_bytes(2));
        }
        return $s;
    }
    require_once 'renewSession.php';
    require_once 'conn.php';

    if (isset($_COOKIE['accountType']) && $_COOKIE['accountType']== 'Physician'){
        $id = generate_id();
        $physicianID = $_GET["id"];

        $sql = "INSERT INTO rooms (id) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $id);
        $val = $stmt->execute();

        if ($val){
            $roomGenerated = 1;
            $sql1 = "UPDATE upcomingappointments SET room = ?, roomGenerated = ? WHERE id = ?";
            $stmt = $conn->prepare($sql1);
            $stmt->bind_param('sss', $id, $roomGenerated, $physicianID);
            $val = $stmt->execute();

            if ($val){
                header("Location: psychiatrist-dashboard.php?success=true");
            } else {
                header("Location: psychiatrist-dashboard.php?success=false");
            }
        } else {
            header("Location: psychiatrist-dashboard.php?success=false");
        }
    } else {
        header("Location: login.php");
    }
?>