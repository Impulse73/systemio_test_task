# Test project for systemio

## Demo data
The test task is implemented through interaction with the database. It is necessary to perform migrations so that tables are created and filled with demo data to check the functionality of the test task.
>  php doctrine:migrations:migrate

## Payment Processor
Payment processors are located src/Payment.

## Payment Adapter
To unify work with payment system processors, an adapter class was created - src/Payment/Adapter
