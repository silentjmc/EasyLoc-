<?php
//require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/database/mongodb_connection.php';
require_once __DIR__ . '/src/database/mysql_connection.php';
require_once __DIR__ . '/src/customer/customer_crud.php';
require_once __DIR__ . '/src/vehicle/vehicle_crud.php';
require_once __DIR__ . '/src/contract/contract_crud.php';
require_once __DIR__ . '/src/billing/billing_crud.php';

$mongodbConnection = new MongodbConnection();
$mysqlConnection = new MysqlConnection();

$customerCrud = new CustomerCrud($mongodbConnection);
$vehicleCrud = new VehicleCrud($mongodbConnection);
$contractCrud = new ContractCrud($mysqlConnection->getPdo());
$billingCrud = new BillingCrud($mysqlConnection->getPdo());

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $action = $_POST['action'] ?? '';
        switch ($action) {
            case 'create_billing':
                $billing = new Billing([
                    'contract_id' => $_POST['billing_contract_id'],
                    'amount' => $_POST['billing_amount']
                ]);
                $billingCrud->createBilling($billing);
                $message = 'Billing created successfully!';
                break;
            case 'update_billing':
                $billing = new Billing([
                    'id' => $_POST['billing_id'],
                    'contract_id' => $_POST['billing_contract_id'],
                    'amount' => $_POST['billing_amount']
                ]);
                $billingCrud->updateBilling($billing);
                $message = 'Billing updated successfully!';
                break;
            case 'delete_billing':
                $billingCrud->deleteBilling($_POST['billing_id']);
                $message = 'Billing deleted successfully!';
                break;
            case 'get_billing_by_id':
                $billingById = $billingCrud->getBillingById($_POST['billing_id']);
                $message = 'Billing retrieved successfully!';
                break;
            case 'get_payments_by_contract_id':
                $payments = $billingCrud->getPaymentsByContractId($_POST['contract_id']);
                $message = 'Payments retrieved successfully!';
                break;
            case 'is_contract_fully_paid':
                $isFullyPaid = $billingCrud->isContractFullyPaid($_POST['contract_id']);
                $message = 'Contract fully paid: ' . ($isFullyPaid ? 'Yes' : 'No');
                break;
            case 'get_unpaid_contract':
                $unpaidContracts = $billingCrud->getUnpaidContract();
                $message = 'Unpaid contracts retrieved successfully!';
                break;
            default:
                $message = 'Invalid action!';
                break;
        }
    } catch (Exception $e) {
        $message = 'Error: ' . $e->getMessage();
    }
}

$billings = $billingCrud->getAllBillings();
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
                <h2>Billing</h2>
                <h3>Billing Create</h3>
                <form method="post">
                    <input type="hidden" name="action" value="create_billing">
                    <label>Contract ID: <input type="number" name="billing_contract_id" required></label><br>
                    <label>Amount: <input type="number" step="0.01" name="billing_amount" required></label><br>
                    <button type="submit">Create Billing</button>
                </form>
                <h3>Billing Update</h3>
                <form method="post">
                    <input type="hidden" name="action" value="update_billing">
                    <label>ID: <input type="number" name="billing_id" required></label><br>
                    <label>Contract ID: <input type="number" name="billing_contract_id" required></label><br>
                    <label>Amount: <input type="number" step="0.01" name="billing_amount" required></label><br>
                    <button type="submit">Update Billing</button>
                </form>
                <h3>Billing Delete</h3>
                <form method="post">
                    <input type="hidden" name="action" value="delete_billing">
                    <label>ID: <input type="number" name="billing_id" required></label><br>
                    <button type="submit">Delete Billing</button>
                </form>
            </div>
            <div class="column2">
                <h2>Billings Table</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Contract ID</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($billings as $billing): ?>
                            <tr>
                                <td><?php echo $billing->getId(); ?></td>
                                <td><?php echo $billing->getContractId(); ?></td>
                                <td><?php echo $billing->getAmount(); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="container">
            <div class="column1">
                <h3>Get Billing by ID</h3>
                <form method="post">
                    <input type="hidden" name="action" value="get_billing_by_id">
                    <label>ID: <input type="number" name="billing_id" required></label><br>
                    <button type="submit">Get Billing</button>
                </form>
            </div>
            <div class="column2">
                <?php if (isset($billingById)): ?>
                <h3>Result:</h3>
                <p>ID: <?php echo $billingById->getId(); ?></p>
                <p>Contract UID: <?php echo $billingById->getContractId(); ?></p>
                <p>Price: <?php echo $billingById->getAmount(); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <div class="container">
            <div class="column1">
            <h3>Get Payments by Contract ID</h3>
                <form method="post">
                    <input type="hidden" name="action" value="get_payments_by_contract_id">
                    <label>Contract ID: <input type="number" name="contract_id" required></label><br>
                    <button type="submit">Get Payments</button>
                </form>
            </div>
            <div class="column2">
                <?php if (isset($payments)): ?>
                <h3>Result:</h3>
                <ul>
                    <?php foreach ($payments as $payment): ?>
                        <li><?php echo $payment->getAmount(); ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </div>
        </div>
        <div class="container">
            <div class="column1">
                <h3>Is Contract Fully Paid?</h3>
                <form method="post">
                    <input type="hidden" name="action" value="is_contract_fully_paid">
                    <label>Contract ID: <input type="number" name="contract_id" required></label><br>
                    <button type="submit">Check</button>
                </form>
            </div>
            <div class="column2">
                <?php if (isset($isFullyPaid)): ?>
                <h3>Result:</h3>
                <p><?php echo $isFullyPaid; ?></p>
                <?php endif; ?>
            </div>
        </div>
        <div class="container">
            <div class="column1">
                <h3>Get Unpaid Contract</h3>
                <form method="post">
                    <input type="hidden" name="action" value="get_unpaid_contract">
                    <button type="submit">Get Unpaid Contract</button>
                </form>
            </div>
            <div class="column2">
                <?php if (isset($unpaidContracts)): ?>
                <h3>Result:</h3>
                <ul>
                    <?php foreach ($unpaidContracts as $contract): ?>
                        <li><?php echo $contract->getId(); ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </div>
</body>
</html>