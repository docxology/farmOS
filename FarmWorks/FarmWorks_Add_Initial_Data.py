import subprocess
import sys
import logging
import time
from datetime import datetime, timedelta
from FarmWorks_Methods import (
    get_db_connection, db_insert, db_select, add_animal_births, add_animal_deaths,
    add_crop_planting, add_weather_data, add_milk_production,
    verify_data_integrity, log_data_addition, create_tables
)

# Set up logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler('farmworks.log'),
        logging.StreamHandler(sys.stdout)
    ]
)

# Configuration block
CONFIG = {
    'animal_births': {
        'count': 50,
        'types': ['cow', 'sheep', 'pig', 'chicken'],
        'max_days_ago': 365,
        'max_mother_id': 100
    },
    'animal_deaths': {
        'count': 30,
        'types': ['cow', 'sheep', 'pig', 'chicken'],
        'causes': ['natural causes', 'disease', 'predator attack', 'accident'],
        'max_days_ago': 180
    },
    'crop_planting': {
        'count': 40,
        'types': ['corn', 'wheat', 'soybeans', 'barley', 'oats'],
        'max_days_ago': 120,
        'max_field_id': 10,
        'min_quantity': 100,
        'max_quantity': 1000
    },
    'weather_data': {
        'days': 365,
        'temp_range': (0, 35),
        'rainfall_range': (0, 50),
        'humidity_range': (30, 90)
    },
    'milk_production': {
        'days': 365,
        'cow_count_range': (50, 100),
        'milk_range': (500, 1500)
    }
}

def run_command(command, max_retries=3, retry_delay=5):
    for attempt in range(max_retries):
        try:
            result = subprocess.run(command, check=True, shell=True, stdout=subprocess.PIPE, stderr=subprocess.PIPE, text=True, cwd='/')
            logging.info(f"Command '{command}' executed successfully")
            return True, result.stdout
        except subprocess.CalledProcessError as e:
            logging.warning(f"Attempt {attempt + 1}/{max_retries} failed for command: {command}")
            logging.warning(f"Error output: {e.stderr}")
            if attempt < max_retries - 1:
                logging.info(f"Retrying in {retry_delay} seconds...")
                time.sleep(retry_delay)
    logging.error(f"Command '{command}' failed after {max_retries} attempts")
    return False, "Max retries reached"

def setup_postgresql():
    logging.info("Setting up PostgreSQL...")
    
    # Update package list and install PostgreSQL
    if not run_command("sudo apt update")[0] or not run_command("sudo apt install -y postgresql postgresql-contrib")[0]:
        return False
    
    # Start and enable PostgreSQL service
    if not run_command("sudo systemctl start postgresql")[0] or not run_command("sudo systemctl enable postgresql")[0]:
        return False
    
    # Create database and user
    db_commands = [
        "sudo -u postgres psql -c \"DROP DATABASE IF EXISTS FarmWorks_DB;\"",
        "sudo -u postgres psql -c \"DROP USER IF EXISTS farmworks_user;\"",
        "sudo -u postgres psql -c \"CREATE USER farmworks_user WITH PASSWORD 'h08934gh!f';\"",
        "sudo -u postgres psql -c \"CREATE DATABASE FarmWorks_DB OWNER farmworks_user;\""
    ]
    
    for command in db_commands:
        if not run_command(command)[0]:
            return False
    
    # Wait for the database to be fully created
    logging.info("Waiting for database to be ready...")
    time.sleep(30)  # Increased wait time to 30 seconds
    
    # Verify database creation
    verify_db_command = "sudo -u postgres psql -lqt | cut -d \| -f 1 | grep -qw FarmWorks_DB"
    success, _ = run_command(verify_db_command)
    if not success:
        logging.error("Failed to verify database creation")
        return False
    
    # Grant privileges
    grant_commands = [
        "sudo -u postgres psql -c \"GRANT ALL PRIVILEGES ON DATABASE FarmWorks_DB TO farmworks_user;\"",
        "sudo -u postgres psql -d FarmWorks_DB -c \"GRANT ALL PRIVILEGES ON SCHEMA public TO farmworks_user;\"",
        "sudo -u postgres psql -d FarmWorks_DB -c \"GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO farmworks_user;\"",
        "sudo -u postgres psql -d FarmWorks_DB -c \"GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO farmworks_user;\""
    ]
    
    for command in grant_commands:
        success, output = run_command(command)
        if not success:
            logging.error(f"Failed to execute command: {command}")
            logging.error(f"Error output: {output}")
            return False
    
    # Verify database connection
    verify_command = "sudo -u postgres psql -d FarmWorks_DB -c \"SELECT 1;\""
    success, output = run_command(verify_command)
    if not success:
        logging.error("Failed to verify database connection")
        logging.error(f"Error output: {output}")
        return False
    
    logging.info("PostgreSQL setup completed successfully")
    return True

def verify_database_connection(max_retries=5, retry_delay=5):
    for attempt in range(max_retries):
        try:
            conn = get_db_connection(dbname='FarmWorks_DB', user='farmworks_user', password='h08934gh!f')
            if conn is None:
                raise Exception("Failed to establish database connection.")
            
            with conn.cursor() as cur:
                cur.execute("SELECT 1")
                result = cur.fetchone()
                if result == (1,):
                    logging.info("Database connection verified successfully.")
                    return True
                else:
                    raise Exception("Unexpected result from database verification query.")
        except Exception as e:
            logging.warning(f"Attempt {attempt + 1}/{max_retries} to verify database connection failed: {str(e)}")
            if attempt < max_retries - 1:
                logging.info(f"Retrying in {retry_delay} seconds...")
                time.sleep(retry_delay)
        finally:
            if conn:
                conn.close()
    
    logging.error("Failed to verify database connection after multiple attempts.")
    return False

def add_initial_data():
    logging.info("Starting initial data addition process")
    if not verify_database_connection():
        return False
    
    if not create_tables():
        logging.error("Failed to create tables. Aborting data addition process.")
        return False
    
    add_animal_births(CONFIG['animal_births'])
    add_animal_deaths(CONFIG['animal_deaths'])
    add_crop_planting(CONFIG['crop_planting'])
    add_weather_data(CONFIG['weather_data'])
    add_milk_production(CONFIG['milk_production'])
    logging.info("Completed initial data addition process")
    return True

def verify_and_log_data():
    logging.info("Starting data verification and logging")
    conn = get_db_connection()
    if conn is None:
        logging.error("Failed to establish database connection. Aborting data verification process.")
        return
    
    conn.close()  # Close the connection as it's not needed for the rest of the process
    
    verification_results = verify_data_integrity()
    log_data_addition(verification_results)
    logging.info("Completed data verification and logging")

if __name__ == "__main__":
    logging.info("FarmWorks Add Initial Data script started")
    
    if setup_postgresql():
        time.sleep(10)  # Wait for 10 seconds after setup
        if verify_database_connection():
            if add_initial_data():
                verify_and_log_data()
                logging.info("FarmWorks Add Initial Data script completed successfully")
                print("\nInitial data addition, verification, and logging completed. Check farmworks.log for details.")
            else:
                logging.error("FarmWorks Add Initial Data script failed")
                print("\nInitial data addition failed. Check farmworks.log for details.")
        else:
            logging.error("Database connection verification failed. Unable to proceed with data addition.")
            print("\nDatabase connection verification failed. Check farmworks.log for details.")
    else:
        logging.error("PostgreSQL setup failed. Unable to proceed with data addition.")
        print("\nPostgreSQL setup failed. Check farmworks.log for details.")
