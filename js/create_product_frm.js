(function(){

});

function guardar_producto() {
    document.form_product.indicador.value = "I";
    document.form_product.action = "../controller/product.php";
    document.form_product.submit();
}

function eliminar_producto($user) {
    let a = confirm('¿Seguro desea eliminar este registro?');
    if(a){
    document.form_product.indicador.value = "D";
    document.form_product.action = "../controller/product.php";
    document.form_product.submit();
    }
}