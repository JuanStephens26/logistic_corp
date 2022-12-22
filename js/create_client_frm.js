(function(){

});

function guardar_usuario() {
    document.form_client.indicador.value = "I";
    document.form_client.action = "../controller/client.php";
    document.form_client.submit();
}

function eliminar_usuario($user) {
    let a = confirm('¿Seguro desea eliminar este registro?');
    if(a){
    document.form_client.indicador.value = "D";
    document.form_client.action = "../controller/client.php";
    document.form_client.submit();
    }
}