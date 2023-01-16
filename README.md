# pdfAddText
batch add text to a pdf template

## Installation
### Debian
        sudo apt update
        sudo apt install wget php-cgi php-cli php-zip unzip poppler-utils php-mbstring php-gd


### HTML/PHP dependencies
  - [Composer](https://getcomposer.org/) 
  - [mPDF](https://mpdf.github.io/)
  - [W3.CSS](https://www.w3schools.com/w3css/w3css_downloads.asp)
  - [datagridXL](https://www.datagridxl.com)
  - [Papa Parse](https://www.papaparse.com)

        wget -O composer-setup.php https://getcomposer.org/installer
        mkdir -p css
        wget -O css/w3.css https://www.w3schools.com/w3css/4/w3.css
        mkdir -p js
        wget -O js/datagridxl2.js https://code.datagridxl.com/datagridxl2.js
        wget -O js/papaparse.min.js https://raw.githubusercontent.com/mholt/PapaParse/master/papaparse.min.js
        php ./composer-setup.php --install-dir=.
        php ./composer.phar require mpdf/mpdf


## Usage
Load the `index.html`.

### Configuration
There is a `config.php` with some options, additionally there is the `data/template.pdf` that contains the pdf taken as template to be written on. 
