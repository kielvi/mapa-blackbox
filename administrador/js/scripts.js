$(document).ready(function() {
	// FECHA O MENU AO CLICAR EM UM ITEM
 	$(".menu ul li a").click(function(event) { 
        //isOpen = true;
        //alert()
        //$(document.body).removeClass('show-menu' ); 
        
    });


$(document).on('click', '[data-toggle="lightbox"]', function(event) {
    event.preventDefault();
    $(this).ekkoLightbox();
});


});


function mascara(o,f) {
	v_obj=o
	v_fun=f
	setTimeout("execmascara()",1)
}
function execmascara() { v_obj.value=v_fun(v_obj.value) }
function leech(v){
    v=v.replace(/o/gi,"0")
    v=v.replace(/i/gi,"1")
    v=v.replace(/z/gi,"2")
    v=v.replace(/e/gi,"3")
    v=v.replace(/a/gi,"4")
    v=v.replace(/s/gi,"5")
    v=v.replace(/t/gi,"7")
    return v
}
function soNumeros(v){
    return v.replace(/\D/g,"")
}
function itelefone(v){
    v=v.replace(/\D/g,"")                 //Remove tudo o que não é dígito
    v=v.replace(/^(\d\d)(\d)/g,"($1) $2") //Coloca parênteses em volta dos dois primeiros dígitos
    v=v.replace(/(\d{4})(\d)/,"$1-$2")    //Coloca hífen entre o quarto e o quinto dígitos
    return v
}
function icpf(v){
    v=v.replace(/\D/g,"")                    //Remove tudo o que não é dígito
    v=v.replace(/(\d{3})(\d)/,"$1.$2")       //Coloca um ponto entre o terceiro e o quarto dígitos
    v=v.replace(/(\d{3})(\d)/,"$1.$2")       //Coloca um ponto entre o terceiro e o quarto dígitos
                                             //de novo (para o segundo bloco de números)
    v=v.replace(/(\d{3})(\d{1,2})$/,"$1-$2") //Coloca um hífen entre o terceiro e o quarto dígitos
    return v
}
function icep(v){
    v=v.replace(/D/g,"")                //Remove tudo o que não é dígito
    v=v.replace(/^(\d{5})(\d)/,"$1-$2") //Esse é tão fácil que não merece explicações
    return v
}
function cnpj(v){
    v=v.replace(/\D/g,"")                           //Remove tudo o que não é dígito
    v=v.replace(/^(\d{2})(\d)/,"$1.$2")             //Coloca ponto entre o segundo e o terceiro dígitos
    v=v.replace(/^(\d{2})\.(\d{3})(\d)/,"$1.$2.$3") //Coloca ponto entre o quinto e o sexto dígitos
    v=v.replace(/\.(\d{3})(\d)/,".$1/$2")           //Coloca uma barra entre o oitavo e o nono dígitos
    v=v.replace(/(\d{4})(\d)/,"$1-$2")              //Coloca um hífen depois do bloco de quatro dígitos
    return v
}

function barra(objeto){
	if (objeto.value.length == 2 || objeto.value.length == 5 ){
	objeto.value = objeto.value+"/";
	}
}

function formatar_moeda(campo, separador_milhar, separador_decimal, tecla) {
    var sep = 0;
    var key = '';
    var i = j = 0;
    var len = len2 = 0;
    var strCheck = '0123456789';
    var aux = aux2 = '';
    var whichCode = (window.Event) ? tecla.which : tecla.keyCode;

    if(whichCode==13) return true; // Tecla Enter
    if(whichCode==8) return true; // Tecla Delete

    key = String.fromCharCode(whichCode); // Pegando o valor digitado
    if(strCheck.indexOf(key) == -1) return false; // Valor inválido (não inteiro)

    len = campo.value.length;
    for(i = 0; i < len; i++)
        if ((campo.value.charAt(i) != '0') && (campo.value.charAt(i) != separador_decimal)) break;
        aux = '';

        for(; i < len; i++)
            if (strCheck.indexOf(campo.value.charAt(i))!=-1) aux += campo.value.charAt(i);
            aux += key;
            len = aux.length;

            if(len==0) campo.value = '';
            if(len==1) campo.value = '0'+ separador_decimal + '0' + aux;
            if(len==2) campo.value = '0'+ separador_decimal + aux;

            if(len>2) {
                aux2 = '';
                for(j=0, i=len-3; i>=0; i--) {
                    if(j==3) {
                        aux2 += separador_milhar;
                        j=0;
                    }
                    aux2 += aux.charAt(i);
                j++; }

                campo.value = '';
                len2 = aux2.length;

                for(i=len2-1; i>=0; i--)
                    campo.value += aux2.charAt(i);
                    campo.value += separador_decimal + aux.substr(len - 2, len);
            }
    return false;
}
