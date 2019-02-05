$("#add").click(function (e) 
{
    $("#items").append('<div><br><div id="items" class="form-group row"><div class="form-row"><div class="col-md-6"><input type="text" name="ip_port[]" class="form-control form-control-sm" placeholder="Addres"></div><div class="col-md-6"><input type="text" name="descripcion_ip_port[]" class="form-control form-control-sm" placeholder="Description"></div></div></div><button class="delete btn btn-danger btn-sm">Delete</button></div>'); 
});
$("body").on("click", ".delete", function (e) {
    $(this).parent("div").remove();
});

function mostrar(id) 
{
    if (id == "host") 
    {
        $("#host").show();
        $("#network").hide();
        $("#port").hide();
        $("#url").hide();
        $("#url_ports").hide();
        $("#urltable").hide();
        $("#urltable_ports").hide();
        $("#items").show();
    }
    if (id == "network") 
    {
        $("#host").hide();
        $("#network").show();
        $("#port").hide();
        $("#url").hide();
        $("#url_ports").hide();
        $("#urltable").hide();
        $("#urltable_ports").hide();
        $("#items").show();
    }
    if (id == "port") 
    {
        $("#host").hide();
        $("#network").hide();
        $("#port").show();
        $("#url").hide();
        $("#url_ports").hide();
        $("#urltable").hide();
        $("#urltable_ports").hide();
        $("#items").show();
    }
    if (id == "url") 
    {
        $("#host").hide();
        $("#network").hide();
        $("#port").hide();
        $("#url").show();
        $("#url_ports").hide();
        $("#urltable").hide();
        $("#urltable_ports").hide();
        $("#items").show();
    }
    if (id == "url_ports") 
    {
        $("#host").hide();
        $("#network").hide();
        $("#port").hide();
        $("#url").hide();
        $("#url_ports").show();
        $("#urltable").hide();
        $("#urltable_ports").hide();
        $("#items").show();
    }
    if (id == "urltable") 
    {
        $("#host").hide();
        $("#network").hide();
        $("#port").hide();
        $("#url").hide();
        $("#url_ports").hide();
        $("#urltable").show();
        $("#urltable_ports").hide();
        $("#items").show();
    }
    if (id == "urltable_ports") 
    {
        $("#host").hide();
        $("#network").hide();
        $("#port").hide();
        $("#url").hide();
        $("#url_ports").hide();
        $("#urltable").hide();
        $("#urltable_ports").show();
        $("#items").show();
    }
}

if ($("#principalbundle_aliases_tipo option:selected").val() == "host") 
{
    $("#host").show();
    $("#network").hide();
    $("#port").hide();
    $("#url").hide();
    $("#url_ports").hide();
    $("#urltable").hide();
    $("#urltable_ports").hide();
    $("#items").show();
}
if ($("#principalbundle_aliases_tipo option:selected").val() == "network") 
{
    $("#host").hide();
    $("#network").show();
    $("#port").hide();
    $("#url").hide();
    $("#url_ports").hide();
    $("#urltable").hide();
    $("#urltable_ports").hide();
    $("#items").show();
}
if ($("#principalbundle_aliases_tipo option:selected").val() == "port") 
{
    $("#host").hide();
    $("#network").hide();
    $("#port").show();
    $("#url").hide();
    $("#url_ports").hide();
    $("#urltable").hide();
    $("#urltable_ports").hide();
    $("#items").show();
}
if ($("#principalbundle_aliases_tipo option:selected").val() == "url") 
{
    $("#host").hide();
    $("#network").hide();
    $("#port").hide();
    $("#url").show();
    $("#url_ports").hide();
    $("#urltable").hide();
    $("#urltable_ports").hide();
    $("#items").show();
}
if ($("#principalbundle_aliases_tipo option:selected").val() == "url_ports") 
{
    $("#host").hide();
    $("#network").hide();
    $("#port").hide();
    $("#url").hide();
    $("#url_ports").show();
    $("#urltable").hide();
    $("#urltable_ports").hide();
    $("#items").show();
}
if ($("#principalbundle_aliases_tipo option:selected").val() == "urltable") 
{
    $("#host").hide();
    $("#network").hide();
    $("#port").hide();
    $("#url").hide();
    $("#url_ports").hide();
    $("#urltable").show();
    $("#urltable_ports").hide();
    $("#items").show();
}
if ($("#principalbundle_aliases_tipo option:selected").val() == "urltable_ports") 
{
    $("#host").hide();
    $("#network").hide();
    $("#port").hide();
    $("#url").hide();
    $("#url_ports").hide();
    $("#urltable").hide();
    $("#urltable_ports").show();
    $("#items").show();
}