<!DOCTYPE html>
<html>
<head>
    <title>Test AJAX Counties</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h2>Test AJAX Counties</h2>
    
    <button onclick="testCounties()">Test Fetch Counties</button>
    
    <div id="result"></div>
    
    <script>
    function testCounties() {
        $.ajax({
            url: "./views/fuelstation/fetchcounties.php",
            method: "post",
            data: {
                district: 1,
                action: "fetch"
            },
            dataType: "json",
            success: function(data) {
                console.log("Success:", data);
                $("#result").html("<h3>Success!</h3><pre>" + JSON.stringify(data, null, 2) + "</pre>");
            },
            error: function(xhr, status, error) {
                console.log("Error:", xhr.responseText);
                $("#result").html("<h3>Error!</h3><p>Status: " + status + "</p><p>Error: " + error + "</p><p>Response: " + xhr.responseText + "</p>");
            }
        });
    }
    </script>
</body>
</html>
