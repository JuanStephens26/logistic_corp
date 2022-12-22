(function(){

});

function guardar_usuario() {
    document.form_user.indicador.value = "I";
    document.form_user.action = "../controller/user.php";
    document.form_user.submit();
}

function eliminar_usuario($user) {
    let a = confirm('¿Seguro desea eliminar este registro?');
    if(a){
    document.form_user.indicador.value = "D";
    document.form_user.action = "../controller/user.php";
    document.form_user.submit();
    }
}