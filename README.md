# bearhug

An auto tweeter to get Tunnelbear credit.

## Installation

Install this on [Heroku](https://dashboard.heroku.com) and set up the [Scheduler](https://scheduler.heroku.com/dashboard) to run the following command `Daily`:

```bash
php bin/bearhug tweet
```

The script will check the date (1st of the month) to avoid running this more than needed.

