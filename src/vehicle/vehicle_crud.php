<?php
require_once __DIR__ . '/../database/mongodb_connection.php';
require_once __DIR__ . '/vehicle_entity.php';

use MongoDB\Collection;

class VehicleCrud {
    private Collection $collection;

    /**
     * * VehicleCrud constructor.
     * 
     * * Initializes a connection to the MongoDB database and selects the 'Vehicle' collection.
     * * @param MongodbConnection $connection The MongoDB connection object.
     */
    public function __construct(MongodbConnection $connection) {
        $database = $connection->getDatabase();
        $this->collection = $database->selectCollection('Vehicle');
    }
    
    /**
     * * Converts a Vehicle object into an associative array that can be inserted into MongoDB.
     * 
     * * @param Vehicle $vehicle The Vehicle object to convert.
     * * @return array The vehicle data as an associative array.
     */
    private function toMongoDocument(Vehicle $vehicle): array {
        return [
            'uid' => $vehicle->getUid(),
            'licence_plate' => $vehicle->getLicencePlate(),
            'informations' => $vehicle->getInformations(),
            'km' => $vehicle->getKm()
        ];
    }

    /**
     * * Creates a new vehicle in the MongoDB collection.
     * 
     * * * @param Vehicle $vehicle The Vehicle object to create.
     * * @return string The UID of the newly added vehicle.
     */
    public function createVehicle(Vehicle $vehicle): string {  
        $document = $this->toMongoDocument($vehicle);
        $result = $this->collection->insertOne($document);
        
        if ($result->getInsertedCount() === 0) {
            throw new Exception("Failed to create vehicle");
        }
        
        return $vehicle->getUid();
    }

    /**
     * * Updates an existing vehicle in the MongoDB collection.
     *  
     * * * @param Vehicle $vehicle The Vehicle object to update.
     */
    public function updateVehicle(Vehicle $vehicle): void {
        $document = $this->toMongoDocument($vehicle);
        $result = $this->collection->replaceOne(['uid' => $vehicle->getUid()], $document);
        
        if ($result->getModifiedCount() === 0) {
            throw new Exception("Failed to update vehicle");
        }
    }

    /**
     * * Deletes a vehicle from the MongoDB collection.
     * 
     * @param string $uid The UID of the vehicle to delete.
     */
    public function deleteVehicle(string $uid): void {
        $result = $this->collection->deleteOne(['uid' => $uid]);
        
        if ($result->getDeletedCount() === 0) {
            throw new Exception("Failed to delete vehicle");
        }
    }

    /**
     * * Retrieves a vehicle by its licence plate from the MongoDB collection.
     * 
     * * @param string $licence_plate The licence plate of the vehicle to retrieve.
     * * @return Vehicle The Vehicle object retrieved from the database.
     */
    public function getVehicleByLicencePlate(string $licence_plate): Vehicle {
        $vehicle = $this->collection->findOne(['licence_plate' => $licence_plate]);
        
        if ($vehicle === null) {
            throw new Exception("Vehicle not found");
        }
        
        $documentArray = $vehicle->getArrayCopy(); 

        return new Vehicle($documentArray);
    }

    /**
     * * Counts the number of vehicles with a kilometrage greater than or equal to the specified value.
     * 
     * * @param int $kilometrage The kilometrage to compare against.
     * * @return int The count of vehicles with a kilometrage greater than or equal to the specified value.
     */
    public function countVehiclesWithKmGreater(int $kilometrage): int {
        $filter = ['km' => ['$gte' => $kilometrage]];

        // Utilise la méthode countDocuments pour compter le nombre de véhicules correspondant au filtre
        $count = $this->collection->countDocuments($filter);
    
        return $count;
    }

    /**
     * * Counts the number of vehicles with a kilometrage less than or equal to the specified value.
     * 
     * * @param int $kilometrage The kilometrage to compare against.
     * * @return int The count of vehicles with a kilometrage less than or equal to the specified value.
     */
    public function countVehiclesWithKmLesser(int $kilometrage): int {
        $filter = ['km' => ['$lte' => $kilometrage]];

        // Utilise la méthode countDocuments pour compter le nombre de véhicules correspondant au filtre
        $count = $this->collection->countDocuments($filter);
    
        return $count;
    }

    /**
     * * Retrieves all vehicles from the MongoDB collection.
     * 
     * * @return array An array of Vehicle objects.
     */
    public function getAllVehicles(): array {
        $vehicles = $this->collection->find();
        $result = [];
    
        foreach ($vehicles as $vehicle) {
            $vehicleArray = $vehicle->getArrayCopy(); // Convert BSONDocument to array
            $result[] = new Vehicle($vehicleArray);
        }
    
        return $result;
    }

}