/* 
 * By Koala jengke
 * ini JS buat grapik coy
 */
function GraphRender(container, url) {
    var data, filter;
    if(typeof url === 'undefined'){
        url = $('#' + container).attr('data-source');
    }
    filter = $('#' + container).attr('data-filter');
    var data = {};
    if (typeof filter !== 'undefined') {
        data = $(filter).serialize();
    }
    $('#' + container).html(loading);
    $.get(url, data, function(out) {
        $('#' + container).html('');
        if (typeof xhr_result === "function") {
            grapik(container, xhr_result());
        }
    }, 'script');
}

function grapik(container, data) {
    // $('#' + container).highcharts(data);
    Highcharts.setOptions({
        lang: {
        thousandsSep: ','
      }
    });

    Highcharts.chart(container, data);
}