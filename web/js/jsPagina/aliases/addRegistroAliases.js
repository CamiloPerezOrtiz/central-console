//when the Add Field button is clicked
$("#add").click(function (e) {
//Append a new row of code to the "#items" div
$("#items").append('<div><br><div id="items" class="form-group"><div class="form-row"><div class="col-md-6"><input type="text" name="input[]" class="form-control form-control-sm" placeholder="Host"></div><div class="col-md-6"><input type="text" name="input2[]" class="form-control form-control-sm" placeholder="Description"></div></div></div><button class="delete btn btn-danger btn-sm">Delete</button></div>'); });
$("body").on("click", ".delete", function (e) {
    $(this).parent("div").remove();
});
//item tiene 3 input item2 tiene 2 input
function mostrar(id) {
    if (id == "host") {
        $("#host").show();
        $("#network").hide();
        $("#port").hide();
        $("#url").hide();
        $("#url_ports").hide();
        $("#urltable").hide();
        $("#urltable_ports").hide();
        $("#items").show();
    }
    if (id == "network") {
        $("#host").hide();
        $("#network").show();
        $("#port").hide();
        $("#url").hide();
        $("#url_ports").hide();
        $("#urltable").hide();
        $("#urltable_ports").hide();
        $("#items").show();
    }
    if (id == "port") {
        $("#host").hide();
        $("#network").hide();
        $("#port").show();
        $("#url").hide();
        $("#url_ports").hide();
        $("#urltable").hide();
        $("#urltable_ports").hide();
        $("#items").show();
    }
    if (id == "url") {
        $("#host").hide();
        $("#network").hide();
        $("#port").hide();
        $("#url").show();
        $("#url_ports").hide();
        $("#urltable").hide();
        $("#urltable_ports").hide();
        $("#items").show();
    }
    if (id == "url_ports") {
        $("#host").hide();
        $("#network").hide();
        $("#port").hide();
        $("#url").hide();
        $("#url_ports").show();
        $("#urltable").hide();
        $("#urltable_ports").hide();
        $("#items").show();
    }
    if (id == "urltable") {
        $("#host").hide();
        $("#network").hide();
        $("#port").hide();
        $("#url").hide();
        $("#url_ports").hide();
        $("#urltable").show();
        $("#urltable_ports").hide();
        $("#items").show();
    }
    if (id == "urltable_ports") {
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
$( function() {
    $("#status").change( function() {
        if ($(this).val() === "1") {
            $("#id_input").prop("disabled", true);
        } else {
            $("#id_input").prop("disabled", false);
        }
    });
});