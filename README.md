# Sales Challenge Project

## Setup Instructions

Follow the instructions below to set up the project environment and run the application.

1. Install Docker on your machine if you haven't already. You can download it from [Docker's official website](https://www.docker.com/get-started).
1. Clone the repository to your local machine. You can use the following command:
   ```bash
    # SSH
    git clone git@github.com:diegosurita/sales-challenge.git

    # HTTPS
    git clone https://github.com/diegosurita/sales-challenge.git
   ```
1. Navigate to the project directory:
   ```bash
   cd sales-challenge
   ```
1. Since this project uses Docker Compose, you can start the application using the following command:
   ```bash
   docker-compose up -d
   ```
   This command will build and start the necessary containers for the application.
1. Once the containers are up and running, you can access the application by opening your web browser and navigating to `http://localhost`.