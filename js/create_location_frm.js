(function(){

});

function guardar_ubicacion() {
    document.form_location.indicador.value = "I";
    document.form_location.action = "../controller/location.php";
    document.form_location.submit();
}

function eliminar_ubicacion() {
    let a = confirm('¿Seguro desea eliminar este registro?');
    if(a){
    document.form_location.indicador.value = "D";
    document.form_location.action = "../controller/location.php";
    document.form_location.submit();
    }
}