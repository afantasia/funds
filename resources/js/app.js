require('./bootstrap');

import $ from 'jquery';

window.$ = window.jQuery = $;

function getAttributes($node, regexp) {
    var rtn = {};
    regexp = typeof regexp != 'undefined' ? regexp : /^param\-/;
    $.each($node[0].attributes, function (index, attribute) {
        if (regexp.test(attribute.name) == true) {
            rtn[attribute.name.replace(regexp, '').trim()] = attribute.value;
        }
    });
    return rtn;
}

var funcLists = {
    'addrow': (params) => {
        console.log(params);
        $("#dataTable tbody ").append('<tr><td>dasdasdasasd</td><td><div type="button" bind="b"  param-key3="das" parm>ddd</div></td></tr>');
    },
    'b': (params) => {
        console.log(params);
    },
}
$(document).ready(function () {
    $(document).on("click", '[bind]', function () {
        var funcionName = $(this).attr("bind");
        if (typeof funcLists[funcionName] != 'undefined') {
            var attrParms = getAttributes($(this));
            console.log(attrParms);
            funcLists[funcionName](attrParms)
        }
        console.log($(this).attr("bind"));
    });
});

