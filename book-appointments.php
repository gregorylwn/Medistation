<?php
    require_once 'renewSession.php';
    require_once 'conn.php';

    if($_SERVER["REQUEST_METHOD"] == "GET"){
        $sql = "SELECT physician.physicianID, physician.FName, physician.LName, 
        physician.Speciality, physicianprofile.image FROM physician INNER JOIN physicianprofile on 
        physician.physicianID=physicianprofile.physicianID";
        $stmt = $conn->query($sql);
        $physicians = $stmt->fetch_all(MYSQLI_ASSOC);
        $availability = null;
        $physician = null;

        $sql1 = "SELECT clientID FROM client WHERE AccountID = ? LIMIT 1";
        $stmt = $conn->prepare($sql1);
        $stmt->bind_param("s", $_COOKIE['accountId']);
        $stmt->execute();
        $client = $stmt->get_result()->fetch_object();

        if(isset($_GET["id"])){
            $sql = "SELECT *, physician.FName, physician.LName FROM physicianprofile INNER JOIN 
            physician on physicianprofile.physicianID=physician.physicianID WHERE physicianprofile.physicianID = ? LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $_GET["id"]);
            $stmt->execute();
            $result = $stmt->get_result();
            $physician = $result->fetch_object();

            $sql2 = "SELECT id, avaDate, avaTime, physicianID FROM physicianavailability WHERE physicianID = ? AND status = 'Open'";
            $stmt = $conn->prepare($sql2);
            $stmt->bind_param("s", $_GET["id"]);
            $stmt->execute();
            $availability = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }
    } 
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $physicianID = $_POST["physicianID"];
        $clientID = $_POST["clientID"];
        $avaTime = $_POST["time"];
        $avaDate = $_POST["date"];
        $id = $_POST["id"];
        $roomGenerated = 0;
        $status = "Pending";

        $sql3 = "INSERT INTO upcomingappointments (physicianID, clientID, aptDate, aptTime, roomGenerated, status) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql3);
        $stmt->bind_param("ssssss", $physicianID, $clientID, $avaDate, $avaTime, $roomGenerated, $status);
        $val = $stmt->execute();

        if ($val){
            $sql4 = "UPDATE physicianavailability SET status = 'Booked' WHERE id = ?";
            $stmt = $conn->prepare($sql4);
            $stmt->bind_param("i", $id);
            $val = $stmt->execute();

            if ($val){ 
                header('Location: client-dashboard.php?booking=success');
            } else {
                header('Location: book-appointments.php?booking=fail');
            }
        } else {
            header('Location: book-appointments.php?booking=fail');
        }
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="js/book-appointment.js" defer></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>Book Appointment - MediStation</title>
</head>

<body class="min-h-screen flex flex-col bg-[#cbd4e1]">
    <div class="flex flex-1 w-full h-full">
        <?php include 'client-sidebar.php' ?>
        <div class="flex flex-col grow">
            <?php include 'client-nav.php' ?>
            <div
                class="bg-white/40 rounded max-w-[75%] max-h-[45%] w-full h-full overflow-auto mx-auto my-2 p-2 shadow-xl">
                <form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>"
                    class="flex flex-wrap items-center justify-center w-full gap-3">
                    <?php
                        foreach ($physicians as $row) : ?>
                    <div class="flex flex-col justify-center items-center bg-black/60 px-6 py-3 rounded shadow-xl">
                        <img src="<?php echo $row['image'] ?>"
                            class="w-[5rem] h-[5rem] object-cover object-center rounded-full" />
                        <p class="text-white text-lg"><?= $row['FName']." ".$row['LName'] ?></p>
                        <input type="hidden" name="id" value="<?php echo $row['physicianID'] ?>" />
                        <button class="bg-emerald-600 text-white px-4 py-1 rounded hover:scale-105">Select</button>
                    </div>
                    <?php 
                        endforeach
                    ?>
                </form>
            </div>
            <?php
                if ($physician){
                    ?>
            <div
                class="bg-black/40 absolute inset-0 flex flex-col items-center justify-center w-full h-full overflow-hidden">
                <button id="close-btn"
                    class="absolute z-50 top-5 right-5 bg-white flex items-center justify-center hover:bg-red-500 hover:text-white cursor-pointer">
                    <span class="material-icons">
                        close
                    </span>
                </button>
                <div class="bg-white w-[75%] rounded my-2">
                    <div class="w-full flex">
                        <div class="flex flex-col justify-center space-y-3 p-4 w-[25%]">
                            <img src="<?php echo $physician->image ?>" alt="profile"
                                class="w-[15rem] h-[20rem] object-cover rounded" />
                            <h2 class="text-2xl font-medium">
                                <?php echo $physician->FName." ". $physician->LName ?>
                            </h2>
                            <div>
                                <button id="switch" class="bg-slate-700 text-white px-4 py-2 rounded">Schedule
                                    Appointment</button>
                            </div>
                        </div>
                        <div id="detail" class="bg-[#cbd4e1] grow p-4 h-[450px] space-y-4 overflow-auto">
                            <div class="bg-white ring-1 ring-slate-500 rounded p-2">
                                <p><?php echo $physician->description ?></p>
                            </div>
                            <div class="grid grid-cols-2 gap-8">
                                <div class="border-b border-gray-500 py-2 space-y-1">
                                    <p class="text-xl font-medium">Languages</p>
                                    <p class="text-gray-700 font-medium"><?php echo $physician->language ?></p>
                                    </p>
                                </div>
                                <div class="border-b border-gray-500 py-2 space-y-1">
                                    <p class="text-xl font-medium">Education</p>
                                    <p class="text-gray-700 font-medium"><?php echo $physician->education ?></p>
                                    </p>
                                </div>
                                <div class="border-b border-gray-500 py-2 space-y-1">
                                    <p class="text-xl font-medium">Years of Experience</p>
                                    <p class="text-gray-700 font-medium"><?php echo $physician->yearsOfExperience ?>
                                        years</p>
                                    </p>
                                </div>
                                <div class="border-b border-gray-500 py-2 space-y-1">
                                    <p class="text-xl font-medium">Location</p>
                                    <p class="text-gray-700 font-medium"><?php echo $physician->location ?></p>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div id="apt" class="bg-[#cbd4e1] grow p-4 h-[450px] space-y-4 overflow-auto hidden">
                            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>">
                                <table class="border-collapse w-full mx-auto border border-slate-500 my-2">
                                    <thead>
                                        <tr class="text-left bg-slate-800 text-white text-xl font-semibold">
                                            <th class="border border-slate-600">Date</th>
                                            <th class="border border-slate-600">Time</th>
                                            <th class="border border-slate-600">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                            foreach ($availability as $row): ?>
                                        <input type="hidden" name="date" value="<?php echo $row['avaDate'] ?>" />
                                        <input type="hidden" name="time" value="<?php echo $row['avaTime'] ?>" />
                                        <input type="hidden" name="physicianID"
                                            value="<?php echo $row['physicianID'] ?>" />
                                        <input type="hidden" name="clientID" value="<?php echo $client->clientID ?>" />
                                        <input type="hidden" name="id" value="<?php echo $row['id'] ?>" />
                                        <tr class="bg-slate-500 text-white hover:bg-slate-600 border border-slate-400">
                                            <td><?= date('F j, Y' ,strtotime($row['avaDate'])) ?></td>
                                            <td><?= date('g:i A' ,strtotime($row['avaTime'])) ?></td>
                                            <td>
                                                <button
                                                    class="bg-emerald-600 text-white px-4 py-1 rounded my-1 hover:scale-105">Reserve
                                                    Now</button>
                                            </td>
                                        </tr>
                                        <?php
                            endforeach
                        ?>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
                }
            ?>

            </div>
        </div>
</body>

</html>