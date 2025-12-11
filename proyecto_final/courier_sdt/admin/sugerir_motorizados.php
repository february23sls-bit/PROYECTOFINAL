<?php include '../config.php';
header('Content-Type: application/json; charset=utf-8');
$d = strtolower($_GET['distrito']??'');
$st=$pdo->prepare("SELECT m.* FROM motorizados m
 JOIN motorizados_rutas mr ON mr.id_motorizado=m.id_motorizado
 JOIN rutas r ON r.id_ruta=mr.id_ruta
 WHERE m.activo=1 AND LOCATE(CONCAT(',',LOWER(?),','), CONCAT(',',LOWER(r.distritos),','))>0");
$st->execute([$d]); $rows=$st->fetchAll(PDO::FETCH_ASSOC);
echo json_encode(['motorizados'=>$rows], JSON_UNESCAPED_UNICODE);
