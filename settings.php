<?php
    session_start();
    if(!isset($_SESSION['username'])) {
        header('location:login.php'); #Login Check
    } else if (isset($_SESSION['updateFeedback'])) {
        $updateFeedback = $_SESSION['updateFeedback'];
        $_SESSION['updateFeedback'] = null;
    } else {
        $updateFeedback = '';
    }

    require_once('code/connect.php');
    $db = connectToDB();
    $sql = "SELECT forename, surname, street, town, postcode, email, username, memberType
            FROM eCustomer
            WHERE customerId = :custId";
    $query = $db->prepare($sql);
    $query->bindParam(':custId',$_SESSION['id']);
    $query->execute();
    $db = null;

    $row = $query->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Rocks</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="css/settings.css">
    <link rel="icon" href="img/icon.png">
    <script src="js/hammer.js"></script>
    <script src="js/settingsPage.js"></script>
</head>
<body>
    <dialog id='dlg_feedback'>
        <div>
            <span><?php echo $updateFeedback ?></span>
            <button id='dlg_feedback_close'>Close</button>
        </div>
    </dialog>
    <header>
        <img src="img/menu_button.png" alt="" height="50" id="btn_menu"/>
        <a href="index.php" id="logo"><img src="img/logo.png" alt="" height="70"/></a>
        <h1>Settings</h1>
    </header>
    <aside>
        <ul>
            <li><a href="#details" id='nav_details'><span class="nav-icon material-symbols-rounded">account_circle</span><span class="nav-text">Details</span></a></li>
            <li><a href="#address" id='nav_address'><span class="nav-icon material-symbols-rounded">home</span><span class="nav-text">Address</span></a></li>
            <li><a href="#tier" id='nav_tier'><span class="nav-icon material-symbols-rounded">key</span><span class="nav-text">Tier</span></a></li>
            <li><a href="#delete" id='nav_delete'><span class="nav-icon material-symbols-rounded">delete_forever</span><span class="nav-text">Delete</span></a></li>
            <li><a href="index.php"><span class="nav-icon material-symbols-rounded">arrow_back</span><span class="nav-text">Back</span></a></li>
        </ul>
    </aside>
    <main>
        <form action="code/updateAccount.php" method='POST' id='update_form' onsubmit='return validateUpdate()'>
            <button type="submit" id="update_button">Update</button>
            <div id="details">
                <h2>Details</h2>
                <section>
                    <h3>Username</h3>
                    <div class="form_items">
                        <div class="form_item">
                            <span class="previous"><?php echo $row['username']?></span>
                            <div class="form_input">
                                <input type="text" id="input_username" name='input_username' placeholder=" "/>
                                <label for="input_username">New Username</label>
                            </div>
                        </div>
                    </div>
                </section>
                <section>
                    <h3>Email</h3>
                    <div class="form_items">
                        <div class="form_item">
                            <span class="previous"><?php echo $row['email']?></span>
                            <div class="form_input">
                                <input type="text" id="input_email" name='input_email' placeholder=" "/>
                                <label for="input_email">New Email Address</label>
                            </div>
                        </div>
                    </div>
                </section>
                <section>
                    <h3>Name</h3>
                    <div class="form_items">
                        <div class="form_item">
                            <span class="previous"><?php echo $row['forename']?></span>
                            <div class="form_input">
                                <input type="text" id="input_forename" name='input_forename' placeholder=" "/>
                                <label for="input_forename">New Forename</label>
                            </div>
                        </div>
                        <div class="form_item">
                            <span class="previous"><?php echo $row['surname']?></span>
                            <div class="form_input">
                                <input type="text" id="input_surname" name='input_surname' placeholder=" "/>
                                <label for="input_surname">New Surname</label>
                            </div>
                        </div>
                    </div>
                </section>
                <section>
                    <h3>Password</h3>
                    <div class="form_items">
                        <div class="form_item">
                            <span class="previous">Old Password</span>
                            <div class="form_input">
                                <input type="password" id="input_old_password" name="input_old_password" placeholder=" "/>
                                <label for="input_old_password">Old Password</label>
                            </div>
                        </div>
                        <div class="form_item">
                            <span class="previous">New Password</span>
                            <div class="form_input">
                                <input type="password" id="input_password1" name="input_password1" placeholder=" "/>
                                <label for="input_password1">New Password</label>
                            </div>
                        </div>
                        <div class="form_item">
                            <span class="previous">Confirm</span>
                            <div class="form_input">
                                <input type="password" id="input_password2" name="input_password2" placeholder=" "/>
                                <label for="input_password2">Retype New Password</label>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div id="address">
                <h2>Address</h2>
                <section>
                    <div class="form_items">
                        <div class="form_item">
                            <span class="previous"><?php echo $row['street']?></span>
                            <div class="form_input">
                                <input type="text" id="input_street" name='input_street' placeholder=" "/>
                                <label for="input_street">New Street</label>
                            </div>
                        </div>
                        <div class="form_item">
                            <span class="previous"><?php echo $row['town']?></span>
                            <div class="form_input">
                                <input type="text" id="input_town" name='input_town' placeholder=" "/>
                                <label for="input_town">New Town</label>
                            </div>
                        </div>
                        <div class="form_item">
                            <span class="previous"><?php echo $row['postcode']?></span>
                            <div class="form_input">
                                <input type="text" id="input_postcode" name='input_postcode' placeholder=" "/>
                                <label for="input_postcode">New Postcode</label>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div id="tier">
                <h2>Tier</h2>
                <section>
                    <div class="form_items">
                        <div class="form_item">
                            <span class="previous">New Tier</span>
                            <div class="form_input">
                                <select type="text" id="select_tier" name='select_tier' placeholder=" ">
                                    <option value="0" <?php if ($row['memberType'] == 0) {echo "selected";} ?>>BRONZE</option>
                                    <option value="1" <?php if ($row['memberType'] == 1) {echo "selected";} ?>>SILVER</option>
                                    <option value="2" <?php if ($row['memberType'] == 2) {echo "selected";} ?>>GOLD</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </form>
            <div class="deleteWrapper">
                <div id="delete">
                    <h2>Delete</h2>
                    <section>
                        <h3>WARNING: This cannot be undone</h3>
                        <p>To delete your account, type in your password and click delete.</p>
                        <form id='delete_form' action="code/deleteAccount.php" method='POST' onsubmit='return confirm("Are you REALLY sure you want to delete your account?")'>
                            <div class="form_items">
                                <div class="form_item">
                                    <div class="form_input">
                                        <input type="password" id="input_delete_password" name='input_delete_password' placeholder=" " required/>
                                        <label for="input_delete_password">Enter Password</label>
                                    </div>
                                    <button type='submit'>Delete</button>
                                </div>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
    </main>
    <script>
            <?php
            if ($updateFeedback) {
                echo "document.querySelector('#dlg_feedback').showModal();";
            }
            ?>
    </script>
</body>
</html>