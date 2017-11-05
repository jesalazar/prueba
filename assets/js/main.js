$(document).ready(function() {

    // Configuracion de tablas
    $('.data-tables').DataTable({
        "language": {
            "responsive": true,
            "lengthMenu": "Mostrar _MENU_ registros por página",
            "zeroRecords": "No hay registros",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "No hay registros disponibles",
            "infoFiltered": "(filtrado de _MAX_ cursos registrados)",
            "search": "Buscar",
            "paginate": {
                "first":      "Primero",
                "last":       "Ultimo",
                "next":       "Siguiente",
                "previous":   "Anterior"
            },
        },
    });

    // Envio de formulario login
    $("#formLogin").submit(function(event){
        event.preventDefault();
        if ($(this).valid()) {
            $.ajax({
                url  : $(this).attr("action"),
                type : $(this).attr("method"),
                data : $(this).serialize(),
                success : function(res){
                    afterAjax(res, "Correo o contraseña incorrecta.");
                }
            });
        }
    });

    // Envio de formulario de registro
    $("#formRegistro").submit(function(event){
        event.preventDefault();
        if ($(this).valid()) {
            $.ajax({
                url  : $(this).attr("action"),
                type : $(this).attr("method"),
                data : $(this).serialize(),
                success : function(res){
console.log(res)
                    if(res==true) {
                        window.location.href = '/prueba';
                    } else {
                        $("#back-error").text(res).show();
                    }
                }
            });
        }
    });

    // Envio de formulario de fotos
    $("#formFotos").submit(function(event){
        event.preventDefault();
        var data = new FormData();
        data.append('foto', $('#foto').prop('files')[0]);
        data.append('descripcion', $('#descripcion').val());
        if ($(this).valid()) {
            $.ajax({
                url         : $(this).attr("action"),
                type        : $(this).attr("method"),
                data        : data,
                cache       : false,
                contentType : false,
                processData : false,
                success : function(res){
                    afterAjax(res, "Llegaste al limite de subida de fotos.");
                }
            });
        }
    });

    // Accion Me gusta
    $("table .like").click(function(event) {
        event.preventDefault();
        var self = $(this);
        $.ajax({
            url  : $(this).attr("data-url"),
            type : 'POST',
            data : {
                'me_gusta' : $(this).attr("data-like"),
                'foto_id'  : $(this).attr("data-foto"),
                'action'   : $(this).attr("data-insert")
            }, success : function(res) {
                if (res) {
                    self.removeAttr("data-insert");
                    self.addClass("selected");
                    self.siblings('a').removeAttr("data-insert");
                    self.siblings('a').removeClass("selected");
                }
            }
        });
    });

    // Funcion a ejecutar cuando el ajax finaliza
    function afterAjax(res, e) {
        if (res==true) {
            window.location.href = '/prueba';
        } else {
            toastr.error(e, "Error!");
        }
    }

});
