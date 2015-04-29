@echo off
:start
del /q twitchbot.log
php -f twitchbot.php >> logs/twitchbot.log
IF %ERRORLEVEL% GTR 0 GOTO start
:end