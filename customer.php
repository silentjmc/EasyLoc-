<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/database/mongodb_connection.php';
require_once __DIR__ . '/src/customer/customer_crud.php';


$mongodbConnection = new MongodbConnection();

$customerCrud = new CustomerCrud($mongodbConnection);

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $action = $_POST['action'] ?? '';
        switch ($action) {
            case 'create_customer':
                $customer = new Customer([
                    'uid' => $_POST['customer_uid'],
                    'firstName' => $_POST['customer_first_name'],
                    'secondName' => $_POST['customer_second_name'],
                    'address' => $_POST['customer_address'],
                    'permitNumber' => $_POST['customer_permit_number']
                ]);
                $customerCrud->createCustomer($customer);
                $message = 'Customer created successfully!';
                break;
            case 'update_customer':
                $customer = new Customer([
                    'uid' => $_POST['customer_uid'],
                    'firstName' => $_POST['customer_first_name'],
                    'secondName' => $_POST['customer_second_name'],
                    'address' => $_POST['customer_address'],
                    'permitNumber' => $_POST['customer_permit_number']
                ]);
                $customerCrud->updateCustomer($customer);
                $message = 'Customer updated successfully!';
                break;
            case 'delete_customer':
                $customerCrud->deleteCustomer($_POST['customer_uid']);
                $message = 'Customer deleted successfully!';
                break;
            case 'get_customer':
                $getCustomer = $customerCrud->getCustomer($_POST['first_name'], $_POST['second_name']);
                if ($getCustomer) {
                    $message = 'Customer retrieved successfully!';
                } else {
                    $message = 'No customer found with the provided name.';
                }
                break;
            default:
                $message = 'Invalid action!';
                break;
        }
    } catch (Exception $e) {
        $message = 'Error: ' . $e->getMessage();
    }
}

$customers = $customerCrud->getAllCustomers();
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
            <h2>Customer</h2>
            <h3>Customer Create</h3>
            <form method="post">
                <input type="hidden" name="action" value="create_customer">
                <label>UID: <input type="text" name="customer_uid" required></label><br>
                <label>First Name: <input type="text" name="customer_first_name" required></label><br>
                <label>Second Name: <input type="text" name="customer_second_name" required></label><br>
                <label>Address: <input type="text" name="customer_address" required></label><br>
                <label>Permit Number: <input type="text" name="customer_permit_number" required></label><br>
                <button type="submit">Create Customer</button>
            </form>
            <h3>Customer Update</h3>
            <form method="post">
                <input type="hidden" name="action" value="update_customer">
                <label>UID: <input type="text" name="customer_uid" required></label><br>
                <label>First Name: <input type="text" name="customer_first_name" required></label><br>
                <label>Second Name: <input type="text" name="customer_second_name" required></label><br>
                <label>Address: <input type="text" name="customer_address" required></label><br>
                <label>Permit Number: <input type="text" name="customer_permit_number" required></label><br>
                <button type="submit">Update Customer</button>
            </form>
            <h3>Customer Delete</h3>
            <form method="post">
                <input type="hidden" name="action" value="delete_customer">
                <label>UID: <input type="text" name="customer_uid" required></label><br>
                <button type="submit">Delete Customer</button>
            </form>
        </div>
        <div class="column2">
            <h2>Customers Table</h2>
            <table>
                <thead>
                    <tr>
                        <th>UID</th>
                        <th>First Name</th>
                        <th>Second Name</th>
                        <th>Address</th>
                        <th>Permit Number</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $customer): ?>
                        <tr>
                            <td><?php echo $customer->getUid(); ?></td>
                            <td><?php echo $customer->getFirstName(); ?></td>
                            <td><?php echo $customer->getSecondName(); ?></td>
                            <td><?php echo $customer->getAddress(); ?></td>
                            <td><?php echo $customer->getPermitNumber(); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="container">
        <div class="column1">
            <h3>Get Customer by Name</h3>
            <form method="post">
                <input type="hidden" name="action" value="get_customer">
                <label>First Name: <input type="text" name="first_name" required></label><br>
                <label>Second Name: <input type="text" name="second_name" required></label><br>
                <button type="submit">Get Customer</button>
            </form>
        </div>
        <div class="column2">
            <?php if (isset($getCustomer)): ?>
            <h2>Customer Table</h2>
            <h3>Result:</h3>
            <p>ID: <?php echo $getCustomer->getUid(); ?></p>
            <p>First Name: <?php echo $getCustomer->getFirstName(); ?></p>
            <p>Second Name: <?php echo $getCustomer->getSecondName(); ?></p>
            <p>Address: <?php echo $getCustomer->getAddress(); ?></p>
            <p>Permit Number: <?php echo $getCustomer->getPermitNumber(); ?></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>