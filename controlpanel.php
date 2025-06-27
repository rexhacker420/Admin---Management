<?php
session_start();

// Initialize session list if not set
if (!isset($_SESSION['items'])) {
    $_SESSION['items'] = [];
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item = trim($_POST['item']);
    if (!empty($item)) {
        $_SESSION['items'][] = htmlspecialchars($item);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bonheur+Royale&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CDN Link -->
     <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <title>Control Panel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            width: 100vw;
            height: 98.5vh;
            background: #000000;
            color: white;
        }

        .container {
            width: 100%;
            height: 100%;
            display: flex;
            font-family: Arial, Helvetica, sans-serif;
        }

        header {
            /* border: 2px solid black; */
            width: 15%;
            height: 100%;
            padding: 5px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        header nav {
            border: 2px solid rgb(53, 53, 53);
            border-top: none;
            border-left: none;
            border-bottom: none;
            width: 100%;
            height: 100%;
            position: relative;
            left: -10px;
            background: #000000;
            display: flex;
            justify-content: center;
        }

        header nav ul {
            text-decoration: none;
        }

        header nav .credit {
            /* border: 2px solid white; */
            width: fit-content;
            height: 50%;
            text-align: bottom;
            display: flex;
            justify-content: flex-end;
            align-items: flex-end;
            cursor: pointer;
        }

        header nav .credit .copy {
            /* border: 2px solid white; */
            width: fit-content;
            height: 60%;
            font-size: 11px;
            text-align: bottom;
            display: flex;
            justify-content: flex-end;
            align-items: flex-end;
            cursor: pointer;
        }

        header nav ul li {
            /* border: 2px solid black; */
            width: 100px;
            padding: 10px;
            margin: 20px;
            text-align: center;
            cursor: pointer;
        }

        header nav ul li a {
            color: white;
            text-decoration: none;
        }

        header nav ul li:hover {
            text-shadow: 0 5px 15px #000000, 0 -5px 45px #000000;
        }

        section {
            /* border: 2px solid white; */
            min-width: 82%;
            height: 0 0;
        }

        section .project-title {
            /* border: 2px solid white; */
            width: auto;
            margin: 0;
            padding: 1rem;
            font-size: 1.8rem;
            font-weight: 600;
            font-family: "Alkatra", system-ui;
            text-align: center;
            color: rgb(5, 255, 5);
            text-shadow: 0 0 2rem rgb(200, 255, 211);
            position: relative;
            top: 6rem;
        }

        .main-body {
            /* border: 2px solid black; */
            width: auto;
            display: flex;
            background-image: url("Vector/Vector/world.svg");
            justify-content: center;
            align-items: center;
            position: relative;
            top: 8rem;
        }

        .status-section-container {
            /* border: 2px solid white; */
            width: 100%;
            height: 45%;
        }

        .status-section-container .hack-titles {
            /* border: 2px solid #2e3dc3; */
            width: 100%;
            height: auto;
            /* background: #282f6d; */
            display: flex;
            justify-content: space-between;
            align-items: center;
            animation: visible 3s;
            transition: 3s ease-in-out;
            cursor: pointer;
        }

        @keyframes visible {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }


        .status-section-container .hack-titles .count-section {
            /* border: 2px solid white; */
            width: 14%;
            height: 100px;
            padding: 10px;
            margin: 10px;
        }

        .status-section-container .hack-titles .count-title-container {
            border: 2px solid #333c8d;
            width: 80%;
            height: fit-content;
            padding: 10px;
            font-weight: 600;
            text-align: center;
            /* background: #282f6d; */
            border-radius: 10px;
            /* box-shadow: 0 2px 10px #96a1ff, 0 -2px 10px #96a1ff; */
            cursor: pointer;
        }

        .status-section-container .hack-titles .count-auto-value-container {
            /* border: 2px solid white; */
            width: 100%;
            height: fix-content;
            font-size: 12px;
            margin-top: 20px;
            text-align: center;
            /* background: #282f6d; */
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            animation: target 2s;
            transition: 1s ease-in-out;
        }

        @keyframes target {
            0% {
                opacity: 0;
                margin-top: -20px;
            }

            50% {
                opacity: 0.2;
            }

            100% {
                opacity: 1;
                margin-top: 20;
            }
        }

        .status-section-container .hack-titles .status-section {
            /* border: 2px solid white; */
            width: 14%;
            height: 100px;
            padding: 10px;
            margin: 10px;
            /* background: #282f6d; */
        }

        .status-section-container .hack-titles .victam-section {
            /* border: 2px solid white; */
            width: 14%;
            height: 100px;
            padding: 10px;
            margin: 10px;
            /* background: #282f6d; */
        }

        .status-section-container .hack-titles .ip-section {
            /* border: 2px solid white; */
            width: 14%;
            height: 100px;
            padding: 10px;
            margin: 10px;
            /* background: #282f6d; */
        }

        .status-section-container .hack-titles .mail-section {
            /* border: 2px solid white; */
            width: 14%;
            height: 100px;
            padding: 10px;
            margin: 10px;
            /* background: #282f6d; */
        }

        .status-section-container .hack-titles .user-section {
            /* border: 2px solid white; */
            width: 14%;
            height: 100px;
            padding: 10px;
            margin: 10px;
            /* background: #282f6d; */
        }

        .status-section-container .hack-titles .pass-section {
            /* border: 2px solid white; */
            width: 14%;
            height: 100px;
            padding: 10px;
            margin: 10px;
            /* background: #282f6d; */
        }

        .status-section-container .hack-titles .device-section {
            /* border: 2px solid white; */
            width: 14%;
            height: 100px;
            padding: 10px;
            margin: 10px;
            /* background: #282f6d; */
        }


        /* Form Container */

        .form-container {
            border: 2px solid white;
            width: auto;
            padding: 2rem;

        }

        .absolute-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100vw;
            max-width: 500px;
        }
    </style>
</head>

<body>
    <div class="container">
        <header>
            <nav>
                <ul type="none">
                    <li><a href="#">Dashbord</a></li>
                    <li><a href="#">Status</a></li>
                    <li><a href="#">Setting</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Login</a></li>
                    <ul type="none" class="credit">
                        <li class="copy">Developer ❤️ by Sanjeet Kalyan</li>
                    </ul>
                </ul>
            </nav>
        </header>
        <section>
            <div class="project-title">
                <span>HACKING DASHBORD</span>
            </div>
            <div class="main-body">
                <div class="status-section-container">
                    <div class="hack-titles">
                        <div class="count-section">
                            <div class="count-title-container"><img src="">Serial</div>
                            <div class="count-auto-value-container">1</div>
                            <div class="count-auto-value-container">2</div>
                        </div>
                        <div class="status-section">
                            <div class="count-title-container">Status</div>
                            <div class="count-auto-value-container">Hacked</div>
                            <div class="count-auto-value-container">Panding...</div>
                        </div>
                        <div class="victam-section">
                            <div class="count-title-container">Victam</div>
                            <div class="count-auto-value-container">Aman</div>
                            <div class="count-auto-value-container">Sagar</div>
                        </div>
                        <div class="ip-section">
                            <div class="count-title-container">IP Address</div>
                            <div class="count-auto-value-container">192.168.218.108</div>
                            <div class="count-auto-value-container">192.168.118.147</div>
                        </div>
                        <div class="mail-section">
                            <div class="count-title-container">E-Mail</div>
                            <div class="count-auto-value-container">amankalyan220@gmail.com</div>
                            <div class="count-auto-value-container">sagarkumar8547@gmail.com</div>
                        </div>
                        <div class="user-section">
                            <div class="count-title-container">User Name</div>
                            <div class="count-auto-value-container">Aman Kalyan</div>
                            <div class="count-auto-value-container">Sagar Kumar</div>
                        </div>
                        <div class="pass-section">
                            <div class="count-title-container">Password</div>
                            <div class="count-auto-value-container">12345678</div>
                            <div class="count-auto-value-container">87654321</div>
                        </div>
                        <div class="device-section">
                            <div class="count-title-container">Device</div>
                            <div class="count-auto-value-container">Samsung A21s</div>
                            <div class="count-auto-value-container">Motorola</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-danger" onclick="openForm()">Update</button>
                        </div>


                        <!-- <h2>📝 Auto Add to List (PHP Session)</h2>

                        <form method="POST" action="">
                            <input type="text" name="item" placeholder="Type an item..." required>
                            <button type="submit">Add</button>
                        </form>

                        <ul>
                            <?php foreach ($_SESSION['items'] as $item): ?>
                                <li><?php echo $item; ?></li>
                            <?php endforeach; ?>
                        </ul> -->



                        <!-- <table border="2" style="width: 100%;">
                        <tr style="width: 100%;display: flex; justify-content: space-around; align-items: center;">
                            <td><div class="count-device hack-title-container">Number</div></td>
                            <td><div class="victam-device hack-title-container">Victam</div></td>
                            <td><div class="victam-device hack-title-container">IP Address</div></td>
                            <td><div class="victam-device hack-title-container">E-Mail</div></td>
                            <td><div class="victam-device hack-title-container">User Name</div></td>
                            <td><div class="victam-device hack-title-container">Password</div></td>
                            <td><div class="victam-device hack-title-container">Device</div></td>
                        </tr>
                        <tr style="width: 100%;display: flex; justify-content: space-around; align-items: center;">
                            <td class="count-device hack-title-container"><?php echo $num; ?></td>
                            <td class="count-device hack-title-container"><?php echo $victam; ?></td>
                            <td class="count-device hack-title-container"><?php echo $ip; ?></td>
                            <td class="count-device hack-title-container"><?php echo $mail; ?></td>
                            <td class="count-device hack-title-container"><?php echo $un; ?></td>
                            <td class="count-device hack-title-container"><?php echo $pass; ?></td>
                            <td class="count-device hack-title-container">
                                <?php
                                if (preg_match('/window/i', $device)) {
                                    echo 'Window';
                                } else {
                                    echo 'Phone';
                                }
                                ;
                                ?>
                            </td>
                        </tr>
                    </table> -->
                    </div>
                </div>
            </div>
        </section>
    </div>
        <seciton id="formContainer">
            <div class="position-absolute absolute-center p-4"  >
                <div class="absolute-center card shadow-sm border-0 bg-dark text-white ">
                    <div class="card-body p-4">
                        <h3 class="card-title text-center mb-4">Device Registration Form</h3>

                        <form action="your-action.php" method="post" class="p-4  bg-dark text-white "
                            style="max-width: 500px; margin: auto;border: none">

                            <div class="mb-3">
                                <label for="victamName" class="form-label"><i class="fas fa-user"></i> Victam Name</label>
                                <input type="text" class="form-control bg-dark text-white" id="victamName"
                                    name="victam_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="victamName" class="form-label"><i class="fas fa-user-tag"></i> Usar Name</label>
                                <input type="text" class="form-control bg-dark text-white" id="victamName"
                                    name="victam_name">
                            </div>
                            <div class="mb-3">
                                <label for="victamName" class="form-label"><i class="fas fa-lock"></i> Password</label>
                                <input type="password" class="form-control bg-dark text-white" id="victamName"
                                    name="victam_name">
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label bg-dark text-white"><i class="fas fa-link"></i> Select</label>
                                <select class="form-select bg-dark text-white" id="status" name="status" required>
                                    <option value="">None</option>
                                    <option value="not_contacted"><i class="fas fa-times-circle"></i> Not Contacted</option>
                                    <option value="contacted"><i class="fas fa-check-circle"></i> Contacted</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary">Done</button>
                                <button type="reset" class="btn btn-warning">Reset</button>
                                <button type="button" class="btn btn-danger" onclick="closeForm()">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </seciton>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openForm() {
            document.getElementById("formContainer").style.display = "block";
        }
        function closeForm() {
            document.getElementById("formContainer").style.display = "none";
        }
    </script>
</body>

</html>