<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Log request data
    file_put_contents('bmi_log.txt', print_r($_POST, true));

    // Retrieve and validate form data
    $height = isset($_POST['height']) ? (float) $_POST['height'] : 0;
    $weight = isset($_POST['weight']) ? (float) $_POST['weight'] : 0;
    $age = isset($_POST['age']) ? (int) $_POST['age'] : 0;
    $gender = isset($_POST['gender']) ? trim($_POST['gender']) : '';

    if ($height <= 0 || $weight <= 0 || $age <= 0 || empty($gender)) {
        echo json_encode(['error' => 'Invalid input values. Please enter valid height, weight, age, and select gender.']);
        exit;
    }

    // Calculate BMI
    $height_in_meters = $height / 100;
    $bmi = $weight / ($height_in_meters * $height_in_meters);
    $bmi = round($bmi, 2);

    // Determine weight category and recommendations
    if ($bmi < 18.5) {
        $recommendations = "You are underweight. Consider eating a balanced diet with more calories.";
    } elseif ($bmi >= 18.5 && $bmi < 24.9) {
        $recommendations = "Your weight is normal. Keep up the good work!";
    } elseif ($bmi >= 25 && $bmi < 29.9) {
        $recommendations = "You are overweight. Consider a healthy diet and regular exercise.";
    } else {
        $recommendations = "You are in the obese category. It's important to seek advice from a healthcare provider.";
    }

    // Customize recommendations based on gender
    if ($gender === 'male') {
        $recommendations .= " Men generally have more muscle mass, so focus on maintaining strength and fitness.";
    } elseif ($gender === 'female') {
        $recommendations .= " Women should focus on balancing nutrients and maintaining a healthy lifestyle.";
    }

    // Customize recommendations based on age
    if ($age < 18) {
        $recommendations .= " As a younger person, ensure you're getting adequate nutrition for growth.";
    } elseif ($age >= 18 && $age <= 40) {
        $recommendations .= " Maintain a balanced diet and regular physical activity.";
    } elseif ($age > 40 && $age <= 60) {
        $recommendations .= " Pay extra attention to your metabolic health and focus on heart health.";
    } else {
        $recommendations .= " At your age, it's important to stay active and maintain muscle mass.";
    }

    // Return JSON response
    echo json_encode([
        'bmi' => $bmi,
        'recommendations' => $recommendations
    ]);
} else {
    echo json_encode(['error' => 'Invalid request method. Use POST.']);
}
?>
