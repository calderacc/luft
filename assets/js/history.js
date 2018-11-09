$(document).ready(createChart);

function getTimestamps() {
    let timestampList = [];

    $('tr.datetime').each(function() {
        let timestamp = $(this).data('timestamp');

        timestampList.push(timestamp);
    });

    return timestampList;
}

function getValues(pollutantIdentifier) {
    let valueList = [];

    $('td.pollution-value.pollutant-' + pollutantIdentifier).each(function() {
        let value = $(this).data('value');

        valueList.push(value);
    });

    return valueList;
}

function createDatasets() {
    let datasetList = [];

    $('th.pollutant').each(function() {
        let pollutantIdentifier = $(this).data('pollutant-identifier');
        let pollutantName = $(this).text().trim();

        dataset = {
            label: pollutantName,
            data: getValues(pollutantIdentifier),
        };

        datasetList.push(dataset);
    });

    return datasetList;
}

function createChart() {
    let timestampList = getTimestamps();
    let datasetList = createDatasets();

    let ctx = document.getElementById('pollutionChart').getContext('2d');
    let myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: timestampList,
            datasets: datasetList,
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });
}
