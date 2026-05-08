<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link href = "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href = "https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap.min.css">	

    <link rel = "stylesheet" href = "css/site.css"/>

    <title>SIS - <?php echo $title ?></title>
    
    <style>
        body {
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .cit-header {
            background: linear-gradient(135deg, #ffd700 0%, #a00000 100%);
            padding: 20px 0;
            text-align: center;
            color: white;
        }
        .cit-header h1 {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 5px;
            letter-spacing: 2px;
        }
        .cit-header p {
            font-size: 0.9rem;
            opacity: 0.9;
            letter-spacing: 3px;
            margin-bottom: 0;
        }
        .nav-links {
            background-color: white;
            padding: 12px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            text-align: center;
        }
        .nav-links a {
            color: #800000;
            text-decoration: none;
            margin: 0 20px;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 5px 0;
            transition: all 0.3s ease;
            position: relative;
        }
        .nav-links a:hover {
            color: #a00000;
        }
        .nav-links a:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            background: #800000;
            bottom: 0;
            left: 0;
            transition: width 0.3s ease;
        }
        .nav-links a:hover:after {
            width: 100%;
        }
        .separator {
            color: #ccc;
            font-weight: normal;
        }
        @media (max-width: 768px) {
            .nav-links a {
                display: inline-block;
                margin: 5px 10px;
            }
            .separator {
                display: none;
            }
        }
    </style>
  </head>
  <body>
    <div class="cit-header">
        <div class="container">
            <img src="images/citlogo.png" width="60" height="50" alt="CIT Logo" style="margin-bottom: 10px;">
            <h1>CEBU INSTITUTE OF TECHNOLOGY</h1>
            <p>UNIVERSITY</p>
        </div>
    </div>
    <div class="nav-links">
        <div class="container">
            <a href="dashboard.php">Announcement</a>
            <span class="separator">|</span>
            <a href="#">About Us</a>
            <span class="separator">|</span>
            <a href="dashboard.php">Status</a>
            <span class="separator">|</span>
            <a href="dashboard.php">Events</a>
            <span class="separator">|</span>
            <a href="logout.php">Log out</a>
        </div>
    </div>
  </body>
</html>