$(document).ready(function () {
    $('.sec').click(function () {
        var actif_sec = document.getElementsByClassName("actif_sec");
        $(actif_sec).removeClass("actif_sec");
        $(this).addClass("actif_sec");
        var id = $(".actif_sec").attr("alt");
        if ($(".actif_sec").hasClass("pascheck_sec")) {
            if ($("#carte_sec_1").val() == "") {
                $("#carte_sec_1").val(id);
                $(".actif_sec").removeClass("pascheck_sec").addClass("check_sec1");
                $(".check_sec1").css('margin-left' , '0');
            }
        } else if ($(".actif_sec").hasClass("check_sec1")) {
            $("#carte_sec_1").val("");
            $(".actif_sec").removeClass("check_sec1").addClass("pascheck_sec");
            $(".actif_sec").attr('style', '');
        }
    });

    $('.diss').click(function () {
        var actif_diss = document.getElementsByClassName("actif_diss");
        $(actif_diss).removeClass("actif_diss");
        $(this).addClass("actif_diss");
        var id = $(".actif_diss").attr("alt");
        if ($(".actif_diss").hasClass("pascheck_diss")) {
            if ($("#carte_diss_1").val() == "") {
                $("#carte_diss_1").val(id);
                $(".actif_diss").removeClass("pascheck_diss").addClass("check_diss1");
                $(".check_diss1").css('margin-left' , '0');
            } else if ($("#carte_diss_2").val() == "") {
                $("#carte_diss_2").val(id);
                $(".actif_diss").removeClass("pascheck_diss").addClass("check_diss2");
                $(".check_diss2").css('margin-left' , '0');
            }
        } else if ($(".actif_diss").hasClass("check_diss1")) {
            $("#carte_diss_1").val("");
            $(".actif_diss").removeClass("check_diss1").addClass("pascheck_diss");
            $(".actif_diss").attr('style', '');
        } else if ($(".actif_diss").hasClass("check_diss2")) {
            $("#carte_diss_2").val("");
            $(".actif_diss").removeClass("check_diss2").addClass("pascheck_diss");
            $(".actif_diss").attr('style', '');
        }
    });

    $('.cadeau').click(function () {
        var actif_cad = document.getElementsByClassName("actif_cad");
        $(actif_cad).removeClass("actif_cad");
        $(this).addClass("actif_cad");
        var id = $(".actif_cad").attr("alt");
        if ($(".actif_cad").hasClass("pascheck_cadeau")) {
            if ($("#carte_cadeau_1").val() == "") {
                $("#carte_cadeau_1").val(id);
                $(".actif_cad").removeClass("pascheck_cadeau").addClass("check_cadeau1");
                $(".check_cadeau1").css('margin-left' , '0');
            } else if ($("#carte_cadeau_2").val() == "") {
                $("#carte_cadeau_2").val(id);
                $(".actif_cad").removeClass("pascheck_cadeau").addClass("check_cadeau2");
                $(".check_cadeau2").css('margin-left' , '0');
            } else if ($("#carte_cadeau_3").val() == "") {
                $("#carte_cadeau_3").val(id);
                $(".actif_cad").removeClass("pascheck_cadeau").addClass("check_cadeau3");
                $(".check_cadeau3").css('margin-left' , '0');
            }
        } else if ($(".actif_cad").hasClass("check_cadeau1")) {
            $("#carte_cadeau_1").val("");
            $(".actif_cad").removeClass("check_cadeau1").addClass("pascheck_cadeau");
            $(".actif_cad").attr('style', '');
        } else if ($(".actif_cad").hasClass("check_cadeau2")) {
            $("#carte_cadeau_2").val("");
            $(".actif_cad").removeClass("check_cadeau2").addClass("pascheck_cadeau");
            $(".actif_cad").attr('style', '');
        } else if ($(".actif_cad").hasClass("check_cadeau3")) {
            $("#carte_cadeau_3").val("");
            $(".actif_cad").removeClass("check_cadeau3").addClass("pascheck_cadeau");
            $(".actif_cad").attr('style', '');
        }
    });

    $('.conc').click(function () {
        var actif_conf = document.getElementsByClassName("actif_conf");
        $(actif_conf).removeClass("actif_conf");
        $(this).addClass("actif_conf");
        var id = $(".actif_conf").attr("alt");
        if ($(".actif_conf").hasClass("pascheck_conc")) {
            if ($("#carte_conc_1").val() == "") {
                $("#carte_conc_1").val(id);
                $(".actif_conf").removeClass("pascheck_conc").addClass("check_conc1");
                $(".check_conc1").css('margin-left' , '0');
            } else if ($("#carte_conc_2").val() == "") {
                $("#carte_conc_2").val(id);
                $(".actif_conf").removeClass("pascheck_conc").addClass("check_conc2");
                $(".check_conc2").css('margin-left' , '0');
            } else if ($("#carte_conc_3").val() == "") {
                $("#carte_conc_3").val(id);
                $(".actif_conf").removeClass("pascheck_conc").addClass("check_conc3");
                $(".check_conc3").css('margin-left' , '0');
            } else if ($("#carte_conc_4").val() == "") {
                $("#carte_conc_4").val(id);
                $(".actif_conf").removeClass("pascheck_conc").addClass("check_conc4");
                $(".check_conc4").css('margin-left' , '0');
            }
        } else if ($(".actif_conf").hasClass("check_conc1")) {
            if ($("#carte_conc_1").val() == id) {
                $("#carte_conc_1").val("");
                $(".actif_conf").removeClass("check_conc1").addClass("pascheck_conc");
                $(".actif_conf").attr('style', '');
            } else if ($("#carte_conc_2").val() == id) {
                $("#carte_conc_2").val("");
                $(".actif_conf").removeClass("check_conc1").addClass("pascheck_conc");
                $(".actif_conf").attr('style', '');
            }
        } else if ($(".actif_conf").hasClass("check_conc2")) {
            if ($("#carte_conc_1").val() == id) {
                $("#carte_conc_1").val("");
                $(".actif_conf").removeClass("check_conc2").addClass("pascheck_conc");
                $(".actif_conf").attr('style', '');
            } else if ($("#carte_conc_2").val() == id) {
                $("#carte_conc_2").val("");
                $(".actif_conf").removeClass("check_conc2").addClass("pascheck_conc");
                $(".actif_conf").attr('style', '');
            }
        } else if ($(".actif_conf").hasClass("check_conc3")) {
            if ($("#carte_conc_3").val() == id) {
                $("#carte_conc_3").val("");
                $(".actif_conf").removeClass("check_conc3").addClass("pascheck_conc");
                $(".actif_conf").attr('style', '');
            } else if ($("#carte_conc_4").val() == id) {
                $("#carte_conc_4").val("");
                $(".actif").removeClass("check_conc3").addClass("pascheck_conc");
                $(".actif").attr('style', '');
            }
        } else if ($(".actif_conf").hasClass("check_conc4")) {
            if ($("#carte_conc_3").val() == id) {
                $("#carte_conc_3").val("");
                $(".actif_conf").removeClass("check_conc4").addClass("pascheck_conc");
                $(".actif_conf").attr('style', '');
            } else if ($("#carte_conc_4").val() == id) {
                $("#carte_conc_4").val("");
                $(".actif_conf").removeClass("check_conc4").addClass("pascheck_conc");
                $(".actif_conf").attr('style', '');
            }
        }
    });
});