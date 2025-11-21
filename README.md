# 🏀 NBA Fantasy Bet

A complete daily fantasy sports platform for NBA games. Build lineups, compete in contests, and win prizes!


##  Quick Setup

```bash
# 1. Install dependencies
composer install && npm install

# 2. Configure environment
cp .env.example .env
php artisan key:generate
touch database/database.sqlite

# 3. Setup database
php artisan migrate
php artisan db:seed --class=AdminUserSeeder

# 4. Import NBA data
php artisan import:players nba_stats_cleaned.csv
php artisan import:games nba_calendar_cleaned.csv
php artisan set:rosters

# 5. Build assets
npm run build

# 6. Setup scheduler (for auto-simulation)
# Add to crontab: * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
# Or if using Herd: * * * * * cd /path-to-project && ~/.composer/vendor/bin/herd php artisan schedule:run >> /dev/null 2>&1

# 7. Run (use Herd or artisan serve)
php artisan serve
```

**Access**: `http://nba-fantasy-bet.test` or `http://localhost:8000`

**Admin Login**: `admin@nba-fantasy-bet.test` / `password`

---

##  Full Documentation

See [SETUP.md](SETUP.md) for complete step-by-step instructions, troubleshooting, and detailed configuration.

---

##  Features

- **User System**: Registration, login, virtual points balance
- **Contest Types**: 50/50, GPP (Guaranteed Prize Pool), H2H (Head-to-Head)
- **Lineup Builder**: Interactive 8-player lineup builder with salary cap ($50k)
- **Simulation Engine**: DraftKings-style fantasy scoring with bell curve variance
- **Game Scores**: Automatic calculation of NBA game results
- **Prize Distribution**: Automatic payouts based on contest type
- **Admin Panel**: Create contests, import data, simulate results
- **Security**: Admin middleware, CSRF protection, secure authentication

---

##  What's Included

- 11 database migrations (users, players, games, contests, lineups, etc.)
- 8 Eloquent models with full relationships
- 6+ controllers (Contest, Lineup, Admin, Simulation)
- Admin middleware for route protection
- CSV import commands for players and games
- Interactive lineup builder with Alpine.js
- Complete fantasy scoring system
- Prize calculation and distribution
- Transaction audit trail
- Responsive UI with Tailwind CSS

---

##  How It Works

1. **Admin creates contests** for specific NBA game dates
2. **Users build lineups** with 8 players under $50k salary cap
3. **Contest locks** at specified time (with live countdown timer)
4. **Games auto-simulate** at their scheduled start time (or manually via admin panel)
5. **System calculates fantasy points** using DraftKings formula
6. **Prizes distributed automatically** to winners
7. **Game scores calculated** by summing player points per team

### Automatic Game Simulation
Games are automatically simulated at their start time when the Laravel scheduler is running:
- Checks every 5 minutes for games ready to simulate
- Simulates games that have passed their start time
- Manual override available in admin panel

**To enable**: Add the scheduler to crontab (see Quick Setup step 6)

