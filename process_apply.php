<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. SANITIZATION (Requirement for security)
    $job_id = htmlspecialchars($_POST['JobID']);
    $fname = htmlspecialchars($_POST['Fname']);
    $lname = htmlspecialchars($_POST['Lname']);
    $email = filter_var($_POST['Mail'], FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars($_POST['PN']);
    $addr = htmlspecialchars($_POST['addr']);
    $town = htmlspecialchars($_POST['town']);
    $code = htmlspecialchars($_POST['code']);
    $state = htmlspecialchars($_POST['tutor']);
    $dob = $_POST['date'];
    $gender = $_POST['unit'];
    
    // Handle checkboxes (skills)
    $skills = isset($_POST['topic']) ? implode(', ', $_POST['topic']) : '';
    $comments = htmlspecialchars($_POST['comments']);
    $other_skills = htmlspecialchars($_POST['other_skills']);

    // 2. PREPARED STATEMENTS (The 'Outstanding' requirement to prevent SQL Injection)
    $sql = "INSERT INTO applications (job_id_ref, fname, lname, email, phone, address, town, postcode, state, dob, gender, skills, comments, other_skills) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $job_id, 
            $fname, 
            $lname, 
            $email, 
            $phone, 
            $addr, 
            $town, 
            $code, 
            $state, 
            $dob, 
            $gender, 
            $skills, 
            $comments, 
            $other_skills
        ]);

        echo "<h1>Application Submitted!</h1>";
        echo "<p>Thank you for applying. Your data has been securely saved to our database.</p>";
        echo "<a href='index.php'>Return to Home</a>";
        
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Redirect if someone tries to access this file directly without a POST
    header("Location: index.php");
    exit;
}
?>