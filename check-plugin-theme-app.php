<?php

/**
 * Script Name: Theme & Plugin Checker
 * Author: Muzamil Attiq
 */
function get_active_theme($site_url) {
    $html = @file_get_contents($site_url);
    if (!$html) {
        return "Failed to retrieve site data.";
    }

    preg_match('/wp-content\/themes\/([a-zA-Z0-9-_]+)\//', $html, $matches);
    return isset($matches[1]) ? ucfirst($matches[1]) : "Theme not found.";
}

function get_active_plugins($site_url) {
    $html = @file_get_contents($site_url);
    if (!$html) {
        return ["Failed to retrieve site data."];
    }

    preg_match_all('/wp-content\/plugins\/([a-zA-Z0-9-_]+)\//', $html, $matches);
    return !empty($matches[1]) ? array_unique($matches[1]) : ["No plugins detected."];
}

$results = [];
$theme_counts = [];
$plugin_counts = [];
$total_sites = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $site_urls = explode("\n", trim($_POST['site_urls'])); 
    $site_urls = array_map('trim', $site_urls); 
    $total_sites = count($site_urls);

    foreach ($site_urls as $site_url) {
        if (!filter_var($site_url, FILTER_VALIDATE_URL)) {
            $results[] = [
                "site_url" => $site_url,
                "theme" => "Invalid URL!",
                "plugins" => ["Invalid URL!"]
            ];
        } else {
            $theme = get_active_theme($site_url);
            $plugins = get_active_plugins($site_url);

            $results[] = [
                "site_url" => $site_url,
                "theme" => $theme,
                "plugins" => $plugins
            ];

            if ($theme !== "Failed to retrieve site data." && $theme !== "Theme not found.") {
                $theme_counts[$theme] = ($theme_counts[$theme] ?? 0) + 1;
            }

            foreach ($plugins as $plugin) {
                if ($plugin !== "Failed to retrieve site data." && $plugin !== "No plugins detected.") {
                    $plugin_counts[$plugin] = ($plugin_counts[$plugin] ?? 0) + 1;
                }
            }
        }
    }
}

arsort($theme_counts);
arsort($plugin_counts);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-Site WordPress Checker</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 1000px;
            margin: auto;
        }
        h2 {
            margin-bottom: 15px;
            color: #333;
        }
        textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            resize: none;
        }
        button {
            padding: 10px 20px;
            border: none;
            background: #28a745;
            color: #fff;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background: #218838;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background: #007bff;
            color: #fff;
        }
        td {
            background: #f9f9f9;
        }
        #preloader {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background: rgba(255, 255, 255, 0.8);
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
    </style>
    <script>
        function showLoader() {
            document.getElementById("preloader").style.display = "flex";
        }
    </script>
</head>
<body>
    <div id="preloader">Processing... Please wait.</div>

    <div class="container">
        <h2>Multi-Site WordPress Checker</h2>
        <form method="post" onsubmit="showLoader()">
            <textarea name="site_urls" placeholder="Enter one site per line"><?= isset($_POST['site_urls']) ? htmlspecialchars($_POST['site_urls']) : '' ?></textarea>
            <button type="submit">Check</button>
        </form>

        <?php if (!empty($results)): ?>
            <h3>Total Websites Checked: <?= $total_sites ?></h3>

            <table>
                <tr>
                    <th>Website</th>
                    <th>Active Theme</th>
                    <th>Active Plugins</th>
                </tr>
                <?php foreach ($results as $result): ?>
                    <tr>
                        <td><?= htmlspecialchars($result['site_url']) ?></td>
                        <td><?= $result['theme'] ?></td>
                        <td>
                            <?= implode(", ", array_map(fn($plugin) => ucfirst(str_replace('-', ' ', $plugin)), $result['plugins'])) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <h2>Common Themes Across Sites</h2>
            <table>
                <tr>
                    <th>Theme Name</th>
                    <th>Usage Count</th>
                </tr>
                <?php foreach ($theme_counts as $theme => $count): ?>
                    <tr>
                        <td><?= $theme ?></td>
                        <td><?= $count ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <h2>Common Plugins Across Sites</h2>
            <table>
                <tr>
                    <th>Plugin Name</th>
                    <th>Usage Count</th>
                </tr>
                <?php foreach ($plugin_counts as $plugin => $count): ?>
                    <tr>
                        <td><?= ucfirst(str_replace('-', ' ', $plugin)) ?></td>
                        <td><?= $count ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
