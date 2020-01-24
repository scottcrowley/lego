# Lego Project

This is an open source app to manage your Lego collection on the Rebrickable.com site. It is assumed that you have an account on Rebrickable and have sets or parts already entered there. 

The main purpose of this app is to allow you a way to specify storage locations for all of your Lego parts. For people that have large collections, this app can be used to keep your Legos organized, so you will always know where any particular piece is located. 

Project was built and maintained by [Scott Crowley](https://github.com/scottcrowley).

## Installation

### Prerequisites

* To run this project, you must have PHP 7.2 installed.
* You should setup a host on your web server for your local domain. For this you could also configure Laravel Homestead or Valet.
* You will need an account on Rebrickable.com
* You will also need to sign up for an API key on Rebrickable.com
    * Go to Account > Profile > Settings > API

#### Step 1

Begin by cloning this repository to your machine, and installing all Composer & NPM dependencies.

```zsh
git clone git@github.com:scottcrowley/lego.git
cd lego && composer install && npm install
npm run dev
```

#### Step 2

Run the installer command to set up your database connection as well as all your required Rebrickable credentials. Make sure that you have your Rebrickable email, password and API Key. The installer will automatically generate the Rebrickable User Token based on your provided email & password.
```zsh
php artisan lego:install
```

#### Step 3

Next, boot up a server and visit your Lego Project app. If using a tool like Laravel Valet, of course the URL will default to http://lego.test. 

#### Step 4

Visit: http://lego.test/register to register a new Lego Project user account.

#### Step 5

Import data from Rebrickable. See the [Data Population](#data-population) section below for more details.

#### Step 6

Run additional Lego Artisan commands. See the [Run Other Lego Artisan Commands](#run-other-lego-artisan-commands) section below for more details.

## Data Population

There is a massive amount of data available on the Rebrickable site and API calls can become very time comsuming, it was determined to be better to have a majority of the data reside in a local database. There is a place you can download all the content from Rebrickable in CSV format, which is updated once a month. The only real need to keep the local database current with the Rebrickable data, is in the event where you are adding a lot of new sets. So once the data is populated in the local database, there won't be a need to update it very often.

It is advisable to import the data using the [Automated CSV Imports](#automated-csv-imports) commands but you can still do it manually if you want. See the [Manual CSV Downloads](#manual-csv-downloads) section below for details.

#### Automated CSV Imports
Several individual Artisan commands have been provided to automatically download the appropriate CSV file from Rebrickable and then import the data to the appropriate table in the database. There is also a single Artisan command that will execute most on the import commands for you in a group, which is much less complicated.
```zsh
php artisan lego:import-all-csv
```
The above command will execute 7 of the 8 CSV commands. A list of all the [commands](#csv-commands) is provided below with an explaination of what they do. The 8th command, `lego:import-inventory-parts-csv`, is extremely memory intensive and should be executed independently from the other commands. This command imports over 800,000 records into the database and can take about a minute and a half to run. See more details about this [command](#import-inventory-parts-command) below, in the list of CSV commands.

#### CSV Commands
Below is a list of all the commands that can be execute to automatically download and import all associated records into the database.

##### Import All CSV Files Command
This command, as explained above, will execute 7 of the 8 CSV import commands all in one go. Along with executing the CSV commands, it will also execute the `lego:category-part-count` and `lego:theme-hierarchy` commands after it has finished with the imports.
```zsh
php artisan lego:import-all-csv
```

##### Import Colors Command
This command downloads the colors.csv.gz file from Rebrickable, then imports all the colors into the `colors` database table.
```zsh
php artisan lego:import-colors-csv
```

##### Import Part Categories Command
This command downloads the part_categories.csv.gz file from Rebrickable, then imports all the categories into the `part_categories` database table. If this command is run on its own, the `lego:category-part-count` command will also be executed after it has finished with the import.
```zsh
php artisan lego:import-part-categories-csv
```

##### Import Themes Command
This command downloads the themes.csv.gz file from Rebrickable, then imports all the themes into the `themes` database table. If this command is run on its own, the `lego:theme-hierarchy` command will also be executed after it has finished with the import.
```zsh
php artisan lego:import-themes-csv
```

##### Import Sets Command
This command downloads the sets.csv.gz file from Rebrickable, then imports all the sets into the `sets` database table.
```zsh
php artisan lego:import-sets-csv
```

##### Import Parts Command
This command downloads the parts.csv.gz file from Rebrickable, then imports all the parts into the `parts` database table. If this command is run on its own, the `lego:category-part-count` command will also be executed after it has finished with the import.
```zsh
php artisan lego:import-parts-csv
```

##### Import Part Relationships Command
This command downloads the part_relationships.csv.gz file from Rebrickable, then imports all the relationships into the `part_relationships` database table.
```zsh
php artisan lego:import-part-relationships-csv
```

##### Import Inventories Command
This command downloads the inventories.csv.gz file from Rebrickable, then imports all the inventories into the `inventories` database table.
```zsh
php artisan lego:import-inventories-csv
```

##### Import Inventory Parts Command
This command downloads the inventory_parts.csv.gz file from Rebrickable, then imports all the inventory parts into the `inventory_parts` database table. This command will take a long time to execute and is EXTREMELY memory intensive, since it is importing over 800,000 records into the database. To avoid any foreign key constraint errors from occurring, it is also advisable that you run this command after you have executed all the other CSV commands.
```zsh
php artisan lego:import-inventories-parts-csv
```

## Run Other Lego Artisan Commands
There are several other Lego setup [commands](#list-of-other-commands) that have been created to help speed up data retrieval. They mostly populate additional tables based on data imported in the main table structure. The commands should be run after every re-import that is performed. Some of these commands are automatically executed if you imported data using any of the automated CSV import commands. See the section [above](#automated-csv-imports) for more details on those commands.

### List of Other Commands

#### Import All Themes From Rebrickable
This command imports all the Themes into the `inventories` database table, using the Rebrickable API. It is important to note that the `themes` table, in the database, is emptied when executing this command.
```zsh
php artisan lego:import-themes
```

#### Import All Sets From Rebrickable
This command imports all the Sets into the `sets` database table, using the Rebrickable API. After the import is completed the `lego:set-image-url` command will be executed. It is important to note that the `sets` and `set_image_urls` tables, in the database, are emptied when executing this command.
```zsh
php artisan lego:import-sets
```

#### Update Set Image Url
This command will update the urls associated with every Set found in the `sets` table of the database. It does it by retrieving a list of all sets through the Rebrickable API and using that data along with what is in the `sets` table to add the urls. It is almost more adventageous to just run the `lego:import-sets` command, which does the same thing as this command but updates the `sets` table as well. Whenever there are any changes to the `sets` table, this command should be run. It is important to note that the `set_image_urls` table, in the database, is emptied when executing this command.
```zsh
php artisan lego:set-image-url
```

#### Update The Theme Hierarchy
This command will update the Theme hierarchy for every Theme in the themes table of the database. The Theme heirarchy is used to get a visual representation of all the parent themes related to a given theme. It is important to note that the `theme_labels` table, in the database, is emptied when executing this command.
```zsh
php artisan lego:theme-hierarchy
```

#### Category Part Counts
This command will update all the part counts associated with every Category found in the `part_categories` database table. It is important to note that the `category_part_count` table, in the database, is emptied when executing this command.
```zsh
php artisan lego:category-part-count
```

#### Update Part Image Url
This command will update the urls associated with every Part found in the `parts` table of the database. Whenever there are any changes to the `parts` table, this command should be run. It is important to note that the `part_image_urls` table, in the database, is emptied when executing this command.
```zsh
php artisan lego:part-image-url
```

#### Import All User Sets From Rebrickable
This command imports all the User Sets into the `user_sets` database table, using the Rebrickable API. It is important to note that the `user_sets` table, in the database, is emptied when executing this command.
```zsh
php artisan lego:import-user-sets
```

#### Import All User Parts From Rebrickable
This command imports all the User Parts into the `user_parts` database table, using the Rebrickable API. It is important to note that the `user_parts` table, in the database, is emptied when executing this command.
```zsh
php artisan lego:import-user-parts
```

## Manual CSV Downloads
The CSV files are available for download on the Rebrickable site at https://rebrickable.com/downloads/. These files are usually updated at the beginning of each month. Below are direct links to each of the files that should be downloaded and imported into the local database.

* [themes.csv.gz](https://cdn.rebrickable.com/media/downloads/themes.csv.gz)
* [colors.csv.gz](https://cdn.rebrickable.com/media/downloads/colors.csv.gz)
* [part_categories.csv.gz](https://cdn.rebrickable.com/media/downloads/part_categories.csv.gz)
* [parts.csv.gz](https://cdn.rebrickable.com/media/downloads/parts.csv.gz)
* [inventories.csv.gz](https://cdn.rebrickable.com/media/downloads/inventories.csv.gz)
* [sets.csv.gz](https://cdn.rebrickable.com/media/downloads/sets.csv.gz)
* [inventory_parts.csv.gz](https://cdn.rebrickable.com/media/downloads/inventory_parts.csv.gz)
* [part_relationships.csv.gz](https://cdn.rebrickable.com/media/downloads/part_relationships.csv.gz)

Some of these files are very large and can contain 100's of thousands of rows. I found it easier to split these larger files, specifically inventory_parts.csv & parts.csv. This can be done via the terminal using the following command.

```zsh
split -l 75000 inventory_parts.csv
```
This will create new csv files containing 75000 rows each from the inventory_parts.csv file.

I created a MacOS Automator script to handle this action much more easily. See the [Automator Scripts](#automator-scripts) section below on how I did it.

When you are ready to actually import the data you have downloaded, see the [Manually Import CSV Files into Local Database](#manually-import-csv-files-into-local-database) section below.

## Manually Import CSV Files into Local Database
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
            ```zsh
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
            ```zsh
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
    * These files can be then imported into the local database using standard import procedures. See the [Manually Import CSV Files into Local Database](#manually-import-csv-files-into-local-database) section above for more info.
