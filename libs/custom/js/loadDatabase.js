/* Global variables to hold current Seatmap ID and Image */
var currentSeatmapId = $('#backgroundImage').attr('data-seatmapID');
var currentSeatmapImage = $('#backgroundImage').attr('data-seatmapPath');

/* Global JSON object to store all profile is loaded from database */
var tempArrayJSON = [];

/**
 * @method: Append all profile to current seat map
 * @param: arraySeatmap: store all profile from database
 *         seatmapID: current seat map to show data
 * @return: None
 */
function loadAllProfileToSeatmap(arraySeatmap, seatmapID) {
    for (i = 0; i < arraySeatmap.length; i++) {
        if ((arraySeatmap[i].seatmapID).toString() === seatmapID.toString()) {
            $('#backgroundImage').append(
                '<li class="drapProfile dragged dropped" data-id="' + arraySeatmap[i].id + '" data-path="' + arraySeatmap[i].path + '" data-name="' + arraySeatmap[i].name + '" style="position: absolute; left: ' + arraySeatmap[i].x + 'px; top: ' + arraySeatmap[i].y + 'px;">\n' +
                '  <form>\n' +
                '  </form>\n' +
                '  <img src="' + arraySeatmap[i].path + '" height="90px" width="90px" image>\n' +
                '  <p style="position:absolute" class="users-list-name">' + arraySeatmap[i].name + '</p>\n' +
                '</li>'
            );
        }
    }
}

/**
 * @method: Load all profiles to all a array JSON
 *          Then call loadAllProfileToSeatmap() to append all data in the array
 * @param:  seatmapID: current seat map to show data
 * @return: None
 */
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

/**
 * @method: Copy all data from arrayJson which mapping with current seat map ID.
 *          After that, call loadAllProfileToSeatmap() for handle data to map
 *          Then call loadAllProfileToSeatmap() to append all data in the array
 * @param:  arrayJson: Global array that hold all profiles from database
 *          seatmapID: current seat map to show data
 * @return: None
 */
function loadingProfileFromArrayJson(arrayJson, seatmapID) {
    var temp = [];
    for (var i = 0; i < arrayJson.length; ++i) {
        if (toString(arrayJson[i].id) === toString(seatmapID)) {
            temp.push(arrayJson[i]);
        }
    }
    loadAllProfileToSeatmap(temp, seatmapID);
}

/**
 * @handleEvent: Click
 * @selection: Show seat map button
 *             - When user click show "Show Seatmap" button, all seat maps will be appeared
 *             - And when user chick once more time, the current seat map will be appeared.
 *             - AJAX is using in this selection.
 */
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

                $('#seatmapCustom').prepend(
                    '<div id="searchBox" class="input-group mb-3">\n' +
                    '  <input id="searchAndFilterSeatMap" type="text" class="form-control" placeholder="Type the name of seat map" aria-label="Recipient\'s username" aria-describedby="basic-addon2">\n' +
                    '  <div class="input-group-append">\n' +
                    '    <button class="btn btn-outline-secondary" type="button">Search</button>\n' +
                    '  </div>\n' +
                    '</div>'
                );

            for (i = 0; i < data.length; i++) {

                imageInfo[i] = 'Seatmap infomation\n' +
                    'Seatmap name: ' + data[i][2] + '\n' +
                    'File file type: ' + data[i][4] + '\n' +
                    'File file type: ' + data[i][3] + 'B\n'
                imageArray[i] =
                    '                    <div id="' + data[i][0] + '" class="hoverEffect col-4"  alt="Card image cap">\n' +
                    '                        <span class="badge badge-dark btn-lg mb-1" style="font-size:100%">' + data[i][2] + '</span>' +
                    '                        <div class="card" style="width: 27rem;" data-toggle="tooltip" data-placement="bottom" title="' + imageInfo[i] + '">\n' +
                    '                            <img data-seatmapImage="' + data[i][1] + '" data-seatmapId="' + data[i][0] + '" class="chooseSeatmap card-img-top" src="' + data[i][1] + '">\n' +
                    '                        </div>\n' +
                    '                            <form>' +
                    //'                                <button type="button" data-seatmapImage="' + data[i][1] + '" data-seatmapId="' + data[i][0] + '" class="chooseSeatmap btn btn-outline-success btn-sm mt-1">Choose</button>\n' +
                    '                            </form>' +
                    '                        </div>\n'

                $('#listAllSeatmap').append(imageArray[i]);
            }
        } else {
            $('#backgroundImage').css('margin-left', '15px');
            $('#listAllSeatmap').empty();
            $('#searchBox').empty();
            $('#seatmapCustom').append('<div id="backgroundImage" data-seatmapID="' + currentSeatmapId + '" style="background-image: url(' + currentSeatmapImage + ');">');

            loadingProfileFromArrayJson(tempArrayJSON, currentSeatmapId);
            getSizeOfBackgroundImage();
        }
    });
});

/**
 * @handleEvent: Click
 * @selection: Choose seat map button
 *             - When all seat map is displayed in screen, user can move the mouse in a specific seat map.
 *             Choose seat map button will be hovered.
 *             - User can click and choose what seat map is current.
 */
$(document).on("click", ".chooseSeatmap", function () {
    currentSeatmapId = $(this).attr('data-seatmapId');
    currentSeatmapImage = $(this).attr('data-seatmapImage');
    $('#backgroundImage').css('margin-left', '15px');
    $('#listAllSeatmap').empty();
    $('#seatmapCustom').append('<div id="backgroundImage" data-seatmapID="' + currentSeatmapId + '" style="background-image: url(' + currentSeatmapImage + ');">');

    loadingProfileFromArrayJson(tempArrayJSON, currentSeatmapId);
    getSizeOfBackgroundImage();
});

/**
 * @handleEvent: Jquery document is ready
 */
$(document).ready(function () {

    /* Get background image size (map size), set height and width for screen */
    getSizeOfBackgroundImage();

    /* Loading all profiles to current seat map */
    loadingProfileFromDatabase(currentSeatmapId);

    /**
     * @handleEvent: keyup - user type in search box
     * @selection: Filter and show all seat map that matching with input value by string.
     */
    $(document).on("keyup", "#searchAndFilterSeatMap", function () {
        var value = $(this).val().toLowerCase();
        $("#listAllSeatmap .hoverEffect").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});

/**
 * @method: get original size of background image. Set the size to the container.
 * @param: None
 * @return: None
 */
function getSizeOfBackgroundImage(){

    if(!$('#backgroundImage').length){
        return false;
    }
    var imageSrc = document
        .getElementById('backgroundImage')
        .style
        .backgroundImage
        .replace(/url\((['"])?(.*?)\1\)/gi, '$2')
        .split(',')[0];

    var image = new Image();
    image.src = imageSrc;

    var width = image.width,
        height = image.height;

    $('#backgroundImage').css('width', width +'px');
    $('#backgroundImage').css('height', height +'px');

    if(height < 720) {
        $('.login').css('min-height', '1000px');
    } else {
        $('.login').css('min-height', (height + 100) + 'px');
    }
}