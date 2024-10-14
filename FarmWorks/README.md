# FarmWorks Installation and Security Scripts

This repository contains two Python scripts designed to simplify the installation and security configuration of farmOS using Docker.

## FarmWorks_Install.py

This script automates the process of setting up farmOS locally using Docker and Docker Compose.

### Features:

- Checks for Docker and Docker Compose installation
- Creates necessary directories for farmOS
- Generates `docker-compose.yml` and `php.ini` files
- Manages Docker containers (stops existing ones and starts new ones)
- Sets up initial permissions for farmOS files
- Provides database information for browser setup

### Usage:

1. Ensure you have Docker and Docker Compose installed on your system.
2. Place the `config.php` file with your database configuration in the same directory as the script or in the `FarmWorks` directory.
3. Run the script:

   ```
   python3 FarmWorks_Install.py
   ```

4. Follow the on-screen instructions to complete the installation through the web interface.

### Notes:

- The script will provide a local URL to access farmOS after setup.
- Database information for browser setup will be displayed at the end of the script execution.
- It's recommended to run this script as a non-root user with Docker permissions.
- You will likely want to add your username to a Docker group, so that the script can be run without sudo. 

## FarmWorks_Security.py

This script secures the permissions of farmOS files and directories after installation.

### Features:

- Sets appropriate permissions for the `sites/default` directory
- Secures the `settings.php` file
- Configures permissions for the `files` directory
- Changes ownership of the `files` directory to the web server user

### Usage:

This script is automatically called at the end of the `FarmWorks_Install.py` script. However, you can also run it separately:

