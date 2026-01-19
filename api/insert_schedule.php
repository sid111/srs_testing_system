<?php
$conn = new mysqli("localhost","db_user","db_pass","db_name");
if($conn->connect_error){ die(json_encode(['success'=>false,'message'=>$conn->connect_error])); }

$report_type = $_POST['report_type'] ?? '';
$start_date = $_POST['start_date'] ?? '';
$frequency = $_POST['frequency'] ?? 'daily';
$time = $_POST['time'] ?? '09:00';
$email_recipients = $_POST['email_recipients'] ?? '';
$name = ucfirst($report_type).' Schedule';

$next_run = date('Y-m-d H:i:s', strtotime("$start_date $time"));
$last_run = date('Y-m-d H:i:s', strtotime("$start_date $time -1 $frequency"));

$stmt = $conn->prepare("INSERT INTO scheduled_reports (name,report_type,frequency,start_date,time,email_recipients,next_run,last_run,status) VALUES (?,?,?,?,?,?,?,?,?)");
$status='active';
$stmt->bind_param("sssssssss",$name,$report_type,$frequency,$start_date,$time,$email_recipients,$next_run,$last_run,$status);
if($stmt->execute()){
    echo json_encode(['success'=>true]);
}else{
    echo json_encode(['success'=>false,'message'=>$stmt->error]);
}
