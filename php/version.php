<?php 
echo "Ver. 3.1.4 Compilado 2018.10.17";

/*
Nuevos cambios en versiones:

Version 3.0
DROP TABLE `prestamo_producto`;
CREATE TABLE `prestamo_producto` (
  `idPrestamo` int(11) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `presidTipoProceso` int(11) NOT NULL,
  `desFechaContarInteres` datetime NOT NULL,
  `preInteres` int(11) NOT NULL,
  `preCapital` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
ALTER TABLE `prestamo_producto` ADD `prActivo` INT NOT NULL COMMENT '0 anulado, 1 activo' AFTER `preCapital`;
ALTER TABLE `prestamo_producto` ADD `idCubicajeEstado` INT NOT NULL COMMENT 'En tipoProceso' AFTER `prActivo`;
ALTER TABLE `prestamo_producto` CHANGE `idCubicajeEstado` `idCubicajeEstado` INT(11) NOT NULL DEFAULT '22' COMMENT 'En tipoProceso';

INSERT INTO `prestamo_producto`(`idPrestamo`, `idProducto`, `presidTipoProceso`, `desFechaContarInteres`, `preInteres`, `preCapital`, `prActivo`)
select pr.idPrestamo, pr.idProducto, pr.preIdEstado, d.desFechaContarInteres, pr.preInteres, d.desCapital, p.prodActivo
from prestamo pr inner join desembolso d on d.idPrestamo = pr.idPrestamo
inner join producto p on pr.idProducto = p.idProducto
where 1;

Ahora jugamos más con presidTipoProceso de prestamo_producto;

UPDATE `prestamo_producto` SET `presidTipoProceso`=32 WHERE `presidTipoProceso`=11;
UPDATE `prestamo_producto` pp inner join producto p on pp.idProducto = p.idProducto SET pp.`presidTipoProceso`= 22 WHERE pp.`presidTipoProceso`=1 and p.esCompra=0;




Version 2.10
* InteresDiario= 4%/7 = 0.04/7
* Día 1 al 7: 4% interés mínimo por defecto
* Día 7 al 28: El cálculo del interés se hace hasta el día exacto= montoInicial * Suma Días * InteresDiario
* Día 29 al inf: Interés compuesto de: montoInicial * 28 * 16%
* Día 32 al inf: agregar S/. 10 por gastos adminstrativos.


Version 2.11
* Corregido conteo días en los intereses
* Agrego nuevo boton de imprimir ticket de articulo retirado
*/
 ?>