<?php
require_once __DIR__ . '/src/database/mysql_connection.php';
require_once __DIR__ . '/src/contract/contract_crud.php';
$mysqlConnection = new MysqlConnection();

$contractCrud = new ContractCrud($mysqlConnection->getPdo());

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $action = $_POST['action'] ?? '';
        switch ($action) {
            case 'create_contract':
                $contract = new Contract([
                    'vehicle_uid' => $_POST['contract_vehicle_uid'],
                    'customer_uid' => $_POST['contract_customer_uid'],
                    'sign_datetime' => $_POST['contract_sign_datetime'],
                    'loc_begin_datetime' => $_POST['contract_loc_begin_datetime'],
                    'loc_end_datetime' => $_POST['contract_loc_end_datetime'],
                    'returning_datetime' => $_POST['contract_returning_datetime'],
                    'price' => $_POST['contract_price']
                ]);
                $contractCrud->createContract($contract);
                $message = 'Contract created successfully!';
                break;
            case 'update_contract':
                $contract = new Contract([
                    'id' => $_POST['contract_id'],
                    'vehicle_uid' => $_POST['contract_vehicle_uid'],
                    'customer_uid' => $_POST['contract_customer_uid'],
                    'sign_datetime' => $_POST['contract_sign_datetime'],
                    'loc_begin_datetime' => $_POST['contract_loc_begin_datetime'],
                    'loc_end_datetime' => $_POST['contract_loc_end_datetime'],
                    'returning_datetime' => $_POST['contract_returning_datetime'],
                    'price' => $_POST['contract_price']
                ]);
                $contractCrud->updateContract($contract);
                $message = 'Contract updated successfully!';
                break;
            case 'delete_contract':
                $contractCrud->deleteContract($_POST['contract_id']);
                $message = 'Contract deleted successfully!';
                break;
            case 'find_contract_by_id':
                $foundContractById = $contractCrud->findContractById($_POST['contract_id']);
                $message = 'Contract found successfully!';
                break;
            case 'get_contracts_by_customer_uid':
                $foundContractsByCustomerUid = $contractCrud->getContractsByCustomerUid($_POST['customer_uid']);
                $message = 'Contracts by customer Uid found successfully!';
                break;
            case 'get_contracts_by_vehicle_uid':
                $foundContractsByVehicleUid = $contractCrud->getContractsByVehicleUid($_POST['vehicle_uid']);
                $message = 'Contracts by vehicle Uid found successfully!';
                break;
            case 'get_contracts_grouped_by_vehicles':
                $contractsGroupedByVehicles = $contractCrud->getContractsGroupedByVehicleUid();
                $message = 'Contracts grouped by vehicles  found successfully!';
                break;
            case 'get_contracts_grouped_by_customers':
                $contractsGroupedByCustomers = $contractCrud->getContractsGroupedByCustomerUid();
                $message = 'Contracts grouped by customers  found successfully!';
                break;
            case 'get_ongoing_rentals_by_customer_uid':
                $ongoingRentalsByCustomerUid = $contractCrud->getOngoingRentalsByCustomerUid($_POST['customer_uid']);
                $message = 'Ongoing rentals by customer Uid found successfully!';
                break;
            case 'get_total_overdue_rents_between_dates':
                $startDate = new DateTime($_POST['begin_date']);
                $endDate = new DateTime($_POST['end_date']); 
                $totalOverdueRentsBetweenDates = $contractCrud->getTotalOverdueRentalsBetweenDates($startDate, $endDate);
                $message = 'Total overdue rents found successfully!';
                break;
            case 'get_average_overdue_rentals_by_customer':
                $averageOverdueRentalsByCustomer = $contractCrud->getAverageOverdueRentalsByCustomer();
                $message = 'Average overdue rentals by customer found successfully!';
                break;
            case 'get_overdue_rentals':
                $overdueRentals = $contractCrud->getOverdueRentals();
                $message = 'Overdue rentals found successfully!';
                break; 
            case 'get_average_time_overdue_by_vehicle':
                $averageTimeOverdueByVehicle = $contractCrud->getAverageTimeOverdueByVehicle();
                $message = 'Average time overdue by vehicle found successfully!';
                break;
            default:
                $message = 'Invalid action!';
                break;
        }
    } catch (Exception $e) {
        $message = 'Error: ' . $e->getMessage();
    }
}

$contracts = $contractCrud->getAllContracts();
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
            <h2>Contract</h2>
            <h3>Create contract</h3>
            <form method="post">
                <input type="hidden" name="action" value="create_contract">
                <label>Vehicle UID: <input type="text" name="contract_vehicle_uid" required></label><br>
                <label>Customer UID: <input type="text" name="contract_customer_uid" required></label><br>
                <label>Sign DateTime: <input type="datetime-local" name="contract_sign_datetime" required></label><br>
                <label>Begin DateTime: <input type="datetime-local" name="contract_loc_begin_datetime" required></label><br>
                <label>End DateTime: <input type="datetime-local" name="contract_loc_end_datetime" required></label><br>
                <label>Returning DateTime: <input type="datetime-local" name="contract_returning_datetime" required></label><br>
                <label>Price: <input type="number" step="0.01" name="contract_price" required></label><br>
                <button type="submit">Create Contract</button>
            </form>
            <h3>Update contract</h3>
            <form method="post">
                <input type="hidden" name="action" value="update_contract">
                <label>Contract ID <input type="text" name="contract_id" required></label><br>
                <label>Vehicle UID: <input type="text" name="contract_vehicle_uid" required></label><br>
                <label>Customer UID: <input type="text" name="contract_customer_uid" required></label><br>
                <label>Sign DateTime: <input type="datetime-local" name="contract_sign_datetime" required></label><br>
                <label>Begin DateTime: <input type="datetime-local" name="contract_loc_begin_datetime" required></label><br>
                <label>End DateTime: <input type="datetime-local" name="contract_loc_end_datetime" required></label><br>
                <label>Returning DateTime: <input type="datetime-local" name="contract_returning_datetime" required></label><br>
                <label>Price: <input type="number" step="0.01" name="contract_price" required></label><br>
                <button type="submit">Update Contract</button>
            </form>
            <h3>Delete contract</h3>
            <form method="post">
                <input type="hidden" name="action" value="delete_contract">
                <label>Contract ID <input type="text" name="contract_id" required></label><br>
                <button type="submit">Delete Contract</button>
            </form>
        </div>
        <div class="column2">
            <h2>Contracts Table</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Vehicle UID</th>
                        <th>Customer UID</th>
                        <th>Sign DateTime</th>
                        <th>Begin DateTime</th>
                        <th>End DateTime</th>
                        <th>Returning DateTime</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contracts as $contract): ?>
                        <tr>
                            <td><?php echo $contract->getId(); ?></td>
                            <td><?php echo $contract->getVehicleUid(); ?></td>
                            <td><?php echo $contract->getCustomerUid(); ?></td>
                            <td><?php echo $contract->getSignDatetime()->format('Y-m-d H:i:s'); ?></td>
                            <td><?php echo $contract->getLocBeginDatetime()->format('Y-m-d H:i:s'); ?></td>
                            <td><?php echo $contract->getLocEndDatetime()->format('Y-m-d H:i:s'); ?></td>
                            <td><?php echo $contract->getReturningDatetime() ? $contract->getReturningDatetime()->format('Y-m-d H:i:s') : ''; ?></td>
                            <td><?php echo $contract->getPrice(); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="container">
        <div class="column1">
        <h3>Find Contract by ID</h3>
        <form method="post">
                <input type="hidden" name="action" value="find_contract_by_id">
                <label>Contract ID: <input type="number" name="contract_id" required></label><br>
                <button type="submit">Find Contract</button>
            </form>
        </div>
        <div class="column2">
            <?php if (isset($foundContractById)): ?>
            <h3>Result:</h3>
            <p>ID: <?php echo $foundContractById->getId(); ?></p>
            <p>Vehicle UID: <?php echo $foundContractById->getVehicleUid(); ?></p>
            <p>Customer UID: <?php echo $foundContractById->getCustomerUid(); ?></p>
            <p>Price: <?php echo $foundContractById->getPrice(); ?></p>
            <?php endif; ?>
        </div>
    </div>
    <div class="container">
        <div class="column1">
        <h3>Get Contracts by Customer UID</h3>
        <form method="post">
                <input type="hidden" name="action" value="get_contracts_by_customer_uid">
                <label>Customer UID: <input type="text" name="customer_uid" required></label><br>
                <button type="submit">Get Contracts</button>
            </form>
        </div>
        <div class="column2">
            <?php if (isset($foundContractsByCustomerUid)): ?>
            <h3>Result:</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Vehicle UID</th>
                        <th>Customer UID</th>
                        <th>Sign DateTime</th>
                        <th>Begin DateTime</th>
                        <th>End DateTime</th>
                        <th>Returning DateTime</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($foundContractsByCustomerUid as $contract): ?>
                        <tr>
                            <td><?php echo $contract->getId(); ?></td>
                            <td><?php echo $contract->getVehicleUid(); ?></td>
                            <td><?php echo $contract->getCustomerUid(); ?></td>
                            <td><?php echo $contract->getSignDatetime()->format('Y-m-d H:i:s'); ?></td>
                            <td><?php echo $contract->getLocBeginDatetime()->format('Y-m-d H:i:s'); ?></td>
                            <td><?php echo $contract->getLocEndDatetime()->format('Y-m-d H:i:s'); ?></td>
                            <td><?php echo $contract->getReturningDatetime() ? $contract->getReturningDatetime()->format('Y-m-d H:i:s') : ''; ?></td>
                            <td><?php echo $contract->getPrice(); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
    <div class="container">
        <div class="column1">
        <h3>Get Contracts by Vehicle UID</h3>
        <form method="post">
                <input type="hidden" name="action" value="get_contracts_by_vehicle_uid">
                <label>Vehicle UID: <input type="text" name="vehicle_uid" required></label><br>
                <button type="submit">Get Contracts</button>
            </form>
        </div>
        <div class="column2">
            <?php if (isset($foundContractsByVehicleUid)): ?>
            <h3>Result:</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Vehicle UID</th>
                        <th>Customer UID</th>
                        <th>Sign DateTime</th>
                        <th>Begin DateTime</th>
                        <th>End DateTime</th>
                        <th>Returning DateTime</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($foundContractsByVehicleUid as $contract): ?>
                        <tr>
                            <td><?php echo $contract->getId(); ?></td>
                            <td><?php echo $contract->getVehicleUid(); ?></td>
                            <td><?php echo $contract->getCustomerUid(); ?></td>
                            <td><?php echo $contract->getSignDatetime()->format('Y-m-d H:i:s'); ?></td>
                            <td><?php echo $contract->getLocBeginDatetime()->format('Y-m-d H:i:s'); ?></td>
                            <td><?php echo $contract->getLocEndDatetime()->format('Y-m-d H:i:s'); ?></td>
                            <td><?php echo $contract->getReturningDatetime() ? $contract->getReturningDatetime()->format('Y-m-d H:i:s') : ''; ?></td>
                            <td><?php echo $contract->getPrice(); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
    <div class="container">        
        <div class="column1">
        <h3>Get Contracts grouped by Vehicles</h3>
            <form method="post">
                <input type="hidden" name="action" value="get_contracts_grouped_by_vehicles">
                <button type="submit">Get Contracts</button>
            </form>
        </div>
        <div class="column2">
            <?php if (isset($contractsGroupedByVehicles)): ?>
            <h3>Result:</h3>
            <table>
            <thead>
                <tr>
                    <th>Vehicle UID</th>
                    <th>Contracts</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contractsGroupedByVehicles as $vehicleUid => $contracts): ?>
                    <tr>
                        <td><?php echo $vehicleUid; ?></td>
                        <td>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Contract ID</th>
                                        <th>Customer UID</th>
                                        <th>Sign DateTime</th>
                                        <th>Begin DateTime</th>
                                        <th>End DateTime</th>
                                        <th>Returning DateTime</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($contracts as $contract): ?>
                                        <tr>
                                            <td><?php echo $contract->getId(); ?></td>
                                            <td><?php echo $contract->getCustomerUid(); ?></td>
                                            <td><?php echo $contract->getSignDatetime()->format('Y-m-d H:i:s'); ?></td>
                                            <td><?php echo $contract->getLocBeginDatetime()->format('Y-m-d H:i:s'); ?></td>
                                            <td><?php echo $contract->getLocEndDatetime()->format('Y-m-d H:i:s'); ?></td>
                                            <td><?php echo $contract->getReturningDatetime() ? $contract->getReturningDatetime()->format('Y-m-d H:i:s') : 'N/A'; ?></td>
                                            <td><?php echo $contract->getPrice(); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
            <?php endif; ?>
        </div>
    </div>
    <div class="container">
        <div class="column1">
        <h3>Get Contracts grouped by Customers</h3>
            <form method="post">
                <input type="hidden" name="action" value="get_contracts_grouped_by_customers">
                <button type="submit">Get Contracts</button>
            </form>
        </div>
        <div class="column2">
            <?php if (isset($contractsGroupedByCustomers)): ?>
            <h3>Result:</h3>
            <table>
            <thead>
                <tr>
                    <th>Customer UID</th>
                    <th>Contracts</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contractsGroupedByCustomers as $customerUid => $contracts): ?>
                    <tr>
                        <td><?php echo $customerUid; ?></td>
                        <td>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Contract ID</th>
                                        <th>Vehicle UID</th>
                                        <th>Sign DateTime</th>
                                        <th>Begin DateTime</th>
                                        <th>End DateTime</th>
                                        <th>Returning DateTime</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($contracts as $contract): ?>
                                        <tr>
                                            <td><?php echo $contract->getId(); ?></td>
                                            <td><?php echo $contract->getVehicleUid(); ?></td>
                                            <td><?php echo $contract->getSignDatetime()->format('Y-m-d H:i:s'); ?></td>
                                            <td><?php echo $contract->getLocBeginDatetime()->format('Y-m-d H:i:s'); ?></td>
                                            <td><?php echo $contract->getLocEndDatetime()->format('Y-m-d H:i:s'); ?></td>
                                            <td><?php echo $contract->getReturningDatetime() ? $contract->getReturningDatetime()->format('Y-m-d H:i:s') : 'N/A'; ?></td>
                                            <td><?php echo $contract->getPrice(); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
            <?php endif; ?>
        </div>
    </div>
    <div class="container">
        <div class="column1">
        <h3>Get Ongoing Rentals by Customer UID</h3>
            <form method="post">
                <input type="hidden" name="action" value="get_ongoing_rentals_by_customer_uid">
                <label>Customer UID: <input type="text" name="customer_uid" required></label><br>
                <button type="submit">Get Rentals</button>
            </form>
        </div>
        <div class="column2">
            <?php if (isset($ongoingRentalsByCustomerUid)): ?>
            <h3>Result:</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Vehicle UID</th>
                        <th>Customer UID</th>
                        <th>Sign DateTime</th>
                        <th>Begin DateTime</th>
                        <th>End DateTime</th>
                        <th>Returning DateTime</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ongoingRentalsByCustomerUid as $contract): ?>
                        <tr>
                            <td><?php echo $contract->getId(); ?></td>
                            <td><?php echo $contract->getVehicleUid(); ?></td>
                            <td><?php echo $contract->getCustomerUid(); ?></td>
                            <td><?php echo $contract->getSignDatetime()->format('Y-m-d H:i:s'); ?></td>
                            <td><?php echo $contract->getLocBeginDatetime()->format('Y-m-d H:i:s'); ?></td>
                            <td><?php echo $contract->getLocEndDatetime()->format('Y-m-d H:i:s'); ?></td>
                            <td><?php echo $contract->getReturningDatetime() ? $contract->getReturningDatetime()->format('Y-m-d H:i:s') : ''; ?></td>
                            <td><?php echo $contract->getPrice(); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
    <div class="container">
        <div class="column1">
        <h3>Get Total Overdue Rentals Between Dates</h3>
        <form method="post">
                <input type="hidden" name="action" value="get_total_overdue_rents_between_dates">
                <label>Begin Date: <input type="date" name="begin_date" required></label><br>
                <label>End Date: <input type="date" name="end_date" required></label><br>
                <button type="submit">Get Total Overdue Rents</button>
            </form>
        </div>
        <div class="column2">
            <?php if (isset($totalOverdueRentsBetweenDates)): ?>
            <h3>Result:</h3>
            <p>Total Overdue Rents: <?php echo $totalOverdueRentsBetweenDates; ?></p>
            <?php endif; ?>
        </div>
    </div>
    <div class="container">
        <div class="column1">
        <h3>Get Average Overdue Rentals by Customer</h3>
            <form method="post">
                <input type="hidden" name="action" value="get_average_overdue_rentals_by_customer">
                <button type="submit">Get Average Overdue Rents</button>
            </form>
        </div>
        <div class="column2">
        <?php if (isset($averageOverdueRentalsByCustomer) && is_array($averageOverdueRentalsByCustomer)): ?>
            <h3>Result:</h3>
            <table>
                <thead>
                    <tr>
                        <th>Customer UID</th>
                        <th>Average Overdue Ratio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($averageOverdueRentalsByCustomer as $row): ?>
                        <tr>
                            <td><?php echo $row['customer_uid']; ?></td>
                            <td><?php echo $row['overdue_ratio']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
    <div class="container">
    <div class="column1">
        <h3>Get overdue rentals</h3>
        <form method="post">
                <input type="hidden" name="action" value="get_overdue_rentals">
                <button type="submit">Get Overdue Rentals</button>
            </form>
        </div>
        <div class="column2">
            <?php if (isset($overdueRentals)): ?>
            <h3>Result:</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Vehicle UID</th>
                        <th>Customer UID</th>
                        <th>Sign DateTime</th>
                        <th>Begin DateTime</th>
                        <th>End DateTime</th>
                        <th>Returning DateTime</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($overdueRentals as $contract): ?>
                        <tr>
                            <td><?php echo $contract->getId(); ?></td>
                            <td><?php echo $contract->getVehicleUid(); ?></td>
                            <td><?php echo $contract->getCustomerUid(); ?></td>
                            <td><?php echo $contract->getSignDatetime()->format('Y-m-d H:i:s'); ?></td>
                            <td><?php echo $contract->getLocBeginDatetime()->format('Y-m-d H:i:s'); ?></td>
                            <td><?php echo $contract->getLocEndDatetime()->format('Y-m-d H:i:s'); ?></td>
                            <td><?php echo $contract->getReturningDatetime() ? $contract->getReturningDatetime()->format('Y-m-d H:i:s') : ''; ?></td>
                            <td><?php echo $contract->getPrice(); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
    <div class="container">
        <div class="column1">
        <h3>Get Average Time Overdue By Vehicle</h3>
            <form method="post">
                <input type="hidden" name="action" value="get_average_time_overdue_by_vehicle">
                <button type="submit">Get Average Time Overdue</button>
            </form>
        </div>
        <div class="column2">
        <?php if (isset($averageTimeOverdueByVehicle)): ?>
            <h3>Result:</h3>
            <table>
                <thead>
                    <tr>
                        <th>Customer UID</th>
                        <th>Average Overdue Ratio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($averageTimeOverdueByVehicle as $row): ?>
                        <tr>
                            <td><?php echo ($row['vehicle_uid']); ?></td>
                            <td><?php echo ($row['average_time_overdue']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        </div>
    </div>
</body>
</html>