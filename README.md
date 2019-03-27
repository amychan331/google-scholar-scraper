# Google Scholar Scraper

# Introduction
This is a scraper I did for a Stanford researcher who wanted to scraped his own article in Google Scholar and display it in his Drupal site. I was doing site content mangament for the research team he was in and had actually started out with writing the module to be compatible with Drupal 8, as seen in the file [drupal8-DRAFT.php](drupal8-DRAFT.php). I then converted it to Drupal 7 when I realized the site was actually on the older version, with the inital draft in [drupal7-DRAFT.php](drupal7-DRAFT.php). The final module is in the directory [gs_scraper](gs_scraper).

# Usage

>**IMPORTANT NOTICE:** Google Scholar do not have an API and the site code is subject to change at any time. This module was initially created in August 2017, and may no longer be functional. If you decide to use it, it should be tested beforehand and should be monitor regularly. In addition, the module is designed for personal use and occasional scraping. It is not intended and should not be use for commercial purposes or heavy amount of scraping. I posted this for references and my own portfolio record. Use this module at your own risk.

The final version of this module is the directory, [gs_scraper](gs_scraper). Two parameters are needed - the query string and alias. An example query string is Amy+Chan+AND+Stanford. For alias, look inside Drupal dashboard's Configuration -> "URL aliases" for existing alias & alias pattern.
The variables are defined in line 14-15 of gs_scraper.module. The alias in this project is defined as 'google-scholar' by default.