/* Current seat map variable */
var currentSeatmapId = $('#backgroundImage').attr('data-seatmapID');
var currentSeatmapImage = $('#backgroundImage').attr('data-seatmapPath');

/* Global JSON object to load from database of all profiles
and show it to all seatmaps */
var arrayJSON = [];

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
                //'    <input type="hidden" name="id" value="' + arraySeatmap[i].id + '">\n' +
                //'    <input type="hidden" name="path" value="' + arraySeatmap[i].path + '">\n' +
                '    <button id="removeOutOfSeatmap" type="button" data-user-id="' + arraySeatmap[i].id + '">×</button>\n' +
                '  </form>\n' +
                '  <img src="' + arraySeatmap[i].path + '" height="90px" width="90px" image>\n' +
                '  <a href="updateUser.php?id=' + arraySeatmap[i].id + '"><p style="position:absolute" class="users-list-name">' + arraySeatmap[i].name + '</p></a>\n' +
                '</li>'
            );
        }
    }
    //callDragAndDrop();
    draggableElementLoadedInDatabase();
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
            //callDragAndDrop();
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

    //draggableElementLoadedInDatabase();
    //callDragAndDrop();
}

/**
 * @handleEvent: Jquery document is ready
 */
$(document).ready(function () {

    /* Get background image size (map size), set height and width for screen */
    loadingProfileFromDatabase(currentSeatmapId);

    /* Init both draggable and droppable element */
    callDragAndDrop();

    /* Loading all profiles to current seat map */
    getSizeOfBackgroundImage();

    /**
     * @handleEvent: Click
     * @selection: Remove profile
     *             - Handle delete profile post method to remove profile out of database
     */
    $(document).on("click", ".removeUser", function () {
        var user_name = $(this).attr('data-user-name');
        var result = confirm("Do you want to delete user: " + user_name + ' ?');
        if(result){
            // Nothing here. Waiting data is deleted
        } else {
            return false;
        }
    });

    /**
     * @method: Read file image from input
     * @param:  input
     * @return: None
     */
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
                $('#imagePreview').hide();
                $('#imagePreview').fadeIn(650);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    /**
     * @handleEvent: Image preview is changed
     * @selection: File to upload
     *             - When user change the image in input from,
     *             Remove image or Undo image button will be displays
     */
    $('#fileToUpload').change(function () {
        readURL(this);
        var dataUpdate = $('#imagePreview').attr('data-update');
        if (!$('#removeImage').length) { // detect id removeImage is exist or not
            if (dataUpdate === undefined) {
                $('.avatar-upload').append(
                    '<span id=\'removeImage\' class="btn btn-block btn-outline-dark mt-2">\n' +
                    '            Remove image\n' +
                    '</span>'
                )
            } else {
                if(!$('#removeImage2').length){
                    $('.avatar-upload').append(
                        '<span id=\'removeImage2\' class="btn btn-block btn-outline-dark mt-2">\n' +
                        '            Undo image\n' +
                        '</span>'
                    )
                }

            }
        }
    });

    /**
     * @handleEvent: Click
     * @selection: Remove image button
     *             - Handle event remove image to return the default image
     */
    $(document).on("click", "#removeImage", function () {
        inputField = $('#fileToUpload');
        //console.log(inputField[0]);
        if (inputField[0].files && inputField[0].files[0]) {
            var reader2 = new FileReader();
            reader2.onload = function (e) {
                $('#imagePreview').css('background-image', 'url(../images/default.png)');
                $('#imagePreview').hide();
                $('#imagePreview').fadeIn(650);
            }
        }
        reader2.readAsDataURL(inputField[0].files[0]);

        $('#removeImage').remove();
        $('#fileToUpload').val("");
    });

    /**
     * @handleEvent: Click
     * @selection: Undo image button
     *             - Handle event undo image to return the image which registered
     */
    $(document).on("click", "#removeImage2", function () {
        inputField = $('#fileToUpload');
        //console.log(inputField[0]);
        if (inputField[0].files && inputField[0].files[0]) {
            var reader2 = new FileReader();
            reader2.onload = function (e) {
                $('#imagePreview').css('background-image', 'url(' + $('#imagePreview').attr('data-update') + ')');
                $('#imagePreview').hide();
                $('#imagePreview').fadeIn(650);
            }
        }
        reader2.readAsDataURL(inputField[0].files[0]);

        $('#removeImage2').remove();
        $('#fileToUpload').val("");
    });

    /**
     * [THIS FUNCTION IS NOT USED IN THIS VERSION]
     * @handleEvent: Double click
     * @selection: Background image
     *             - When user double in background image. All user will be displayed
     *             - Handle more event.
     */
    /*$(document).on("dblclick", "#backgroundImage", function (e) {
        $.post('listAllProfile.php', function (response) {
            $.alert({
                title: 'Choose a profile!',
                content: response,
                columnClass: 'col-md-4 col-md-offset-4',
                animation: 'rotateXR',
                closeAnimation: 'rotateXR',
                theme: 'supervan'
            });
        });
    });*/

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
        $('#searchBox').remove();
        $('#seatmapCustom').append('<div id="backgroundImage" data-seatmapID="' + currentSeatmapId + '" style="background-image: url(' + currentSeatmapImage + ');">');

        if(arrayJSON.length > 0){
            for (var i = 0; i < arrayJSON.length; ++i) {
                console.log(arrayJSON[i].id);
                if(hasId(tempArrayJSON, arrayJSON[i].id)){
                    findAndReplace(tempArrayJSON, arrayJSON[i].id, arrayJSON[i].x, arrayJSON[i].y);
                } else {
                    tempArrayJSON.push(arrayJSON[i]);
                }
            }
        }
        getSizeOfBackgroundImage();
        loadingProfileFromArrayJson(tempArrayJSON, currentSeatmapId);
        droppableElementLoadedInDatabase();
        //loadAllProfileToSeatmap(arrayJSON, currentSeatmapId);
        //callDragAndDrop();
    });

    /**
     * @handleEvent: Click
     * @selection: Remove profile out of seat map
     *             - Remove profile out of seat map when user click 'x' button.
     *             - The profile is removed will be appeared in the left sidebar.
     *             - The profile will be not delete in database
     */
    $(document).on("click", "#removeOutOfSeatmap", function () {
        var deleteID = $(this).attr('data-user-id');
        var thisRemove = $(this);
        $.confirm({
            title: 'MESSAGE',
            content: 'Do you want to remove this profile out of seatmap?',
            buttons: {
                confirm: function () {
                    $.post('removeProfileOutOfSeatmap.php',
                        {deleteID: deleteID},
                        function(response){
                            if(response){
                                thisRemove.parentsUntil("div").remove();
                                response = JSON.parse(response);
                                $('.users-list').append(
                                    '<div class="drapProfile fixBugCanNotDrap">\n' +
                                    '    <li class="drapProfile fixBugCanNotDrap" data-id="'+response[0][0]+'" data-path="'+response[0][3]+'" data-name="'+response[0][1]+'">\n' +
                                    '        <form method="post" action="deleteUser.php">\n' +
                                    '             <input type="hidden" name="id" value="'+response[0][0]+'">\n' +
                                    '             <input type="hidden" name="path" value="'+response[0][3]+'">\n' +
                                    '             <button type="submit" data-user-name="'+response[0][1]+'" class="removeUser">&times;</button>\n' +
                                    '        </form>\n' +
                                    '        <img src="'+response[0][3]+'" height="90px" width="90px" Image">\n' +
                                    '        <a href="updateUser.php?id='+response[0][0]+'"><p class="users-list-name">'+response[0][1]+'</p></a>\n' +
                                    '     </li>\n' +
                                    '</div>'
                                );

                                initDragForClass('fixBugCanNotDrap');
                                $('.fixBugCanNotDrap').removeClass('fixBugCanNotDrap');

                                if(hasId(arrayJSON,response[0][0])){
                                    arrayJSON = arrayJSON.filter(function( obj ) {
                                        if(obj.id !== undefined && response[0][0] !== undefined){
                                            return obj.id.toString() !== response[0][0].toString();
                                        }
                                    });
                                }
                                if(hasId(tempArrayJSON,response[0][0])){
                                    tempArrayJSON = tempArrayJSON.filter(function( obj ) {
                                        return obj.id.toString() !== response[0][0].toString();
                                    });
                                }
                            } else {
                                $.alert('Something wrong when delete profile!');
                            }
                        }
                    );
                },
                cancel: function () {

                }
            }
        });

        //alert($(this).attr('data-user-id'));
    });

    /**
     * @handleEvent: Click
     * @selection: Cancel all manipulation before, except remove out of seat map event.
     *             - Remove profile out of seat map when user click 'x' button.
     *             - The profile is removed will be appeared in the left sidebar.
     *             - The profile will be not delete in database
     */
    $(document).on("click", "#cancelSaveSeatmap", function () {
        $.confirm({
            title: 'MESSAGE',
            content: 'Do you want to undo everything that changed?',
            buttons: {
                confirm: function () {
                    location.reload();
                },
                cancel: function () {

                }
            }
        });

    });

    /**
     * @handleEvent: Click
     * @selection: Save all position of profiles to database
     *             - Position (left and top) and seat map ID will be saved to database.
     */
    $(document).on("click", "#saveToSeatmap", function () {
        $.confirm({
            title: 'MESSAGE!',
            content: 'Click confirm to save all data',
            buttons: {
                confirm: function () {
                    if (arrayJSON.length) {
                        $.post('saveProfileToSeatmap.php',
                            {
                                jsonObject: JSON.stringify(arrayJSON)
                            },
                            function (response) {
                                if (response == 1) {
                                    /* Save database successfully */
                                } else {
                                    $.alert({
                                        title: 'ERROR!',
                                        content: 'Can not save database!',
                                    });
                                }
                            });
                    } else {
                        $.alert({
                            title: 'ERROR',
                            content: 'Nothing to save in database!',
                        });
                    }
                },
                cancel: function () {
                    /* Cancel */
                }
            }
        });

    });

    /**
     * @handleEvent: keyup - user type in search box
     * @selection: Filter and show all profile that matching with input value by string.
     */
    $("#searchAndFilter").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $(".users-list div").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

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
 * @method: Remove seat map out of database. All profiles in the map will be return to left sidebar.
 * @param:  id, path
 * @return: None
 */
function removeSeatmap(id, path) {
    $.confirm({
        title: 'WARNING!',
        content: 'When you delete this seatmap, all profiles in this will be remove out of seatmap. Are you sure?',
        buttons: {
            confirm: function () {
                $.post('deleteSeatmap.php',
                    {id: id, path: path},
                    function (data) {
                        $('#' + id).remove();
                        location.reload();
                    }
                );
            },
            cancel: function () {

            }
        }
    });
}

/**
 * @handleEvent: Click
 * @selection: Show seat map button
 *             - When user click show "Show SeatMap" button, all seat maps will be appeared
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

                imageInfo[i] = 'SeatMap infomation\n' +
                    'SeatMap name: ' + data[i][2] + '\n' +
                    'File file type: ' + data[i][4] + '\n' +
                    'File file type: ' + data[i][3] + 'B\n'
                imageArray[i] =
                    '                    <div id="' + data[i][0] + '" class="hoverEffect col-4"  alt="Card image cap">\n' +
                    '                        <span class="badge badge-dark btn-lg mb-1" style="font-size:100%">' + data[i][2] + '</span>' +
                    '                        <div class="card" style="width: 27rem;" data-toggle="tooltip" data-placement="bottom" title="' + imageInfo[i] + '">\n' +
                    '                            <img data-seatmapImage="' + data[i][1] + '" data-seatmapId="' + data[i][0] + '" class="chooseSeatmap card-img-top" src="' + data[i][1] + '">\n' +
                    '                        </div>\n' +
                    '                            <form action="updateSeatmap.php" method="get">' +
                    '                                <input type="hidden" class="form-control form-control-lg" name="id" value="' + data[i][0] + '">\n' +
                    //'                                <button type="button" data-seatmapImage="' + data[i][1] + '" data-seatmapId="' + data[i][0] + '" class="chooseSeatmap btn btn-outline-success btn-sm mt-1">Choose</button>\n' +
                    '                                <button type="submit" class="btn btn-outline-dark btn-sm mt-1">Edit</button>\n' +
                    '                                <button type="button" class="btn btn-outline-danger btn-sm mt-1" onclick="return removeSeatmap(\'' + data[i][0] + '\',\'' + data[i][1] + '\')">Remove</button>\n' +
                    '                            </form>' +
                    '                        </div>\n'

                $('#listAllSeatmap').append(imageArray[i]);
            }
        } else {
            $('#backgroundImage').css('margin-left', '15px');
            $('#listAllSeatmap').empty();
            $('#searchBox').remove();
            $('#seatmapCustom').append('<div id="backgroundImage" data-seatmapID="' + currentSeatmapId + '" style="background-image: url(' + currentSeatmapImage + ');">');
            if(arrayJSON.length > 0){
                for (var i = 0; i < arrayJSON.length; ++i) {
                    if(hasId(tempArrayJSON, arrayJSON[i].id)){
                        findAndReplace(tempArrayJSON, arrayJSON[i].id, arrayJSON[i].x, arrayJSON[i].y);
                    } else {
                        tempArrayJSON.push(arrayJSON[i]);
                    }
                }
            }
            loadingProfileFromArrayJson(tempArrayJSON, currentSeatmapId);
            droppableElementLoadedInDatabase();
            getSizeOfBackgroundImage();
        }
    });
});

/**
 * @method: Check the ID is exist in array JSON or not.
 * @param:  data, id
 * @return: bool. Return true if exist, false if not.
 */
function hasId(data, id) {
    return data.some(function (el) {
        return el.id === id;
    });
}

/**
 * @method: Find value in object and update x, y of all element which mapping value
 * @param:  object, value, x, y
 * @return: None
 */
function findAndReplace(object, value, x, y) {
    for (var i in object) {
        if (typeof object[i] == typeof {}) {
            findAndReplace(object[i], value, x, y);
        }
        if (object[i] == value) {
            object["x"] = x;
            object["y"] = y;
            //object["seatmapID"] = currentSeatmapId;
        }
    }
}

/**
 * @method: Init both draggable and droppable element
 * @param:  None
 * @return: None
 */
function callDragAndDrop() {
    $(".drapProfile").draggable({
        cursor: "crosshair",
        revert: "invalid",
        zIndex: 1000,
        scroll: true,
        start: function (event, ui) {
            $(ui.helper).addClass("ui-helper");
            $(ui.helper).css('border-radius', '50%');
        },
        helper: function () {
            return jQuery(this).clone().appendTo('body');
        },
        stop: function (event, ui) {

        },
        drag: function (event, ui) {
            $(ui.helper).css('border-radius', '50%');
        },
        containment: "document"
    });

    $("#backgroundImage").droppable({
        activeClass: 'ui-state-hover',
        accept: '.drapProfile',
        drop: function (e, ui) {

            var id = $(ui.draggable).attr('data-id'); // ID to save to JSON object
            var path = $(ui.draggable).attr('data-path'); // Path to save to JSON object
            var name = $(ui.draggable).attr('data-name');// Name to save to JSON object
            var x = null; // x to save to JSON object
            var y = null; // y to save to JSON object

            if (!ui.draggable.hasClass("dropped")) {
                //debugger;
                //console.log('notDrop');
                var parentOffset = jQuery('#backgroundImage').offset();
                var dropped = jQuery(ui.draggable).clone().css('position', 'absolute').addClass("dropped").draggable(
                    {
                        revert: "invalid",
                        scroll: true
                    }

                );

                dropped.css('left', (ui.position.left - parentOffset.left) + 'px');
                dropped.css('top', (ui.position.top - parentOffset.top) + 'px');

                x = ui.position.left - parentOffset.left; // x to save to JSON object
                y = ui.position.top - parentOffset.top;

                $(dropped).appendTo($(this));

                $(dropped).children('form').replaceWith(
                    '<form>\n' +
                    '    <button id="removeOutOfSeatmap" type="button" data-user-id="' + id + '">×</button>\n' +
                    '  </form>'
                );

                $(dropped).children('a').children('p').css('position', 'absolute');

                $(ui.draggable).detach();
            } else {
                //console.log('Drop');
                x = ui.position.left;
                y = ui.position.top;
            }

            /* Save to main JSON object */
            if (hasId(arrayJSON, id)) { // if id is exist in arrayJSON --> modify x, y
                findAndReplace(arrayJSON, id, x, y);
            } else { // if id is not exist in arrayJSON --> add new record in arrayJSON
                arrayJSON.push({'id': id, 'x': x, 'y': y, 'seatmapID': currentSeatmapId, 'path': path, 'name': name});
            }
        }
    });
}

/**
 * @method: Init both draggable element which specific class name.
 * @param:  className
 * @return: None
 */
function initDragForClass(className) {
    $("." + className).draggable({
        cursor: "crosshair",
        revert: "invalid",
        zIndex: 1000,
        scroll: true,
        start: function (event, ui) {
            $(ui.helper).addClass("ui-helper");
            if ($(this).hasClass('dragged')) {
                $(this).detach();
            }
        },
        helper: function () {
            return jQuery(this).clone().appendTo('body');
        },
        stop: function (event, ui) {

        },
        drag: function (event, ui) {

        },
        containment: "document"
    });
}

/**
 * @method: Init draggable element
 * @param:  None
 * @return: None
 */
function draggableElementLoadedInDatabase() {
    $(".dragged").draggable({
        cursor: "crosshair",
        revert: "invalid",
        zIndex: 1000,
        scroll: true,
        start: function (event, ui) {
            $(ui.helper).addClass("ui-helper");
        }
    });
}

/**
 * @method: Init droppable element
 * @param:  None
 * @return: None
 */
function droppableElementLoadedInDatabase() {
    $("#backgroundImage").droppable({
        activeClass: 'ui-state-hover',
        accept: '.drapProfile',
        drop: function (e, ui) {

            var id = $(ui.draggable).attr('data-id'); // ID to save to JSON object
            var path = $(ui.draggable).attr('data-path'); // Path to save to JSON object
            var name = $(ui.draggable).attr('data-name');// Name to save to JSON object
            var x = null; // x to save to JSON object
            var y = null; // y to save to JSON object

            if (!ui.draggable.hasClass("dropped")) {
                //debugger;
                //console.log('notDrop');
                var parentOffset = jQuery('#backgroundImage').offset();
                var dropped = jQuery(ui.draggable).clone().css('position', 'absolute').addClass("dropped").draggable(
                    {
                        revert: "invalid",
                        scroll: true
                    }

                );

                dropped.css('left', (ui.position.left - parentOffset.left) + 'px');
                dropped.css('top', (ui.position.top - parentOffset.top) + 'px');

                x = ui.position.left - parentOffset.left; // x to save to JSON object
                y = ui.position.top - parentOffset.top;

                $(dropped).appendTo($(this));

                $(dropped).children('form').replaceWith(
                    '<form>\n' +
                    '    <button id="removeOutOfSeatmap" type="button" data-user-id="' + id + '">×</button>\n' +
                    '  </form>'
                );

                $(ui.draggable).detach();
            } else {
                //console.log('Drop');
                x = ui.position.left;
                y = ui.position.top;
            }

            /* Save to main JSON object */
            if (hasId(arrayJSON, id)) { // if id is exist in arrayJSON --> modify x, y
                findAndReplace(arrayJSON, id, x, y);
            } else { // if id is not exist in arrayJSON --> add new record in arrayJSON
                arrayJSON.push({'id': id, 'x': x, 'y': y, 'seatmapID': currentSeatmapId, 'path': path, 'name': name});
            }
        }

    });
}

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