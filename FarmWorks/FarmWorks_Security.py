import os
import subprocess
import logging

# Set up logging
logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(levelname)s - %(message)s')

def run_command(command):
    """Run a shell command and log the output."""
    logging.info(f"Running command: {command}")
    result = subprocess.run(command, shell=True, stdout=subprocess.PIPE, stderr=subprocess.PIPE, text=True)
    if result.stdout:
        logging.info(result.stdout)
    if result.stderr:
        logging.error(result.stderr)
    return result

def secure_farmOS_permissions():
    """Secure permissions for farmOS after installation."""
    logging.info("Securing farmOS permissions...")

    # Secure sites/default directory
    run_command("chmod 755 sites/default")
    logging.info("Set permissions for sites/default to 755.")

    # Secure settings.php
    settings_path = 'sites/default/settings.php'
    if os.path.exists(settings_path):
        run_command(f"chmod 444 {settings_path}")
        logging.info("Set permissions for settings.php to 444 (read-only).")
    else:
        logging.warning(f"{settings_path} not found. Skipping permission change.")

    # Secure files directory
    files_path = 'sites/default/files'
    if os.path.exists(files_path):
        run_command(f"chmod 755 {files_path}")
        logging.info("Set permissions for sites/default/files to 755.")
        
        # Ensure web server can write to files directory
        run_command(f"chown -R www-data:www-data {files_path}")
        logging.info(f"Changed ownership of {files_path} to www-data.")
    else:
        logging.warning(f"{files_path} not found. Skipping permission change.")

    logging.info("farmOS permissions secured.")

if __name__ == "__main__":
    secure_farmOS_permissions()
