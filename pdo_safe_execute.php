<?php
function pdo_safe_execute($queryBuilder){
	$pdo = $ctrl->getPDO();
	$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // 静的プレースホルダを指定
	$sth = $pdo->prepare($queryBuilder->getSQL());
	$sth->execute($queryBuilder->getData());
	return $sth->fetchAll(PDO::FETCH_OBJ);
}

?>