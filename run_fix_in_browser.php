<!DOCTYPE html>
<html>
<head>
    <title>Fix Geographic Data</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        .info { color: blue; }
        button { padding: 10px 20px; font-size: 16px; margin: 10px 0; }
    </style>
</head>
<body>
    <h2>🔧 Fix Geographic Data Import</h2>
    
    <button onclick="runFix()">Run Data Fix</button>
    <div id="output"></div>
    
    <script>
    function runFix() {
        document.getElementById('output').innerHTML = '<p class="info">Running fix...</p>';
        
        fetch('fix_geographic_data.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('output').innerHTML = data;
        })
        .catch(error => {
            document.getElementById('output').innerHTML = '<p class="error">Error: ' + error + '</p>';
        });
    }
    </script>
</body>
</html>
