import random
from datetime import datetime, timedelta
import logging
import psycopg2
from psycopg2 import sql
from collections import Counter
import time

# Database connection parameters
DB_PARAMS = {
    'dbname': 'FarmWorks_DB',
    'user': 'farmworks_user',
    'password': 'h08934gh!f',
    'host': 'localhost',
    'port': '5432'
}

def get_db_connection(max_retries=5, retry_delay=10):
    """Establish a database connection with retries"""
    for attempt in range(max_retries):
        try:
            conn = psycopg2.connect(**DB_PARAMS, connect_timeout=30)  # Added connect_timeout
            logging.info(f"Successfully connected to database {DB_PARAMS['dbname']} as user {DB_PARAMS['user']}")
            return conn
        except psycopg2.OperationalError as error:
            logging.error(f"Attempt {attempt + 1}/{max_retries}: Error while connecting to PostgreSQL: {error}")
            if attempt < max_retries - 1:
                logging.info(f"Retrying in {retry_delay} seconds...")
                time.sleep(retry_delay)
    
    logging.error(f"Failed to connect to the database after {max_retries} attempts")
    return None

def db_insert(query, params=None):
    """Execute an INSERT query"""
    conn = get_db_connection()
    if conn is None:
        return None  # Return None if connection fails

    try:
        with conn.cursor() as cur:
            if params:
                cur.execute(query, params)
            else:
                cur.execute(query)
            conn.commit()
            if cur.description:  # Check if the query returns a result
                return cur.fetchone()  # Return the first row
            else:
                return cur.rowcount
    except (Exception, psycopg2.Error) as error:
        logging.error(f"Error executing INSERT query: {error}")
        conn.rollback()  # Rollback the transaction in case of error
        return None
    finally:
        if conn:
            conn.close()
            logging.info("Database connection closed")

def db_select(query, params=None):
    """Execute a SELECT query and return results"""
    conn = get_db_connection()
    if conn is None:
        return []

    try:
        with conn.cursor() as cur:
            if params:
                cur.execute(query, params)
            else:
                cur.execute(query)
            results = cur.fetchall()
        logging.info(f"Successfully executed SELECT query: {query[:50]}...")
        return results
    except (Exception, psycopg2.Error) as error:
        logging.error(f"Error executing SELECT query: {error}")
        return []
    finally:
        if conn:
            conn.close()
            logging.info("Database connection closed")

def add_animal_births(config):
    total_inserted = 0
    type_counter = Counter()
    for _ in range(config['count']):
        animal_type = random.choice(config['types'])
        birth_date = datetime.now() - timedelta(days=random.randint(1, config['max_days_ago']))
        mother_id = random.randint(1, config['max_mother_id'])
        query = """
        INSERT INTO animal_births (animal_type, birth_date, mother_id)
        VALUES (%s, %s, %s) RETURNING id
        """
        result = db_insert(query, (animal_type, birth_date, mother_id))
        if result:
            total_inserted += 1
            type_counter[animal_type] += 1
            logging.info(f"Added animal birth record: ID {result[0][0]}, {animal_type}, {birth_date}, mother_id: {mother_id}")
        else:
            logging.warning(f"Failed to add animal birth record: {animal_type}, {birth_date}, mother_id: {mother_id}")
    
    logging.info(f"Added {total_inserted} animal birth records:")
    for animal_type, count in type_counter.items():
        logging.info(f"  - {animal_type}: {count}")
    
    verify_animal_births(type_counter)

def verify_animal_births(type_counter):
    query = "SELECT animal_type, COUNT(*) FROM animal_births GROUP BY animal_type"
    results = db_select(query)
    db_counter = Counter(dict(results))
    
    if db_counter == type_counter:
        logging.info("Animal birth records verified successfully")
    else:
        logging.warning("Discrepancy in animal birth records:")
        for animal_type in set(type_counter.keys()) | set(db_counter.keys()):
            if type_counter[animal_type] != db_counter[animal_type]:
                logging.warning(f"  - {animal_type}: Expected {type_counter[animal_type]}, Found {db_counter[animal_type]}")

def add_animal_deaths(config):
    total_inserted = 0
    type_counter = Counter()
    cause_counter = Counter()
    for _ in range(config['count']):
        animal_type = random.choice(config['types'])
        death_date = datetime.now() - timedelta(days=random.randint(1, config['max_days_ago']))
        cause = random.choice(config['causes'])
        query = """
        INSERT INTO animal_deaths (animal_type, death_date, cause)
        VALUES (%s, %s, %s) RETURNING id
        """
        result = db_insert(query, (animal_type, death_date, cause))
        if result:
            total_inserted += 1
            type_counter[animal_type] += 1
            cause_counter[cause] += 1
            logging.info(f"Added animal death record: ID {result[0][0]}, {animal_type}, {death_date}, cause: {cause}")
        else:
            logging.warning(f"Failed to add animal death record: {animal_type}, {death_date}, cause: {cause}")
    
    logging.info(f"Added {total_inserted} animal death records:")
    for animal_type, count in type_counter.items():
        logging.info(f"  - {animal_type}: {count}")
    logging.info("Causes of death:")
    for cause, count in cause_counter.items():
        logging.info(f"  - {cause}: {count}")
    
    verify_animal_deaths(type_counter, cause_counter)

def verify_animal_deaths(type_counter, cause_counter):
    type_query = "SELECT animal_type, COUNT(*) FROM animal_deaths GROUP BY animal_type"
    cause_query = "SELECT cause, COUNT(*) FROM animal_deaths GROUP BY cause"
    
    type_results = db_select(type_query)
    cause_results = db_select(cause_query)
    
    db_type_counter = Counter(dict(type_results))
    db_cause_counter = Counter(dict(cause_results))
    
    if db_type_counter == type_counter and db_cause_counter == cause_counter:
        logging.info("Animal death records verified successfully")
    else:
        logging.warning("Discrepancy in animal death records:")
        for animal_type in set(type_counter.keys()) | set(db_type_counter.keys()):
            if type_counter[animal_type] != db_type_counter[animal_type]:
                logging.warning(f"  - {animal_type}: Expected {type_counter[animal_type]}, Found {db_type_counter[animal_type]}")
        for cause in set(cause_counter.keys()) | set(db_cause_counter.keys()):
            if cause_counter[cause] != db_cause_counter[cause]:
                logging.warning(f"  - {cause}: Expected {cause_counter[cause]}, Found {db_cause_counter[cause]}")

def add_crop_planting(config):
    total_inserted = 0
    for _ in range(config['count']):
        crop_type = random.choice(config['types'])
        planting_date = datetime.now() - timedelta(days=random.randint(1, config['max_days_ago']))
        field_id = random.randint(1, config['max_field_id'])
        quantity = random.randint(config['min_quantity'], config['max_quantity'])
        query = """
        INSERT INTO crop_planting (crop_type, planting_date, field_id, quantity)
        VALUES (%s, %s, %s, %s) RETURNING id
        """
        result = db_insert(query, (crop_type, planting_date, field_id, quantity))
        if result:
            total_inserted += 1
            logging.info(f"Added crop planting record: ID {result[0][0]}, {crop_type}, {planting_date}, field_id: {field_id}, quantity: {quantity}")
        else:
            logging.warning(f"Failed to add crop planting record: {crop_type}, {planting_date}, field_id: {field_id}, quantity: {quantity}")
    logging.info(f"Added {total_inserted} crop planting records")

def add_weather_data(config):
    total_inserted = 0
    for _ in range(config['days']):
        date = datetime.now() - timedelta(days=_)
        temperature = random.uniform(*config['temp_range'])
        rainfall = random.uniform(*config['rainfall_range'])
        humidity = random.uniform(*config['humidity_range'])
        query = """
        INSERT INTO weather_data (date, temperature, rainfall, humidity)
        VALUES (%s, %s, %s, %s) RETURNING id
        """
        result = db_insert(query, (date, temperature, rainfall, humidity))
        if result:
            total_inserted += 1
            logging.info(f"Added weather data record: ID {result[0][0]}, {date}, temp: {temperature:.1f}, rainfall: {rainfall:.1f}, humidity: {humidity:.1f}")
        else:
            logging.warning(f"Failed to add weather data record: {date}, temp: {temperature:.1f}, rainfall: {rainfall:.1f}, humidity: {humidity:.1f}")
    logging.info(f"Added weather data for {total_inserted} days")

def add_milk_production(config):
    total_inserted = 0
    for _ in range(config['days']):
        date = datetime.now() - timedelta(days=_)
        cow_count = random.randint(*config['cow_count_range'])
        total_milk = random.uniform(*config['milk_range'])
        query = """
        INSERT INTO milk_production (date, cow_count, total_milk)
        VALUES (%s, %s, %s) RETURNING id
        """
        result = db_insert(query, (date, cow_count, total_milk))
        if result:
            total_inserted += 1
            logging.info(f"Added milk production record: ID {result[0][0]}, {date}, cow count: {cow_count}, total milk: {total_milk:.1f}")
        else:
            logging.warning(f"Failed to add milk production record: {date}, cow count: {cow_count}, total milk: {total_milk:.1f}")
    logging.info(f"Added milk production data for {total_inserted} days")

def verify_data_integrity():
    verification_results = {}
    
    tables = ['animal_births', 'animal_deaths', 'crop_planting', 'weather_data', 'milk_production']
    
    for table in tables:
        query = sql.SQL("SELECT COUNT(*) FROM {}").format(sql.Identifier(table))
        result = db_select(query)
        count = result[0][0] if result else 0
        verification_results[table] = count
        logging.info(f"Verified {count} records in {table}")
    
    return verification_results

def log_data_addition(verification_results):
    logging.info(f"Data addition completed at {datetime.now()}")
    for table, count in verification_results.items():
        logging.info(f"{table}: {count} records")
    
    # Example of checking for potential issues
    if verification_results['animal_deaths'] > verification_results['animal_births']:
        logging.warning("More animal deaths than births recorded. Please verify data.")

    # Additional data integrity checks
    check_date_ranges()
    check_numeric_ranges()

def check_date_ranges():
    tables = {
        'animal_births': 'birth_date',
        'animal_deaths': 'death_date',
        'crop_planting': 'planting_date',
        'weather_data': 'date',
        'milk_production': 'date'
    }
    
    for table, date_column in tables.items():
        query = sql.SQL("SELECT MIN({}), MAX({}) FROM {}").format(
            sql.Identifier(date_column),
            sql.Identifier(date_column),
            sql.Identifier(table)
        )
        result = db_select(query)
        if result:
            min_date, max_date = result[0]
            logging.info(f"{table} date range: {min_date} to {max_date}")

def check_numeric_ranges():
    checks = [
        ("SELECT MIN(mother_id), MAX(mother_id) FROM animal_births", "animal_births.mother_id"),
        ("SELECT MIN(quantity), MAX(quantity) FROM crop_planting", "crop_planting.quantity"),
        ("SELECT MIN(temperature), MAX(temperature) FROM weather_data", "weather_data.temperature"),
        ("SELECT MIN(rainfall), MAX(rainfall) FROM weather_data", "weather_data.rainfall"),
        ("SELECT MIN(humidity), MAX(humidity) FROM weather_data", "weather_data.humidity"),
        ("SELECT MIN(cow_count), MAX(cow_count) FROM milk_production", "milk_production.cow_count"),
        ("SELECT MIN(total_milk), MAX(total_milk) FROM milk_production", "milk_production.total_milk")
    ]
    
    for query, field in checks:
        result = db_select(query)
        if result:
            min_val, max_val = result[0]
            logging.info(f"{field} range: {min_val} to {max_val}")

def create_tables():
    conn = get_db_connection()
    if conn is None:
        return False

    try:
        with conn.cursor() as cur:
            cur.execute("""
                CREATE TABLE IF NOT EXISTS animal_births (
                    id SERIAL PRIMARY KEY,
                    animal_type VARCHAR(50),
                    birth_date DATE,
                    mother_id INTEGER
                )
            """)
            cur.execute("""
                CREATE TABLE IF NOT EXISTS animal_deaths (
                    id SERIAL PRIMARY KEY,
                    animal_type VARCHAR(50),
                    death_date DATE,
                    cause VARCHAR(100)
                )
            """)
            cur.execute("""
                CREATE TABLE IF NOT EXISTS crop_planting (
                    id SERIAL PRIMARY KEY,
                    crop_type VARCHAR(50),
                    planting_date DATE,
                    field_id INTEGER,
                    quantity INTEGER
                )
            """)
            cur.execute("""
                CREATE TABLE IF NOT EXISTS weather_data (
                    id SERIAL PRIMARY KEY,
                    date DATE,
                    temperature FLOAT,
                    rainfall FLOAT,
                    humidity FLOAT
                )
            """)
            cur.execute("""
                CREATE TABLE IF NOT EXISTS milk_production (
                    id SERIAL PRIMARY KEY,
                    date DATE,
                    cow_count INTEGER,
                    total_milk FLOAT
                )
            """)
        conn.commit()
        logging.info("Tables created successfully")
        return True
    except (Exception, psycopg2.Error) as error:
        logging.error(f"Error creating tables: {error}")
        conn.rollback()
        return False
    finally:
        if conn:
            conn.close()