# Lego Project

This is an open source app to manage your Lego collection on the Rebrickable.com site. It is assumed that you have an account on Rebrickable and have sets or parts already entered there. 

Project was built and maintained by Scott Crowley.

## Installation

### Prerequisites

* To run this project, you must have PHP 7.2 installed.
* You should setup a host on your web server for your local domain. For this you could also configure Laravel Homestead or Valet.
* You will need an account on Rebrickable.com
* You will also need to sign up for an API key on Rebrickable.com
    * Go to Account > Profile > Settings > API

#### Step 1

Begin by cloning this repository to your machine, and installing all Composer & NPM dependencies.

```bash
git clone git@github.com:scottcrowley/lego.git
cd lego && composer install && npm install
npm run dev
```

#### Step 2

Run the installer command to set up your database connection as well as all your required Rebrickable credentials. Make sure that you have your Rebrickable email, password and API Key. The installer will automatically generate the Rebrickable User Token based on your provided email & password.
```bash
php artisan lego:install
```


#### Step 3

Next, boot up a server and visit your Lego Project app. If using a tool like Laravel Valet, of course the URL will default to http://lego.test. 

#### Step 4

Visit: http://lego.test/register to register a new Lego Project user account.

#### Step 5

Import data from Rebrickable. See [Data Population](#data-population) section below for more details.

#### Step 6

Run additional Lego Artisan commands. See the [Run Other Lego Artisan Commands](#run-other-lego-artisan-commands) section below for more details.

## Data Population

There is a massive amount of data available on the Rebrickable site and API calls can become very time comsuming, it was determined to be better to have a majority of the data reside in the local database. There is a place you can download all the content from Rebrickable in CSV format. This data is updated once a month. The only real need to keep the local database current with the Rebrickable data, is in the event where you are adding a lot of new sets. So once the data is populated in the local database, there won't be a need to update it very often.

#### CSV Downloads
The CSV files are available for download on the Rebrickable site at https://rebrickable.com/downloads/. These files are usually updated at the beginning of each month. Below are direct links to each of the files that should be downloaded and imported into the local database.

* [themes.csv](https://cdn.rebrickable.com/media/downloads/themes.csv)
* [colors.csv](https://cdn.rebrickable.com/media/downloads/colors.csv)
* [part_categories.csv](https://cdn.rebrickable.com/media/downloads/part_categories.csv)
* [parts.csv](https://cdn.rebrickable.com/media/downloads/parts.csv)
* [inventories.csv](https://cdn.rebrickable.com/media/downloads/inventories.csv)
* [sets.csv](https://cdn.rebrickable.com/media/downloads/sets.csv)
* [inventory_parts.csv](https://cdn.rebrickable.com/media/downloads/inventory_parts.csv)
* [part_relationships.csv](https://cdn.rebrickable.com/media/downloads/part_relationships.csv)

Some of these files are very large and can contain 100's of thousands of rows. I found it easier to split these larger files, specifically inventory_parts.csv & parts.csv. This can be done via the terminal using the following command.

```bash
split -l 75000 inventory_parts.csv
```
This will create new csv files containing 75000 rows each from the inventory_parts.csv file.

I created a MacOS Automator script to handle this action much more easily. See the [Automator Scripts](#automator-scripts) section below on how I did it.

## Import CSV Files into Local Database
This section will cover using PhpMyAdmin to import the downloaded Rebrickable CSV files into the local database. Each of the download files should have a corresponding table in the database. 

#### Truncate the Tables
If there is already data imported into these tables, they should first be truncated.
* Click on the `lego` database from the sidebar. You should see a listing of all the tables in the database
* Click the `Empty` button for each table you are importing data into

#### Import the data
* Click the table you are importing data into from the sidebar
* Click the Import button at the top of the window
* Click the `Choose File` button to select the CSV file to import
* Leave the checkbox next to `Allow the interruption of an import in case the script detects it is close to the PHP timeout limit`.
* Put a `1` in the box for `Skip this number of queries (for SQL) starting from the first one:`. This is because the CSV files contain column headers that need to be ignored on import.
* Uncheck `Enable foreign key checks`
* Leave `Format` as `SQL`
* Leave `SQL compatibility mode:` as `NONE`
* Leave `Do not use AUTO_INCREMENT for zero values` checked
* Click on `Go`
* Repeat for each table or if you have additional files to import for the current table.

## Run Other Lego Artisan Commands
There are several other Lego setup commands that have been created to help speed up data retrieval. The mostly populate additional tables based on data imported in the main table structure. The commands should be run after every re-import that is performed.

### Commands
* #### Category Part Counts
    * This will update all the part counts associated with every Category found in the `part_categories` database table.

        ```bash
        php artisan lego:category-part-count
        ```
* #### Update Part Image Url
    * This will update the urls associated with every Part found in the `parts` table of the database.

        ```bash
        php artisan lego:part-image-url
        ```
    * ***Options:***
        * `--category` - Allows you to specify only process parts associated with the given `part_category_id`. i.e. `php artisan lego:part-image-url --category=1` to only process parts associated with the *Baseplates* category.
        * `--start` - Allows you to specify which row to start processing on. Can be useful if you only want to process a certain set of parts. i.e. `php artisan lego:part-image-url --start=1000` to start processing parts after row 1000.
        * `--end` - Allows you to specify which row to end processing on. Can be useful if you only want to process a certain set of parts. i.e. `php artisan lego:part-image-url --end=1000` to end processing parts at row 1000.
        * Use both `--start` and `--end` to specify a range of parts to process. i.e. `php artisan lego:part-image-url --start=1000 --end=2000` to process parts from row 1000 to 2000.
* #### Update Set Image Url
    * This will update the urls associated with every Set found in the `sets` table of the database.
    
        ```bash
        php artisan lego:set-image-url
        ```
    * ***Options:***
        * `--theme` - Allows you to specify only process sets associated with the given `theme_id`. i.e. `php artisan lego:set-image-url --theme=4` to only process sets associated with the *Expert Builder* theme.
        * `--start` - Allows you to specify which row to start processing on. Can be useful if you only want to process a certain numnber of sets. i.e. `php artisan lego:set-image-url --start=1000` to start processing sets after row 1000.
        * `--end` - Allows you to specify which row to end processing on. Can be useful if you only want to process a certain number of sets. i.e. `php artisan lego:set-image-url --end=1000` to end processing sets at row 1000.
        * Use both `--start` and `--end` to specify a range of sets to process. i.e. `php artisan lego:set-image-url --start=1000 --end=2000` to process sets from row 1000 to 2000.
* #### Update The Theme Hierarchy
    * This will update the Theme hierarchy for every Theme in the themes table of the database.
    
        ```bash
        php artisan lego:theme-hierarchy
        ```
## Automator Scripts
* #### Setup
    * Create a directory in `~/Downloads` called `Rebrickable-CSVs`
    * Create a directory in `~/Downloads/Rebrickable-CSVs` called `inventory_parts`
    * Create a directory in `~/Downloads/Rebrickable-CSVs` called `parts`

* #### Create Scripts - Inventory Parts
    * Create a new Automater Application and call it `_Split Inventory Parts CSV`
    * Add a Copy Finder Items module to the application
        * In the `To:` pulldown menu, select the directory you created above called `~/Downloads/Rebrickable-CSVs/inventory_parts`
        * Check the box for `Replacing existing files`
    * Add a `Run Shell Script` module
        * From the `Shell:` pulldown, choose the shell you are currently using. i.e. `/bin/zsh`
        * From the `Pass input:` pulldown, choose `to stdin`.
        * In the script window, paste the following:
            ```bash
            cd ~/Downloads/Rebrickable-CSVs/inventory_parts
            rm x*.csv
            split -l 75000 inventory_parts.csv
            rm inventory_parts.csv
            for i in *;
            > do mv "$i" "$i.csv";
            > done
            rm do
            rm done
            ```
    * Save the application to `~/Downloads/Rebrickable-CSVs/_Split Inventory Parts CSV`
* #### Create Scripts - Parts
    * Create a new Automater Application and call it `_Split Parts CSV`
    * Add a Copy Finder Items module to the application
        * In the `To:` pulldown menu, select the directory you created above called `~/Downloads/Rebrickable-CSVs/parts`
        * Check the box for `Replacing existing files`
    * Add a `Run Shell Script` module
        * From the `Shell:` pulldown, choose the shell you are currently using. i.e. `/bin/zsh`
        * From the `Pass input:` pulldown, choose `to stdin`.
        * In the script window, paste the following:
            ```bash
            cd ~/Downloads/Rebrickable-CSVs/parts
            rm x*.csv
            split -l 75000 parts.csv
            rm parts.csv
            for i in *;
            > do mv "$i" "$i.csv";
            > done
            rm do
            rm done
            ```
    * Save the application in `~/Downloads/Rebrickable-CSVs/_Split Parts CSV`
* #### Usage
    * Download the CSV files from Rebrickable to `~/Downloads/Rebrickable-CSVs/`
    * Drag `~/Downloads/Rebrickable-CSVs/inventory_parts.csv` onto `_Split Inventory Parts CSV` which should be in the same directory
    * Drag `~/Downloads/Rebrickable-CSVs/parts.csv` onto `_Split Parts CSV` which should be in the same directory
    * Dragging the CSV files onto the Automator Applications will create several files in the corresponding sub directory for `inventory_parts` and `parts`.
    * These files can be then imported into the local database using standard import procedures. See the [Import CSV Files into Local Database](#import-csv-files-into-local-database) section above for more info.
