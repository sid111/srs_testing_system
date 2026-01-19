<?php
$conn = new mysqli("localhost","db_user","db_pass","db_name");
if($conn->connect_error){ die(json_encode(['success'=>false,'message'=>$conn->connect_error])); }

$report_type = $_POST['report_type'] ?? '';
$start_date = $_POST['start_date'] ?? '';
$end_date = $_POST['end_date'] ?? '';
$product_type = $_POST['product_type'] ?? '';
$test_status = $_POST['test_status'] ?? '';
$format = $_POST['format'] ?? '';

$name = ucfirst($report_type).' Report';

$stmt = $conn->prepare("INSERT INTO reports (name,type,start_date,end_date,product_type,test_status,format,status,size) VALUES (?,?,?,?,?,?,?,?,?)");
$status='processing';
$size='Processing...';
$stmt->bind_param("sssssssss",$name,$report_type,$start_date,$end_date,$product_type,$test_status,$format,$status,$size);
if($stmt->execute()){
    echo json_encode(['success'=>true]);
}else{
    echo json_encode(['success'=>false,'message'=>$stmt->error]);
}
