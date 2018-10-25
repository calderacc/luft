$(document).ready(function () {
    const prefetchedCities = new Bloodhound({
        datumTokenizer: function (data) {
            return Bloodhound.tokenizers.whitespace(data.value);
        },
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        prefetch: Routing.generate('prefetch'),
        cache: false,
        ttl: 60,
    });

    const remoteCities = new Bloodhound({
        datumTokenizer: function (data) {
            return Bloodhound.tokenizers.whitespace(data.value);
        },
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        cache: false,
        ttl: 60,
        remote: {
            url: Routing.generate('search') + '?query=%QUERY',
            wildcard: '%QUERY'
        },
    });

    $('.typeahead').typeahead({
        hint: false,
        highlight: true,
        minLength: 3,
        classNames: {
            dataset: 'tt-dataset tt-dataset-results container'
        }
    }, {
        name: 'prefetchedCities',
        source: prefetchedCities,
        display: function(data) {
            return data.value.name;
        },
        templates: {
            suggestion: renderSuggestion,
        }
    }, {
        name: 'remoteCities',
            source: remoteCities,
            display: function(data) {
            return data.value.name;
        },
        templates: {
            suggestion: renderSuggestion,
        }
    }).on('typeahead:selected', redirect);
});

function renderSuggestion(data) {
    let html = '';

    console.log(data);
    html += '<a href="' + data.value.url + '">';

    html += '<div class="row">';
    html += '<div class="col-12">';
    html += '<i class="fa fa-' + data.value.icon + '"></i> ';
    html += data.value.name;

    if (data.value.address || data.value.zipCode || data.value.city) {
        html += '<address>';

        if (data.value.address) {
            html += data.value.address;
        }

        if (data.value.address && (data.value.zipCode || data.value.city)) {
            html += '<br />';
        }

        if (data.value.zipCode) {
            html += data.value.zipCode;
        }

        if (data.value.zipCode && data.value.city) {
            html += ' ';
        }

        if (data.value.city) {
            html += data.value.city;
        }

        html += '</address>';

    }


    html += '</div>';
    html += '</div>';

    html += '</a>';

    return html;
}

function redirect(event, datum) {
    window.location = datum.value.url;
}
