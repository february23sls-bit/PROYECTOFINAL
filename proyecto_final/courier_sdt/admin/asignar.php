<?php include '../config.php';
if(!es_admin()){ header("Location: /admin/login.php"); exit; }
$id_pedido=(int)($_POST['id_pedido']??0); $id_motorizado=(int)($_POST['id_motorizado']??0);
$pdo->beginTransaction();
try{
  $pdo->prepare("INSERT INTO asignaciones (id_pedido,id_motorizado) VALUES (?,?)")->execute([$id_pedido,$id_motorizado]);
  $pdo->prepare("UPDATE pedidos SET estado='asignado' WHERE id_pedido=?")->execute([$id_pedido]);
  $pdo->commit();
}catch(Exception $e){ $pdo->rollBack(); }
header("Location: /admin/index.php"); ?>
