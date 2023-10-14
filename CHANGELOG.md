# Changelog


## [4.0.0] - 14.10.2023

- add option to disable avif generation (some servers d not have avif codecs installed) / #15


## [4.0.0alpha1] - 05.08.2023

-  remove params
- now for working correctly the media_manager.php file must be changed which shloud ensure correct function of the caching mechanism
- MIGRATION: 
  Die Datei media_manager.php des media manager Addons muss angepasst werden. 
  Die Anleitung dazu findet sich auf der Setup Seite des Addons.
  Dies ist notwendig um die Cache-Funktionalität korrekt zu gewährleisten. 




## [3.0.0] - 01.08.2023

- Added check to confirm whether file is already cached or not
- MIGRATION: alle Media-Manager Effekte die den Negotiator nutzen müssen einmal bearbeitet werden. 
  Dabei muss das neue Parameterfeld "Name dieses Effekts" auf den Namen des Effekts gesetzt werden, welcher den Negotiator nutzt.
  Das Feld sollte den korrekten Wert bereits als default tragen.



## [2.2.2] - 31.07.2023

- check added if Imagick supports webp or avif as output formats



## [2.2.1] - 28.07.2023

- Settings page shows which functions are available and which output formats are possible



## [2.2.0] - 28.07.2023

- Added setting to force usage of Imagick. F.e. when GD is not supporting avif as expected.



## [2.1.0] - 27.07.2023

- Imagick is now used as fallback when the PHP version is compiled without webp or avif support
- fix issues for setting the cache path correctly



## [2.0.0] - 02.03.2023

- deliver original file when image can not be converted to avif or webp [#1](https://github.com/AndiLeni/media_negotiator/issues/1)



## [1.1.1] - 22.02.2023

- fix issue with php 8.1 where gd is not compiled with avif support / [#3](https://github.com/AndiLeni/media_negotiator/issues/3)



## [1.1.0] - 06.02.2023

- the effect could not be applied to any profile because it was set to a fixed name



## [1.0.0] - 05.02.2023

- initial release
