<?php
    require_once 'renewSession.php';
    include_once 'conn.php';
    
    $sql = "SELECT physician.FName, physician.LName, physicianprofile.image FROM physician INNER JOIN physicianprofile on physician.physicianID=physicianprofile.physicianID WHERE physician.AccountID = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $_COOKIE['accountId']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_object();
    $name = $user->FName." ".$user->LName;
    $image = $user->image;
    $pageName = $_SERVER['REQUEST_URI'];

?>

<div class="min-h-screen bg-[#024d81] w-full max-w-[17%]">
    <div class="flex flex-col space-y-4">
        <div class="flex flex-col text-white items-center">
            <img src="<?php echo $image ?>" alt="profile"
                class="w-[8rem] h-[8rem] rounded-full aspect-square object-cover my-2" />
            <p class="text-lg">Welcome <?php echo $name ?> </p>
        </div>
        <div class="flex flex-col px-1 py-2 text-white space-y-2">
            <a class="w-full" href="psychiatrist-profile.php">
                <div class="flex items-center px-2 py-1 space-x-2 bg-slate-700/80 hover:bg-slate-700 relative">
                    <span class="material-icons">
                        edit
                    </span>
                    <p>
                        Edit Profile
                    </p>
                    <?php
                        if (strpos($pageName, 'profile')){
                            ?>
                    <span class="absolute bg-blue-500 -left-2 w-1 h-full rounded"></span>
                    <?php
                        };
                    ?>
                </div>
            </a>
            <a class="w-full" href="psychiatrist-dashboard.php">
                <div class="flex items-center px-2 py-1 space-x-2 bg-slate-700/80 hover:bg-slate-700 relative">
                    <span class="material-icons">
                        home
                    </span>
                    <p>
                        Dashboard
                    </p>
                    <?php
                        if (strpos($pageName, 'psychiatrist-dashboard')){
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
                        if (strpos($pageName, 'upcoming-appointments')){
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
                        if (strpos($pageName, 'client-history')){
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