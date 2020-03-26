$(document).on('ready', function() {

    loadInfo();

    function loadInfo() {
        $.ajax({
            url: 'load-info',
            type: 'get',
            datatype: 'json',
            data: {
                _token: '{{ csrf_token() }}'
            }
        }).fail(function(statusCode, errorThrown) {
            console.log(statusCode + ' ' + errorThrown);
        }).done(function(response) {
            console.log(response);

            $("#tableroInfo").html(response.data.tableroInfo);
            graficos(response.data.graficoMiembros, response.data.graficoGenero);
        });

    }


    function graficos(dataMiembros, dataGenero) {

        if ($('#pieChartMiembros').length) {

            var ctx = document.getElementById("pieChartMiembros");
            var data = {
                datasets: [{
                    data: dataMiembros.values,
                    backgroundColor: dataMiembros.colores,
                    label: 'My dataset' // for legend
                }],
                labels: dataMiembros.labels
            };

            var pieChart = new Chart(ctx, {
                data: data,
                type: 'pie',
                otpions: {
                    legend: true
                }
            });

        }


        if ($('#graficoGenero').length) {
console.log(dataGenero.labels)
            var ctx = document.getElementById("graficoGenero");
            var data = {
                labels: dataGenero.labels,
                datasets: [{
                    data: dataGenero.values,
                    backgroundColor: dataGenero.colores,
                    hoverBackgroundColor: [
                        "#34495E",
                        "#B370CF",
                        "#CFD4D8",
                        "#36CAAB",
                        "#49A9EA"
                    ]

                }]
            };

            var canvasDoughnut = new Chart(ctx, {
                type: 'doughnut',
                tooltipFillColor: "rgba(51, 51, 51, 0.55)",
                data: data
            });

        }
    }

});
