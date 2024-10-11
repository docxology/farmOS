import unittest
from unittest.mock import patch
import FarmWorks_Install  # Adjust the import based on actual module structure

class TestFarmWorksInstall(unittest.TestCase):

    @patch('FarmWorks_Install.check_command')
    def test_check_command_exists(self, mock_check):
        mock_check.return_value = True
        self.assertTrue(FarmWorks_Install.check_command('docker'))

    @patch('FarmWorks_Install.subprocess.run')
    def test_check_docker_permissions_success(self, mock_run):
        mock_run.return_value.returncode = 0
        self.assertTrue(FarmWorks_Install.check_docker_permissions())

    @patch('FarmWorks_Install.subprocess.run')
    def test_check_docker_permissions_failure(self, mock_run):
        mock_run.side_effect = subprocess.CalledProcessError(1, 'docker')
        self.assertFalse(FarmWorks_Install.check_docker_permissions())

    @patch('FarmWorks_Install.write_file')
    def test_generate_docker_compose(self, mock_write):
        FarmWorks_Install.generate_docker_compose()
        mock_write.assert_called_with('docker-compose.yml', FarmWorks_Install.DOCKER_COMPOSE_CONTENT)

    @patch('FarmWorks_Install.write_file')
    def test_generate_php_ini(self, mock_write):
        FarmWorks_Install.generate_php_ini()
        mock_write.assert_called_with('php.ini', FarmWorks_Install.PHP_INI_CONTENT)

if __name__ == '__main__':
    unittest.main()