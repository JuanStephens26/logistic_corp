(function(){

});

function guardar_receipt() {
    document.form_order.indicador.value = "I";
    document.form_order.action = "../controller/order.php";
    document.form_order.submit();
}

function guardar_order_lin() {
    document.form_order_lin.indicador.value = "LI";
    document.form_order_lin.action = "../controller/order.php";
    document.form_order_lin.submit();
}

function located_order_lin() {
    document.form_order_lin.indicador.value = "LO";
    document.form_order_lin.action = "../controller/order.php";
    document.form_order_lin.submit();
}

function asignar_order_lin(){
    document.form_order_lin.indicador.value = "AI";
    document.form_order_lin.action = "../controller/order.php";
    document.form_order_lin.submit();
}

function eliminar_receipt($user) {
    let a = confirm('¿Seguro desea eliminar este registro?');
    if(a){
    document.form_order.indicador.value = "D";
    document.form_order.action = "../controller/order.php";
    document.form_order.submit();
    }
}

function eliminar_receipt($user) {
    let a = confirm('¿Seguro desea eliminar este registro?');
    if(a){
    document.form_receipt_lin.indicador.value = "LD";
    document.form_receipt_lin.action = "../controller/receipt.php";
    document.form_receipt_lin.submit();
    }
}

function abrir_modificacion_linea(id, no_order){
    document.form_hidden.action = "./create_order_lin_frm.php";
    document.form_hidden.no_order.value = no_order;
    document.form_hidden.no_line.value = id;
    document.form_hidden.indicador.value = "CU";
    document.form_hidden.submit();
}