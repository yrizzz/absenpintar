module.exports = {
  apps: [
    {
      name: "presensiku-reverb",
      script: "artisan",
      args: "reverb:start",
      interpreter: "php",
      cwd: "/www/wwwroot/presensiku.yrizzz.my.id/presensiku",
      autorestart: true,
      watch: false,
      out_file: "./storage/logs/reverb.log",
      error_file: "./storage/logs/reverb-error.log",
    },
    {
      name: "presensiku-queue",
      script: "artisan",
      args: "queue:work --sleep=3 --tries=3 --timeout=90",
      interpreter: "php",
      cwd: "/www/wwwroot/presensiku.yrizzz.my.id/presensiku",
      autorestart: true,
      watch: false,
      out_file: "./storage/logs/queue.log",
      error_file: "./storage/logs/queue-error.log",
    },
    {
      // Runs Laravel's scheduler (e.g. weekly holidays:sync) without a system crontab.
      name: "presensiku-scheduler",
      script: "artisan",
      args: "schedule:work",
      interpreter: "php",
      cwd: "/www/wwwroot/presensiku.yrizzz.my.id/presensiku",
      autorestart: true,
      watch: false,
      out_file: "./storage/logs/scheduler.log",
      error_file: "./storage/logs/scheduler-error.log",
    }
  ]
}
