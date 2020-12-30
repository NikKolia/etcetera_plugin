$ = jQuery;

window.onload = function () {
    mafsForm.submit();
};

var mafs = $("#my-ajax-filter-search");
var mafsForm = mafs.find("form");

mafsForm.submit(function (e) {
    e.preventDefault();

    if (mafsForm.find("#search").val().length !== 0) {
        var search = mafsForm.find("#search").val();
    }
    if (mafsForm.find("#building_title").val().length !== 0) {
        var building_title = mafsForm.find("#building_title").val();
    }
    if (mafsForm.find("#building_location").val().length !== 0) {
        var building_location = mafsForm.find("#building_location").val();
    }
    if (mafsForm.find("#district").val().length !== 0) {
        var district = mafsForm.find("#district").val();
    }
    if (mafsForm.find("#floors_number").val().length !== 0) {
        var floors_number = mafsForm.find("#floors_number").val();
    }
    if (mafsForm.find("#building_type").val().length !== 0) {
        var building_type = mafsForm.find("#building_type").val();
    }
    if (mafsForm.find("#building_eco").val().length !== 0) {
        var building_eco = mafsForm.find("#building_eco").val();
    }
    if(mafsForm.find("#square").val().length !== 0) {
        var square = mafsForm.find("#square").val();
    }
    if(mafsForm.find("#rooms").val().length !== 0) {
        var rooms = mafsForm.find("#rooms").val();
    }
    if(mafsForm.find("#balcony").val().length !== 0) {
        var balcony = mafsForm.find("#balcony").val();
    }
    if(mafsForm.find("#bathroom").val().length !== 0) {
        var bathroom = mafsForm.find("#bathroom").val();
    }

    var data = {
        action: "my_ajax_filter_search",
        search: search,
        building_title: building_title,
        building_location: building_location,
        district: district,
        floors_number: floors_number,
        building_type: building_type,
        building_eco: building_eco,
        square : square,
        rooms : rooms,
        balcony : balcony,
        bathroom : bathroom
    }

    // jQuery Ajax function codes
    $.ajax({
        url: ajax_url,
        data: data,
        success: function (response) {
            mafs.find("ul").empty();
            if (response) {
                for (var i = 0; i < response.length; i++) {
                    var html = "<li id='property-" + response[i].id + "'>";
                    html += "  <a href='" + response[i].permalink + "' title='" + response[i].title + "'>";
                    html += "      <img src='" + response[i].poster + "' alt='" + response[i].title + "' />";
                    html += "      <div class='property-info'>";
                    html += "          <h4>" + response[i].building_title + "</h4>";
                    html += "          <p>Площадь: " + response[i].square + "</p>";
                    html += "          <p>Адрес: " + response[i].building_location + "</p>";
                    html += "          <p>Район: " + response[i].district + "</p>";
                    html += "          <p>Этажность: " + response[i].floors_number + "</p>";
                    html += "          <p>Тип: " + response[i].building_type + "</p>";
                    html += "          <p>Экологичность: " + response[i].building_eco + "</p>";
                    html += "          <p>Кол. комнат: " + response[i].rooms + "</p>";
                    html += "          <p>Балкон: " + response[i].balcony + "</p>";
                    html += "          <p>Санузел: " + response[i].bathroom + "</p>";
                    html += "      </div>";
                    html += "  </a>";
                    html += "</li>";
                    mafs.find("ul").append(html);
                }
            } else {
                mafs.find("ul").empty();
                var html = "<li class='no-result'>No matching items found. Try a different filter or search keyword</li>";
                mafs.find("ul").append(html);
            }
        },
        error: function () {
            mafs.find("ul").empty();
            var html = "<li class='no-result'>No matching items found. Try a different filter or search keyword</li>";
            mafs.find("ul").append(html);
        }
    });
});