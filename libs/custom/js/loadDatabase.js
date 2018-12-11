var currentSeatmapId = $('#backgroundImage').attr('data-seatmapID');
var currentSeatmapImage = $('#backgroundImage').attr('data-seatmapPath');

/* Global JSON object to store all profile is loaded from database */
var tempArrayJSON = [];


/**
 * @method: Load all profiles to all seatmaps
 * @param: arraySeatmap: store all profile from database
 *         seatmapID: seatmap to show data
 * @return: None
 */
function loadAllProfileToSeatmap(arraySeatmap, seatmapID) {
    for (i = 0; i < arraySeatmap.length; i++) {
        if ((arraySeatmap[i].seatmapID).toString() === seatmapID.toString()) {
            $('#backgroundImage').append(
                '<li class="drapProfile dragged" data-id="' + arraySeatmap[i].id + '" data-path="' + arraySeatmap[i].path + '" data-name="' + arraySeatmap[i].name + '" style="position: absolute; left: ' + arraySeatmap[i].x + 'px; top: ' + arraySeatmap[i].y + 'px;">\n' +
                '  <img src="' + arraySeatmap[i].path + '" height="90px" width="90px" image>\n' +
                '  <p class="users-list-name">' + arraySeatmap[i].name + '</p>\n' +
                '</li>'
            );
        }
    }
}

function loadingProfileFromDatabase(seatmapID) {
    var showArrayJSON = [];
    $.post('loadingProfileFromDatabase.php',
        {flag: 'true'},
        function (response) {
            response = JSON.parse(response);
            for (var i = 0; i < response.length; ++i) {

                if(response[i][6] && response[i][5]){
                    var position = JSON.parse(response[i][5]);
                    if(seatmapID !== undefined){
                        if (response[i][6].toString() === seatmapID.toString()) {
                            showArrayJSON.push({
                                'id': response[i][0],
                                'x': position.x,
                                'y': position.y,
                                'seatmapID': response[i][6],
                                'path': response[i][3],
                                'name': response[i][1]
                            });
                        }
                        tempArrayJSON.push({
                            'id': response[i][0],
                            'x': position.x,
                            'y': position.y,
                            'seatmapID': response[i][6],
                            'path': response[i][3],
                            'name': response[i][1]
                        });
                    }
                }
            }
            loadAllProfileToSeatmap(showArrayJSON, seatmapID);
        });
}

function loadingProfileFromArrayJson(arrayJson, seatmapID) {
    var temp = [];
    for (var i = 0; i < arrayJson.length; ++i) {
        if (toString(arrayJson[i].id) === toString(seatmapID)) {
            temp.push(arrayJson[i]);
        }
    }
    loadAllProfileToSeatmap(temp, seatmapID);
}

/* Show Seatmap button */
$('#showSeatmap').click(function () {

    var n = $("#sidebarCustom").css("left");
    if (n === '0px') {
        $('#seatmapCustom').css('margin-left', '0px');
    } else {
        $('#seatmapCustom').css('margin-left', '15px');
    }

    $.getJSON("viewSeatmap.php", function (data, status) {
        if ($('#backgroundImage').length) { // detect id backgroundImage is exist or not
            $('#backgroundImage').remove();

            var imageArray = [];
            var imageInfo = [];

            for (i = 0; i < data.length; i++) {

                imageInfo[i] = 'Seatmap infomation\n' +
                    'Seatmap name: ' + data[i][2] + '\n' +
                    'File file type: ' + data[i][4] + '\n' +
                    'File file type: ' + data[i][3] + 'B\n'
                imageArray[i] =
                    '                    <div id="' + data[i][0] + '" class="hoverEffect col-4"  alt="Card image cap">\n' +
                    '                        <span class="badge badge-dark btn-lg mb-1" style="font-size:100%">' + data[i][2] + '</span>' +
                    '                        <div class="card" style="width: 27rem;" data-toggle="tooltip" data-placement="bottom" title="' + imageInfo[i] + '">\n' +
                    '                            <img class="card-img-top" src="' + data[i][1] + '">\n' +
                    '                        </div>\n' +
                    '                            <form>' +
                    '                                <button type="button" data-seatmapImage="' + data[i][1] + '" data-seatmapId="' + data[i][0] + '" class="chooseSeatmap btn btn-outline-success btn-sm mt-1">Choose</button>\n' +
                    '                            </form>' +
                    '                        </div>\n'

                $('#listAllSeatmap').append(imageArray[i]);
            }
        } else {
            $('#backgroundImage').css('margin-left', '15px');
            $('#listAllSeatmap').empty();
            $('#seatmapCustom').append('<div id="backgroundImage" data-seatmapID="' + currentSeatmapId + '" class="w-100 h-75" style="background-image: url(' + currentSeatmapImage + ');">');

            loadingProfileFromArrayJson(tempArrayJSON, currentSeatmapId);

        }
    });
});

$(document).on("click", ".chooseSeatmap", function () {
    currentSeatmapId = $(this).attr('data-seatmapId');
    currentSeatmapImage = $(this).attr('data-seatmapImage');
    $('#backgroundImage').css('margin-left', '15px');
    $('#listAllSeatmap').empty();
    $('#seatmapCustom').append('<div id="backgroundImage" data-seatmapID="' + currentSeatmapId + '" class="w-100 h-75" style="background-image: url(' + currentSeatmapImage + ');">');

    loadingProfileFromArrayJson(tempArrayJSON, currentSeatmapId);
});

$(document).ready(function () {
    loadingProfileFromDatabase(currentSeatmapId);
});