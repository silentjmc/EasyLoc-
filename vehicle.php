<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/database/mongodb_connection.php';
require_once __DIR__ . '/src/vehicle/vehicle_crud.php';

$mongodbConnection = new MongodbConnection();

$vehicleCrud = new VehicleCrud($mongodbConnection);

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $action = $_POST['action'] ?? '';
        switch ($action) {
            case 'create_vehicle':
                $vehicle = new Vehicle([
                    'uid' => $_POST['vehicle_uid'],
                    'licencePlate' => $_POST['vehicle_licence_plate'],
                    'informations' => $_POST['vehicle_informations'],
                    'km' => $_POST['vehicle_km']
                ]);
                $vehicleCrud->createVehicle($vehicle);
                $message = 'Vehicle created successfully!';
                break;
            case 'update_vehicle':
                $vehicle = new Vehicle([
                    'uid' => $_POST['vehicle_uid'],
                    'licencePlate' => $_POST['vehicle_licence_plate'],
                    'informations' => $_POST['vehicle_informations'],
                    'km' => $_POST['vehicle_km']
                ]);
                $vehicleCrud->updateVehicle($vehicle);
                $message = 'Vehicle updated successfully!';
                break;
            case 'delete_vehicle':
                $vehicleCrud->deleteVehicle($_POST['vehicle_uid']);
                $message = 'Vehicle deleted successfully!';
                break;
            case 'get_vehicle_by_licence_plate':
                $vehicleByLicencePlate = $vehicleCrud->getVehicleByLicencePlate($_POST['vehicle_licence_plate']);
                $message = 'Vehicle retrieved successfully!';
                break;
            case 'count_vehicle_by_greater_km':
                $vehiclesByGreaterKm = $vehicleCrud->countVehiclesWithKmGreater($_POST['vehicle_km']);
                $message = 'Vehicles retrieved successfully!';
                break;    
            case 'count_vehicle_by_lesser_km':
                $vehiclesByLesserKm = $vehicleCrud->countVehiclesWithKmLesser($_POST['vehicle_km']);
                $message = 'Vehicles retrieved successfully!';
                break;
            default:
                $message = 'Invalid action!';
                break;
        }
    } catch (Exception $e) {
        $message = 'Error: ' . $e->getMessage();
    }
}

$vehicles = $vehicleCrud->getAllVehicles();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EasyLoc Management</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>EasyLoc Management</h1>
    <div class="navigation">
        <h2>Actions</h2>
        <a href="index.php">Contract</a>
        <a href="billing.php">Billing</a>
        <a href="customer.php">Customer</a>
        <a href="vehicle.php">Vehicle</a>
    </div>
    <p class="message"><?php echo $message; ?></p>
    <div class="container">
        <div class="column1">
            <h2>Vehicle</h2>
            <h3>Vehicle Create</h3>
            <form method="post">
                <input type="hidden" name="action" value="create_vehicle">
                <label>UID: <input type="text" name="vehicle_uid" required></label><br>
                <label>Licence Plate: <input type="text" name="vehicle_licence_plate" required></label><br>
                <label>Informations: <input type="text" name="vehicle_informations" required></label><br>
                <label>Kilometers: <input type="number" name="vehicle_km" required></label><br>
                <button type="submit">Create Vehicle</button>
            </form>
            <h3>Vehicle Update</h3>
            <form method="post">
                <input type="hidden" name="action" value="update_vehicle">
                <label>UID: <input type="text" name="vehicle_uid" required></label><br>
                <label>Licence Plate: <input type="text" name="vehicle_licence_plate" required></label><br>
                <label>Informations: <input type="text" name="vehicle_informations" required></label><br>
                <label>Kilometers: <input type="number" name="vehicle_km" required></label><br>
                <button type="submit">Update Vehicle</button>
            </form>
            <h3>Vehicle Delete</h3>
            <form method="post">
                <input type="hidden" name="action" value="delete_vehicle">
                <label>UID: <input type="text" name="vehicle_uid" required></label><br>
                <button type="submit">Delete Vehicle</button>
            </form>
        </div>
        <div class="column2">
            <h2>Vehicles Table</h2>
            <table>
                <thead>
                    <tr>
                        <th>UID</th>
                        <th>Licence Plate</th>
                        <th>Informations</th>
                        <th>Kilometers</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vehicles as $vehicle): ?>
                        <tr>
                            <td><?php echo $vehicle->getUid(); ?></td>
                            <td><?php echo $vehicle->getLicencePlate(); ?></td>
                            <td><?php echo $vehicle->getInformations(); ?></td>
                            <td><?php echo $vehicle->getKm(); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="container">
        <div class="column1">
            <h3>Get Vehicle by Licence Plate</h3>
            <form method="post">
                <input type="hidden" name="action" value="get_vehicle_by_licence_plate">
                <label>Licence Plate: <input type="text" name="vehicle_licence_plate" required></label><br>
                <button type="submit">Get Vehicle</button>
            </form>
        </div>
        <div class="column2">
            <?php if (isset($vehicleByLicencePlate)): ?>
            <h2>vehicle Table</h2>
            <h3>Result:</h3>
            <p>UID: <?php echo $vehicleByLicencePlate->getUid(); ?></p>
            <p>Licence plate Name: <?php echo $vehicleByLicencePlate->getLicencePlate(); ?></p>
            <p>Informations: <?php echo $vehicleByLicencePlate->getInformations(); ?></p>
            <p>Km: <?php echo $vehicleByLicencePlate->getKm(); ?></p>
            <?php endif; ?>
        </div>
    </div>
    <div class="container">
        <div class="column1">
            <h3>Count Vehicles with Kilometers Greater</h3>
            <form method="post">
                <input type="hidden" name="action" value="count_vehicle_by_greater_km">
                <label>Kilometers: <input type="number" name="vehicle_km" required></label><br>
                <button type="submit">Get Vehicles</button>
            </form>
        </div>
        <div class="column2">
            <?php if (isset($vehiclesByGreaterKm)): ?>
            <h2>Vehicles Table</h2>
            <h3>Result:</h3>
            <p>Vehicles: <?php echo $vehiclesByGreaterKm; ?></p>
            <?php endif; ?>
        </div>
    </div>
    <div class="container">
        <div class="column1">
            <h3>Count Vehicles with Kilometers Lesser</h3>
            <form method="post">
                <input type="hidden" name="action" value="count_vehicle_by_lesser_km">
                <label>Kilometers: <input type="number" name="vehicle_km" required></label><br>
                <button type="submit">Get Vehicles</button>
            </form>
        </div>
        <div class="column2">
            <?php if (isset($vehiclesByLesserKm)): ?>
            <h2>Vehicles Table</h2>
            <h3>Result:</h3>
            <p>Vehicles: <?php echo $vehiclesByLesserKm; ?></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>