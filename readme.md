Local Weather for MODx
======================

A MODx snippet to display weather forecasts and information.

This extension uses the World Weather Online weather *free* API that is free to use
both commercially and non-commercially.

**You will need to get and use your own API key from http://www.worldweatheronline.com/register.aspx**

Please read the documentation at http://www.goldcoastmedia.co.uk/tools/modx-local-weather/
for usage, examples, parameters and placeholder information.

*NOTE*: *curl* or *file_get_contents* must be able to read remote files. You
can set which method to use with the ```&method=````` parameter.

Installation
-----------
Install via MODx package manager and change settings via Settings > System Settings.

Documentation
------------
Full detailed documentation available at:
http://www.goldcoastmedia.co.uk/tools/modx-local-weather/

Example Calls
-------------
Get the weather for London

```[[!LocalWeather? &location=`London`]]```


Setting the number of forecast days (1-5)

```[[!LocalWeather? &location=`Madrid` &days=`3`]] ```


Getting the weather for Boston (in the UK)

```[[!LocalWeather? &location=`Boston` &country=`UK`]] ```


Adding a custom CSS file

```[[!LocalWeather? &location=`Munich` &css=`assets/path/file.css`]]```

Gold Coast Media Ltd
