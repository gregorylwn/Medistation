<!-- Sidebar -->
<?php
    require_once 'renewSession.php';
    include_once 'conn.php';
    $sql = "SELECT FName, LName, image FROM client WHERE AccountID = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $_COOKIE['accountId']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_object();
    $name = $user->FName." ".$user->LName;
    $image = $user->image;
    $page = $_SERVER['REQUEST_URI'];
?>
<div class="min-h-screen bg-[#024d81] w-full max-w-[18%]">
    <div class="flex flex-col space-y-4">
        <div class="flex flex-col text-white items-center">
            <!-- <img src="./images/logo.png" alt="Logo" /> -->
            <img src="<?php echo $image ?>" alt="profile"
                class="w-[8rem] h-[8rem] rounded-full aspect-square object-cover my-2" />
            <p class="text-xl">Welcome <?php echo $name ?></p>
        </div>
        <div class="flex flex-col px-1 py-2 text-white space-y-2">
            <a class="w-full" href="edit-profile.php">
                <div class="flex items-center px-2 py-1 space-x-2 bg-slate-700/80 hover:bg-slate-700 relative">
                    <span class="material-icons">
                        edit
                    </span>
                    <p>
                        Edit Profile
                    </p>
                    <?php
                        if (strpos($page, 'edit')){
                            ?>
                    <span class="absolute bg-blue-500 -left-2 w-1 h-full rounded"></span>
                    <?php
                        };
                    ?>
                </div>
            </a>
            <a class="w-full" href="client-dashboard.php">
                <div class="flex items-center px-2 py-1 space-x-2 bg-slate-700/80 hover:bg-slate-700 relative">
                    <span class="material-icons">
                        home
                    </span>
                    <p>
                        Dashboard
                    </p>
                    <?php
                        if (strpos($page, 'client-dashboard')){
                            ?>
                    <span class="absolute bg-blue-500 -left-2 w-1 h-full rounded"></span>
                    <?php
                        };
                    ?>
                </div>
            </a>
            <a class="w-full" href="book-appointments.php">
                <div class="flex items-center px-2 py-1 space-x-2 bg-slate-700/80 hover:bg-slate-700 relative">
                    <span class="material-icons">
                        calendar_month
                    </span>
                    <p>Book Appointment</p>
                    <?php
                        if (strpos($page, 'book-appointments')){
                            ?>
                    <span class="absolute bg-blue-500 -left-2 w-1 h-full rounded"></span>
                    <?php
                        };
                    ?>
                </div>
            </a>
            <a class="w-full" href="#">
                <div class="flex items-center px-2 py-1 space-x-2 bg-slate-700/80 hover:bg-slate-700 relative">
                    <span class="material-icons">
                        workspaces
                    </span>
                    <p>
                        Upcoming Appointments
                    </p>
                    <?php
                        if (strpos($page, 'upcoming-appointments')){
                            ?>
                    <span class="absolute bg-blue-500 -left-2 w-1 h-full rounded"></span>
                    <?php
                        };
                    ?>
                </div>
            </a>
            <a class="w-full" href="#">
                <div class="flex items-center px-2 py-1 space-x-2 bg-slate-700/80 hover:bg-slate-700 relative">
                    <span class="material-icons">
                        timeline
                    </span>
                    <p>
                        History
                    </p>
                    <?php
                        if (strpos($page, 'client-history')){
                            ?>
                    <span class="absolute bg-blue-500 -left-2 w-1 h-full rounded"></span>
                    <?php
                        };
                    ?>
                </div>
            </a>
            <a class="w-full" href="#">
                <div class="flex items-center px-2 py-1 space-x-2 bg-slate-700/80 hover:bg-slate-700 relative">
                    <span class="material-icons">
                        paid
                    </span>
                    <p>
                        Payments
                    </p>
                    <?php
                        if (strpos($page, 'client-payment')){
                            ?>
                    <span class="absolute bg-blue-500 -left-2 w-1 h-full rounded"></span>
                    <?php
                        };
                    ?>
                </div>
            </a>
        </div>
    </div>
</div>