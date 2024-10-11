import os
import subprocess
import sys
import textwrap
import logging
import urllib.request
import time

# Set up logging
logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(levelname)s - %(message)s')

DOCKER_COMPOSE_CONTENT = textwrap.dedent("""
version: '3.8'

services:
  farmos:
    image: farmos/farmos:latest
    container_name: farmos
    ports:
      - "80:80"
    volumes:
      - ./sites:/opt/drupal/web/sites
      - ./php.ini:/usr/local/etc/php/conf.d/farmos.ini
    environment:
      - DRUPAL_DB_HOST=db
      - DRUPAL_DB_NAME=farmos
      - DRUPAL_DB_USER=farmos
      - DRUPAL_DB_PASSWORD=securepassword
    depends_on:
      - db

  db:
    image: postgres:13
    container_name: farmos_db
    environment:
      - POSTGRES_DB=farmos
      - POSTGRES_USER=farmos
      - POSTGRES_PASSWORD=securepassword
    volumes:
      - db_data:/var/lib/postgresql/data

volumes:
  db_data:
""")

PHP_INI_CONTENT = textwrap.dedent("""
memory_limit=256M
max_execution_time=240
max_input_time=240
max_input_vars=5000
realpath_cache_size=4096K
realpath_cache_ttl=3600
""")

def run_command(command, check=True):
    """Run a shell command and log the output."""
    logging.info(f"Running command: {command}")
    result = subprocess.run(command, shell=True, stdout=subprocess.PIPE, stderr=subprocess.PIPE, text=True)
    if result.stdout:
        logging.info(result.stdout)
    if result.stderr:
        logging.error(result.stderr)
    if check and result.returncode != 0:
        logging.error(f"Command failed with return code {result.returncode}")
        sys.exit(1)
    return result

def check_command(command):
    """Check if a command exists on the system."""
    return run_command(f"which {command}", check=False).returncode == 0

def install_docker():
    """Guide the user to install Docker."""
    logging.error("Docker is not installed. Please install Docker from https://docs.docker.com/get-docker/ and try again.")
    sys.exit(1)

def check_docker_compose():
    """Check if Docker Compose is installed (either as a standalone or Docker plugin)."""
    if check_command('docker-compose'):
        return 'docker-compose'
    elif check_command('docker') and run_command('docker compose version', check=False).returncode == 0:
        return 'docker compose'
    else:
        return None

def install_docker_compose():
    """Guide the user to install Docker Compose."""
    logging.error("Docker Compose is not installed. Please install Docker Compose from https://docs.docker.com/compose/install/ and try again.")
    sys.exit(1)

def create_directories():
    """Create necessary directories for farmOS."""
    os.makedirs('sites/default', exist_ok=True)
    os.makedirs('sites/default/files', exist_ok=True)
    logging.info("Created directories for farmOS.")

def write_file(path, content):
    """Write content to a file."""
    with open(path, 'w') as file:
        file.write(content)
    logging.info(f"Created {path}.")

def generate_docker_compose(db_name, db_user, db_pass):
    """Generate docker-compose.yml with values from config.php."""
    content = textwrap.dedent(f"""
    version: '3.8'

    services:
      farmos:
        image: farmos/farmos:latest
        container_name: farmos
        ports:
          - "80:80"
        volumes:
          - ./sites:/opt/drupal/web/sites
          - ./php.ini:/usr/local/etc/php/conf.d/farmos.ini
        environment:
          - DRUPAL_DB_HOST=db
          - DRUPAL_DB_NAME={db_name}
          - DRUPAL_DB_USER={db_user}
          - DRUPAL_DB_PASSWORD={db_pass}
        depends_on:
          - db

      db:
        image: postgres:13
        container_name: farmos_db
        environment:
          - POSTGRES_DB={db_name}
          - POSTGRES_USER={db_user}
          - POSTGRES_PASSWORD={db_pass}
        volumes:
          - db_data:/var/lib/postgresql/data

    volumes:
      db_data:
    """)
    write_file('docker-compose.yml', content)

def generate_php_ini():
    """Generate php.ini with recommended settings."""
    write_file('php.ini', PHP_INI_CONTENT)

def stop_and_remove_containers(compose_command):
    """Stop and remove existing Docker containers."""
    logging.info("Stopping and removing existing Docker containers...")
    run_command(f"{compose_command} down -v")
    
    # Check if there are any containers before attempting to remove them
    result = run_command("docker ps -aq", check=False)
    if result.stdout.strip():
        run_command("docker rm -f $(docker ps -aq)")
    else:
        logging.info("No containers to remove.")
    
    logging.info("Existing Docker containers stopped and removed.")

def start_containers(compose_command):
    """Start Docker containers using Docker Compose."""
    logging.info("Starting Docker containers...")
    run_command(f"{compose_command} up -d")
    logging.info("Docker containers started successfully.")

def check_docker_permissions():
    """Check if the current user can run Docker commands."""
    try:
        run_command("docker info", check=True)
        return True
    except subprocess.CalledProcessError:
        return False

def add_user_to_docker_group():
    """Provide instructions to add the current user to the docker group."""
    username = os.getenv('USER')
    logging.error("Current user does not have permissions to run Docker commands.")
    print("\nTo fix this, please follow these steps:")
    print(f"1. Run the following command to add your user to the docker group:")
    print(f"   sudo usermod -aG docker {username}")
    print("2. Log out of your current session.")
    print("3. Log back in for the changes to take effect.")
    print("4. Run this script again.")
    print("\nAlternatively, you can run this script with sudo:")
    print("   sudo python3 FarmWorks_Install.py")
    sys.exit(1)

def download_default_settings():
    """Download default.settings.php if it does not exist."""
    default_settings = 'sites/default/default.settings.php'
    url = 'https://raw.githubusercontent.com/drupal/drupal/main/sites/default/default.settings.php'
    
    if not os.path.exists(default_settings):
        logging.info("Downloading default.settings.php from Drupal repository...")
        urllib.request.urlretrieve(url, default_settings)
        logging.info("Successfully downloaded default.settings.php.")
    else:
        logging.info("default.settings.php already exists.")

def generate_settings_file():
    """Copy default.settings.php to settings.php."""
    default_settings = 'sites/default/default.settings.php'
    settings = 'sites/default/settings.php'
    
    download_default_settings()
    
    if not os.path.exists(settings):
        run_command(f"cp {default_settings} {settings}")
        logging.info("Copied default.settings.php to settings.php.")
    else:
        logging.info("settings.php already exists.")

def set_permissions():
    """Set appropriate permissions for settings.php and files directory."""
    run_command("chmod 777 sites/default/files")
    logging.info("Set permissions for files directory to 777 (temporarily).")

    settings_path = 'sites/default/settings.php'
    if not os.path.exists(settings_path):
        open(settings_path, 'a').close()
    run_command(f"chmod 666 {settings_path}")
    logging.info("Set permissions for settings.php to 666 (temporarily).")

    logging.warning("Note: These permissions are set for installation purposes. Remember to secure these files after installation.")

def wait_for_postgres(compose_command):
    """Wait for PostgreSQL to be ready."""
    logging.info("Waiting for PostgreSQL to be ready...")
    max_retries = 30
    for i in range(max_retries):
        result = run_command(f"{compose_command} exec db pg_isready -U farmos", check=False)
        if result.returncode == 0:
            logging.info("PostgreSQL is ready.")
            return
        time.sleep(1)
    logging.error("PostgreSQL did not become ready in time.")
    sys.exit(1)

def read_config():
    """Read database information from config.php."""
    config_path = 'FarmWorks/config.php'
    alternate_path = 'config.php'
    
    if not os.path.exists(config_path):
        if os.path.exists(alternate_path):
            config_path = alternate_path
        else:
            logging.error(f"Config file not found. Tried paths: {config_path} and {alternate_path}")
            logging.error("Please ensure config.php is in the correct location.")
            sys.exit(1)
    
    try:
        with open(config_path, 'r') as file:
            content = file.read()
        
        # Extract values using simple string parsing
        db_host = content.split("DB_HOST', '")[1].split("'")[0]
        db_port = content.split("DB_PORT', '")[1].split("'")[0]
        db_name = content.split("DB_NAME', '")[1].split("'")[0]
        db_user = content.split("DB_USER', '")[1].split("'")[0]
        db_pass = content.split("DB_PASSWORD', '")[1].split("'")[0]
        db_prefix = content.split("DB_PREFIX', '")[1].split("'")[0]
        
        logging.info(f"Successfully read configuration from {config_path}")
        
        return {
            'host': db_host,
            'port': db_port,
            'name': db_name,
            'user': db_user,
            'password': db_pass,
            'prefix': db_prefix
        }
    except Exception as e:
        logging.error(f"Error reading {config_path}: {e}")
        logging.error("Please check the contents of your config.php file.")
        sys.exit(1)

def main():
    logging.info("Starting farmOS installation...")
    logging.info(f"Current working directory: {os.getcwd()}")

    if os.geteuid() == 0:
        logging.warning("Running this script as root is not recommended. It's better to add your user to the docker group.")
        proceed = input("Do you want to proceed anyway? (y/n): ").lower()
        if proceed != 'y':
            sys.exit(1)
    
    if not check_command('docker'):
        install_docker()
    
    compose_command = check_docker_compose()
    if not compose_command:
        install_docker_compose()
    
    if not check_docker_permissions():
        add_user_to_docker_group()
    
    stop_and_remove_containers(compose_command)
    
    db_config = read_config()
    
    create_directories()
    generate_settings_file()
    set_permissions()
    generate_docker_compose(db_config['name'], db_config['user'], db_config['password'])
    generate_php_ini()
    
    start_containers(compose_command)
    
    wait_for_postgres(compose_command)
    
    logging.info("\nfarmOS has been set up locally.")
    local_url = "http://localhost:80/core/install.php"
    logging.info(f"Please visit {local_url} in your browser to complete the installation through the web interface.")
    print(f"\nClick here to open farmOS: {local_url}")
    logging.info("Ensure that you configure the private filesystem path as per the installation instructions.")
    
    print("\n--- Database Information for Browser Setup (from config.php) ---")
    print(f"Database type: PostgreSQL")
    print(f"Database name: {db_config['name']}")
    print(f"Database username: {db_config['user']}")
    print(f"Database password: {db_config['password']}")
    print(f"Database host: db")  # Changed from localhost to db
    print(f"Database port: 5432")
    print(f"Table prefix: {db_config['prefix']}")
    print("----------------------------------------------")
    print("Copy and paste this information into the browser setup when prompted.")
    print("\nIMPORTANT: Use 'db' as the database host when setting up in the browser.")

    # Secure permissions after installation
    from FarmWorks_Security import secure_farmOS_permissions
    secure_farmOS_permissions()

    logging.info("Installation complete and permissions secured.")

if __name__ == "__main__":
    main()