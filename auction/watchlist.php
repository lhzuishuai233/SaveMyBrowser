<button onclick="modifyWatchlist('add_to_watchlist', 1)">Add to Watchlist</button>
<button onclick="modifyWatchlist('remove_from_watchlist', 1)">Remove from Watchlist</button>

<script>
function modifyWatchlist(action, itemId) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "watchlist.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = JSON.parse(xhr.responseText);
            alert(response);
        }
    };
    xhr.send("functionname=" + action + "&arguments=" + itemId);
}
</script>