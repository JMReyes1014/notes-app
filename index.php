<?php
    include('assets/php/connect.php');
    include('assets/php/addNote.php');

    // Check if the user is logged in
    if (!isset($_SESSION['username'])) {
        header("Location: assets/php/login.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" href="assets/note.png" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Home | notes-app</title>
</head>
<body class="bg-dark">
    <div class="containers">
        <div class="header">
            <h1><?php 
            $username = $_SESSION['username'];
            echo $username . "'s Notes"; ?></h1>
            <a class="save" href="#" data-bs-toggle="modal" data-bs-target="#addNoteModal" style="text-decoration: none; color: black;">Add Note</a>
            <a class="save" href="assets/php/logout.php" style="text-decoration: none; color: black;">Logout</a>
        </div>

        <div class="search">
             <input type="text" placeholder="Type to search..." id="search-input">
        </div>

        <!-- Add Note Modal -->
        <div class="modal fade" id="addNoteModal" tabindex="-1" aria-labelledby="addNoteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addNoteModalLabel">Add Note</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="assets/php/addNote.php" method="post">
                        <div class="modal-body">
                            <textarea 
                                rows="8" 
                                cols="10" 
                                placeholder="Type to add a note..." 
                                name="content" 
                                id="noteContent"
                                maxlength="200" 
                                class="form-control"></textarea>
                            <small id="remainingCount" class="form-text text-muted">200 Remaining</small>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="notes-list">
            <?php 
                $confirm_del = 'Are you sure you want to delete this note?';

                $sql = "SELECT * FROM `notes` WHERE user_id = ? ORDER BY date DESC";
                $stmt = $con->prepare($sql);
                $stmt->bind_param("i", $_SESSION['user_id']);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    $id = $row['notes_id'];
                    $content = decrypt($row['content']); // Decrypt the note content before displaying it
                    $date = $row['date'];

                    echo '
                    <div class="note">
                        <span class="note-span">' . htmlspecialchars($content) . '</span>
                        <div class="note-footer">
                            <small>' . htmlspecialchars($date) . '</small>
                            <div class="delete-icon"><a href="assets/php/delete.php?deleteid='.$id.'" style="text-decoration: none; color: black;" onclick="return confirm(\''.$confirm_del.'\')"><i class="las la-trash-alt" style="font-size: 1.3em;"></i></a></div>
                        </div>
                    </div>';
                }

                $stmt->close();
            ?>
        </div>
    </div>

    <script>
        document.getElementById('search-input').addEventListener('input', function () {
            const searchValue = this.value.toLowerCase();
            const notes = document.querySelectorAll('.notes-list .note');

            notes.forEach(note => {
                const content = note.querySelector('span').textContent.toLowerCase();
                if (content.includes(searchValue)) {
                    note.style.display = '';
                } else {
                    note.style.display = 'none';
                }
            });
        });
    </script>
    <script>
    const textarea = document.getElementById('noteContent');
    const remainingCount = document.getElementById('remainingCount');

    // Update remaining characters dynamically
    textarea.addEventListener('input', () => {
        const remaining = 200 - textarea.value.length;
        remainingCount.textContent = `${remaining} Remaining`;
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <?php
    
        // Check if a note has been added successfully
        if (isset($_SESSION['note_added']) && $_SESSION['note_added'] === true) {
            echo '
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: "Your note has been added successfully!",
                        confirmButtonText: "OK",
                        confirmButtonColor: "gray",
                        background: "#0e0d0d",
                        color: "white",
                        iconColor: "gray"
                    });
                });
            </script>';
            unset($_SESSION['note_added']);
        }
    
        // Define encryption method and secret key if not already defined
        if (!defined('ENCRYPTION_METHOD')) {
            define('ENCRYPTION_METHOD', 'AES-256-CBC');
        }
    
        if (!defined('SECRET_KEY')) {
            define('SECRET_KEY', 'I_Love_Pizza_Secret_Key');
        }
    
        if (!defined('SECRET_IV')) {
            define('SECRET_IV', 'I_Also_Like_Chocolate_Secret_IV');
        }
    
        if (isset($_POST['submit'])) {
            $note = $_POST['content'];
            $user_id = $_SESSION['user_id'];
    
            // Encrypt the note content
            $encrypted_note = encrypt($note);
    
            // Prepared statements to prevent SQL injection
            $stmt = $con->prepare("INSERT INTO `notes` (user_id, content) VALUES (?, ?)");
            $stmt->bind_param("is", $user_id, $encrypted_note);
    
            // Sweet alert for success or failure
            if ($stmt->execute()) {
                $_SESSION['note_added'] = true; // Set the session flag for success
            } else {
                echo '
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: "Failed to add note: ' . $stmt->error . '",
                            confirmButtonColor: "gray",
                            background: "#0e0d0d",
                            color: "white",
                            iconColor: "gray"
                            confirmButtonText: "OK"
                        });
                    });
                </script>';
            }
    
            $stmt->close();
        }
    
        if (isset($_SESSION['login_success'])) {
            echo "
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: '" . htmlspecialchars($_SESSION['login_success']) . "',
                        confirmButtonText: 'OK',
                        confirmButtonColor: 'gray',
                        background: '#0e0d0d',
                        color: 'white',
                        iconColor: 'gray'
                    }).then(() => {
                        // Remove the query parameters from the URL
                        window.history.replaceState({}, document.title, window.location.pathname);
                    });
                });
            </script>
            ";
            
            // Unset the success session variable to prevent it from showing again
            unset($_SESSION['login_success']);
        }
    
        // Function to encrypt data
        function encrypt($data) {
            $key = hash('sha256', SECRET_KEY); // Hash the key to get a 256-bit key
            $iv = substr(hash('sha256', SECRET_IV), 0, 16); // Use first 16 bytes of hash for IV
            $encrypted = openssl_encrypt($data, ENCRYPTION_METHOD, $key, 0, $iv);
            return base64_encode($encrypted); // Encode to base64 for storage
        }
    
        // Function to decrypt data
        function decrypt($data) {
            $key = hash('sha256', SECRET_KEY); // Hash the key to get a 256-bit key
            $iv = substr(hash('sha256', SECRET_IV), 0, 16); // Use first 16 bytes of hash for IV
            $data = base64_decode($data);  // Decode from base64
            $decrypted = openssl_decrypt($data, ENCRYPTION_METHOD, $key, 0, $iv);
            return $decrypted;
        }
    
    ?>

</body>
</html>
