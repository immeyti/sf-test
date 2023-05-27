# Project Setup

This guide will help you set up and run a the project using Docker.

## Prerequisites

Make sure you have the following installed on your machine:

- Docker: [https://www.docker.com/](https://www.docker.com/)

## Installation

1. Clone the repository:

   ```shell
   git clone <repository-url>
   cd <project-folder>

2. Run the following command to install the project dependencies using Composer:
   
    ```shell
   docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs

This command runs a Composer container that installs the project dependencies. It mounts the current directory ($(pwd)) to the /var/www/html directory in the container.

3. Start the Docker containers:
    ```sell
   ./vendor/bin/sail up -d
   

4. Access the Laravel application:

Open your web browser and visit http://localhost to access the Laravel application.

5. Run Tests:
    ```sell
   ./vendor/bin/sail test
