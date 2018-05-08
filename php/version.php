<?php 
echo "Ver. 3.0.21 Compilado 2018.04.30";

/*
Nuevos cambios en versiones:

Version 3.0
ALTER TABLE `producto` ADD `idTipoProducto` INT NOT NULL DEFAULT '0' AFTER `prodDioAdelanto`;

CREATE TABLE `prestamo_producto` (
  `idPrestamo` int(11) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `presidTipoProceso` int(11) NOT NULL,
  `desFechaContarInteres` datetime NOT NULL,
  `preInteres` int(11) NOT NULL,
  `preCapital` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `prestamo_producto`(`idPrestamo`, `idProducto`, `presidTipoProceso`, `desFechaContarInteres`, `preInteres`, `preCapital`)
select pr.idPrestamo, pr.idProducto, pr.preIdEstado, d.desFechaContarInteres, pr.preInteres, d.desCapital
from prestamo pr inner join desembolso d on d.idPrestamo = pr.idPrestamo
where 1

Ahora jugamos más con presidTipoProceso de prestamo_producto;







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