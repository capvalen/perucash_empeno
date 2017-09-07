$.fn.modal.prototype.constructor.Constructor.DEFAULTS.backdrop = 'static'; //Para que no cierre el modal, cuando hacen clic en cualquier parte

$("input").focus(function(){this.select();}); //para seleccionar todo en un input


/*Para que la página cargue en el tab que se requiere*/
// Javascript to enable link to tab
var url = document.location.toString();
if (url.match('#')) {
    $('.nav-tabs a[href="#' + url.split('#')[1]).tab('show').click();
    console.log(url.split('#')[1]);
} //add a suffix
$('.nav-tabs a').on('shown.bs.tab', function (e) {
window.location.hash = e.target.hash;
});
