function lookup(inputString) {
    if (inputString.length == 0) {
        // Hide the suggestion box.
        $('#suggestions').slideUp();
    } else {
        $.post("rpc.php", { queryString: "" + inputString + "" }, function (data) {
            if (data.length > 0) {
                $('#suggestions').slideDown();
                $('#autoSuggestionsList').html(data);
            }
        });
    }
}

function userLookup(inputString) {
    if (inputString.length == 0) {
        // Hide the suggestion box.
        $('#suggestions').slideUp();
    } else {
        $.post("rpcUser.php", { queryString: "" + inputString + "" }, function (data) {
            if (data.length > 0) {
                $('#suggestions').slideDown();
                $('#autoSuggestionsList').html(data);
            }
        });
    }
}

function adminLookup(inputString) {
    if (inputString.length == 0) {
        // Hide the suggestion box.
        $('#suggestions').slideUp();
    } else {
        $.post("rpcUser.php", { editUser: true, queryString: "" + inputString + "" }, function (data) {
            if (data.length > 0) {
                $('#suggestions').slideDown();
                $('#autoSuggestionsList').html(data);
            }
        });
    }
}

function fill(thisValue) {
    if (thisValue) {
        $('#inputString').val(thisValue);
    }
    $('#suggestions').slideUp();
}

function doSearch() { $('#SearchForm').submit(); }

function searchAllUsers() {
    $('#inputString').val("");
    doSearch();
    return false;
}

function showPatient(id) {
    window.location.href = s_url + "?id=" + id;
}
