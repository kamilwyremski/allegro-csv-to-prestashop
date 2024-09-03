# Allegro CSV to PrestaShop CSV Converter
This PHP script is designed to facilitate the import of products from Allegro to PrestaShop by converting a CSV file exported from Allegro into a format compatible with PrestaShop.

## Features
- CSV File Reading: The script reads a CSV file containing product data exported from Allegro.
- JSON Data Decoding: Decodes JSON data found within the CSV file, particularly extracting image and text information.
- HTML Content Generation: Converts JSON data into HTML content, which is then placed into the appropriate fields within the CSV file, ensuring that product images and descriptions display correctly in PrestaShop.
- CSV File Optimization: Removes unnecessary columns and adjusts key fields such as product category, status, and availability to meet PrestaShop requirements.
- Output: Generates a new prestashop.csv file ready for import into a PrestaShop store.

## Requirements
- PHP 7.0 or higher
- A CSV file exported from Allegro
- PrestaShop installation

## Installation and Usage
1. Download the script files from the repository.
2. Export your product data from Allegro as a CSV file.
3. Rename the exported file to allegro.csv and place it in the script's directory.
4. Run the index.php file via PHP:
```php index.php```
5. The script will generate a file named prestashop.csv in the same directory.
6. Import the prestashop.csv file into your PrestaShop store.

## Troubleshooting
File Not Found: Ensure that the allegro.csv file is in the same directory as the index.php script.
CSV Compatibility Issues: If you encounter any issues with the CSV import, check that the CSV file from Allegro has the correct format.

## License
This project is licensed under the MIT License. See the LICENSE file for details.
