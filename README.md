logtailview v0.1

DESCRIPTION
logtailview is a PHP script to display log events stored in a MySQL database by syslog-ng. The user can:
- select how often will be refreshed the web
- filter by program
- filter by severity

REQUERIMENTS
You will need those packages:
- PHP5 (tested with 5.3.2)
- Apache2 (tested with 2.2.14)
- Syslog-ng (tested 2.0.9)
- MySQL5 (tested 5.1.41)
- Logzilla (tested 3.1) just to store the logs the same way

INSTALLATION
Once you install all dependencies
- Just copy the files to a folder in your apache DocumentRoot.

TODO
- Add a filter by time
- Enable change the amount of messages to show
- Don't use Logzilla for log storing
