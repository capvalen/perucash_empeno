function listaBugs(data, comentario=''){
var motivo =''
   switch (data) {
      case "err1": motivo = "No hay ninguna caja abierta aún."; break;
      case "err2": motivo = "Faltan rellenar datos: " + comentario; break;
      case "err3": motivo = "Tu sesión expiró, vuelve a intentarlo luego de iniciar sessión."; break;
      default:
         break;
   }

   $('.modal-GuardadoError').find('#spanMalo').text('El servidor dice: ' + motivo);
   $('.modal-GuardadoError').modal('show');
}