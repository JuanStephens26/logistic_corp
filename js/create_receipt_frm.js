(function(){

});

function guardar_receipt() {
    document.form_receipt.indicador.value = "I";
    document.form_receipt.action = "../controller/receipt.php";
    document.form_receipt.submit();
}

function guardar_receipt_lin() {
    document.form_receipt_lin.indicador.value = "LI";
    document.form_receipt_lin.action = "../controller/receipt.php";
    document.form_receipt_lin.submit();
}

function located_receipt_lin() {
    if (document.form_receipt_lin.qty_located.value > document.form_receipt_lin.qty.value){
        alert('¡No puede introducir un valor mayor a la cantidad original!');
        return false;
    }
    document.form_receipt_lin.indicador.value = "LO";
    document.form_receipt_lin.action = "../controller/receipt.php";
    document.form_receipt_lin.submit();
}

function asignar_receipt_lin(){
    document.form_receipt_lin.indicador.value = "AI";
    document.form_receipt_lin.action = "../controller/receipt.php";
    document.form_receipt_lin.submit();
}

function eliminar_receipt($user) {
    let a = confirm('¿Seguro desea eliminar este registro?');
    if(a){
    document.form_receipt.indicador.value = "D";
    document.form_receipt.action = "../controller/receipt.php";
    document.form_receipt.submit();
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

function abrir_modificacion_linea(id, no_receipt){
    document.form_hidden.action = "./create_receipt_lin_frm.php";
    document.form_hidden.no_receipt.value = no_receipt;
    document.form_hidden.no_line.value = id;
    document.form_hidden.indicador.value = "CU";
    document.form_hidden.submit();
}