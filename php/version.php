<?php 
echo "Ver. 3.0.7 Build 2018.03.06";

/*
Nuevos cambios en versiones:

Version 3.0
UPDATE `prestamo` p inner join desembolso d
on d.idPrestamo=p.idPrestamo
SET p.`preFechaContarInteres`=d.desFechaContarInteres
WHERE 1
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