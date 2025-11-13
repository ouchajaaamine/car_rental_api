<?php

namespace App\DataFixtures;

use App\Entity\Car;
use App\Entity\Reservation;
use App\Entity\User;
use App\Enum\FuelType;
use App\Enum\ReservationStatus;
use App\Enum\TransmissionType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Create cars
        $cars = [
            [
                'brand' => 'Dacia',
                'model' => 'Logan 2023',
                'dailyFee' => '250.00',
                'inventory' => 8,
                'seats' => 5,
                'transmission' => TransmissionType::MANUAL,
                'fuelType' => FuelType::GASOLINE,
            ],
            [
                'brand' => 'Dacia',
                'model' => 'Sandero Stepway',
                'dailyFee' => '300.00',
                'inventory' => 5,
                'seats' => 5,
                'transmission' => TransmissionType::MANUAL,
                'fuelType' => FuelType::DIESEL,
            ],
            [
                'brand' => 'Renault',
                'model' => 'Clio 5',
                'dailyFee' => '350.00',
                'inventory' => 6,
                'seats' => 5,
                'transmission' => TransmissionType::AUTOMATIC,
                'fuelType' => FuelType::GASOLINE,
            ],
            [
                'brand' => 'Peugeot',
                'model' => '208 Automatique',
                'dailyFee' => '380.00',
                'inventory' => 4,
                'seats' => 5,
                'transmission' => TransmissionType::AUTOMATIC,
                'fuelType' => FuelType::GASOLINE,
            ],
            [
                'brand' => 'Hyundai',
                'model' => 'Tucson',
                'dailyFee' => '550.00',
                'inventory' => 3,
                'seats' => 5,
                'transmission' => TransmissionType::AUTOMATIC,
                'fuelType' => FuelType::DIESEL,
            ],
            [
                'brand' => 'Toyota',
                'model' => 'Corolla Hybrid',
                'dailyFee' => '450.00',
                'inventory' => 4,
                'seats' => 5,
                'transmission' => TransmissionType::AUTOMATIC,
                'fuelType' => FuelType::HYBRID,
            ],
            [
                'brand' => 'Volkswagen',
                'model' => 'Golf 8',
                'dailyFee' => '420.00',
                'inventory' => 5,
                'seats' => 5,
                'transmission' => TransmissionType::AUTOMATIC,
                'fuelType' => FuelType::DIESEL,
            ],
            [
                'brand' => 'Nissan',
                'model' => 'Qashqai',
                'dailyFee' => '500.00',
                'inventory' => 3,
                'seats' => 5,
                'transmission' => TransmissionType::AUTOMATIC,
                'fuelType' => FuelType::GASOLINE,
            ],
            [
                'brand' => 'Renault',
                'model' => 'Megane E-Tech',
                'dailyFee' => '600.00',
                'inventory' => 2,
                'seats' => 5,
                'transmission' => TransmissionType::AUTOMATIC,
                'fuelType' => FuelType::ELECTRIC,
            ],
            [
                'brand' => 'Mercedes',
                'model' => 'Classe A',
                'dailyFee' => '700.00',
                'inventory' => 2,
                'seats' => 5,
                'transmission' => TransmissionType::AUTOMATIC,
                'fuelType' => FuelType::DIESEL,
            ],
        ];

        $carEntities = [];
        foreach ($cars as $carData) {
            $car = new Car();
            $car->setBrand($carData['brand'])
                ->setModel($carData['model'])
                ->setDailyFee($carData['dailyFee'])
                ->setInventory($carData['inventory'])
                ->setSeats($carData['seats'])
                ->setTransmission($carData['transmission'])
                ->setFuelType($carData['fuelType']);

            $manager->persist($car);
            $carEntities[] = $car;
        }

        $manager->flush();

        // Create reservations for customers 
        $customer1 = $this->getReference('customer1-user', User::class);
        $customer2 = $this->getReference('customer2-user', User::class);

        // Reservation 1: Customer 1 - Dacia Logan (Active)
        $reservation1 = new Reservation();
        $reservation1->setUser($customer1)
            ->setCar($carEntities[0]) // Dacia Logan
            ->setStartDate(new \DateTime('2025-11-20'))
            ->setEndDate(new \DateTime('2025-11-25'))
            ->setTotalDays(5)
            ->setTotalPrice('1250.00')
            ->setCustomerName('Karim Alaoui')
            ->setCustomerPhone('+212661234567')
            ->setCustomerEmail('karim.alaoui@gmail.com')
            ->setDriverLicenseNumber('B234567')
            ->setStatus(ReservationStatus::ACTIVE);

        $manager->persist($reservation1);

        // Reservation 2: Customer 2 - Renault Clio (Active)
        $reservation2 = new Reservation();
        $reservation2->setUser($customer2)
            ->setCar($carEntities[2]) // Renault Clio
            ->setStartDate(new \DateTime('2025-11-18'))
            ->setEndDate(new \DateTime('2025-11-22'))
            ->setTotalDays(4)
            ->setTotalPrice('1400.00')
            ->setCustomerName('Fatima Idrissi')
            ->setCustomerPhone('+212662345678')
            ->setCustomerEmail('fatima.idrissi@gmail.com')
            ->setDriverLicenseNumber('B345678')
            ->setStatus(ReservationStatus::ACTIVE);

        $manager->persist($reservation2);

        // Reservation 3: Customer 1 - Hyundai Tucson (Returned)
        $reservation3 = new Reservation();
        $reservation3->setUser($customer1)
            ->setCar($carEntities[4]) // Hyundai Tucson
            ->setStartDate(new \DateTime('2025-11-01'))
            ->setEndDate(new \DateTime('2025-11-05'))
            ->setActualReturnDate(new \DateTime('2025-11-05'))
            ->setTotalDays(4)
            ->setTotalPrice('2200.00')
            ->setCustomerName('Karim Alaoui')
            ->setCustomerPhone('+212661234567')
            ->setCustomerEmail('karim.alaoui@gmail.com')
            ->setDriverLicenseNumber('B234567')
            ->setStatus(ReservationStatus::RETURNED);

        $manager->persist($reservation3);

        // Reservation 4: Customer 2 - Toyota Corolla Hybrid (Returned)
        $reservation4 = new Reservation();
        $reservation4->setUser($customer2)
            ->setCar($carEntities[5]) // Toyota Corolla Hybrid
            ->setStartDate(new \DateTime('2025-10-28'))
            ->setEndDate(new \DateTime('2025-11-02'))
            ->setActualReturnDate(new \DateTime('2025-11-02'))
            ->setTotalDays(5)
            ->setTotalPrice('2250.00')
            ->setCustomerName('Fatima Idrissi')
            ->setCustomerPhone('+212662345678')
            ->setCustomerEmail('fatima.idrissi@gmail.com')
            ->setDriverLicenseNumber('B345678')
            ->setStatus(ReservationStatus::RETURNED);

        $manager->persist($reservation4);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
