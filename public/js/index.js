window.onload = function clickBtn1() {
    var startFlg = JSON.parse('<?php echo $startFlg_json; ?>');
    var endFlg = JSON.parse('<?php echo $endFlg_json; ?>');
    var startRestFlg = JSON.parse('<?php echo $startRestFlg_json; ?>');
    var endRestFlg = JSON.parse('<?php echo $endRestFlg_json; ?>');

    console.log('値の引き渡し確認_startRestFlg:')
    console.log(startRestFlg);

    if (startFlg == true) {
        document.getElementById("btn_punchin").removeAttribute("disabled");
        document.getElementById("btn_punchin").style.color = "black";
    } else {
        document.getElementById("btn_punchin").setAttribute("disabled", true);
        document.getElementById("btn_punchin").style.color = "white";
    }

    if (endFlg == true) {
        document.getElementById("btn_punchout").removeAttribute("disabled");
        document.getElementById("btn_punchout").style.color = "black";
    } else {
        document.getElementById("btn_punchout").setAttribute("disabled", true);
        document.getElementById("btn_punchout").style.color = "white";
    }

    if (startRestFlg == true) {
        document.getElementById("btn_rest_punchin").removeAttribute("disabled");
        document.getElementById("btn_rest_punchin").style.color = "black";
    } else {
        document.getElementById("btn_rest_punchin").setAttribute("disabled", true);
        document.getElementById("btn_rest_punchin").style.color = "white";
    }

    if (endRestFlg == true) {
        document.getElementById("btn_rest_punchout").removeAttribute("disabled");
        document.getElementById("btn_rest_punchout").style.color = "black";
    } else {
        document.getElementById("btn_rest_punchout").setAttribute("disabled", true);
        document.getElementById("btn_rest_punchout").style.color = "white";
    }
}