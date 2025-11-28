# NBA Fantasy Bet - Setup Guide

## Requirements

- PHP 8.3+
- Composer
- Node.js & npm
- Laravel Herd or PHP server
- SQLite (or MySQL/PostgreSQL)

## Installation

```bash
cd /Users/mihailandreev/boniato

composer install
npm install

cp .env.example .env
php artisan key:generate
touch database/database.sqlite
```

## Database Setup

```bash
php artisan migrate
php artisan db:seed --class=AdminUserSeeder
```

**Admin Credentials:**
- Email: `admin@nba-fantasy-bet.test`
- Password: `password`

## Import NBA Data

```bash
php artisan import:players nba_player_stats_update.csv
php artisan import:games nba_full_calendar.csv
php artisan set:rosters
```

## Build Assets

```bash
npm run build
```

For development with hot reload:
```bash
npm run dev
```

## Access Application

**Using Laravel Herd:**
- Add `/Users/mihailandreev/boniato` to Herd sites
- Access at: `http://nba-fantasy-bet.test`

**Using artisan serve:**
```bash
php artisan serve
```
- Access at: `http://localhost:8000`

## Login

**Admin:**
- URL: `/login`
- Email: `admin@nba-fantasy-bet.test`
- Password: `password`
- Admin panel: `/admin/dashboard`

**New User:**
- Register at `/register`
- Start with 1000 virtual points

## How It Works

### For Users
1. Browse contests at `/contests`
2. Click "Enter" button
3. Build lineup with 8 players (PG, SG, SF, PF, C, G, F, UTIL)
4. Stay under $50,000 salary cap
5. Submit lineup (entry fee deducted)
6. Wait for admin to simulate contest
7. View results and rankings
8. Prizes added automatically to balance

### For Admins
1. Login with admin account
2. Create contest at `/admin/contests/create`
3. Set contest type, entry fee, date, and lock time
4. Users enter lineups
5. Simulate contest from `/admin/dashboard`
6. Prizes distributed automatically

## Contest Types

**50/50:** Top 50% win approximately 2x entry fee

**GPP (Guaranteed Prize Pool):** Top-heavy payouts (1st: 20%, 2nd: 10%, 3rd: 7%, etc.)

**H2H (Head-to-Head):** 2 players, winner takes 90% of total pool

## Fantasy Scoring

Points × 1.0
Rebounds × 1.25
Assists × 1.5
Steals × 2.0
Blocks × 2.0
Turnovers × -0.5
Double-Double: +1.5
Triple-Double: +3.0

## Database Tables

- `users` - User accounts and points balance
- `players` - NBA player stats and salaries
- `games` - NBA game schedule and scores
- `contests` - Contest details and settings
- `lineups` - User lineup submissions
- `lineup_players` - Players in lineups with performance
- `game_player_stats` - Individual game statistics
- `contest_payouts` - Prize structures by contest type
- `transactions` - Point movement history

## Useful Commands

```bash
php artisan import:players <csv-file>
php artisan import:games <csv-file>
php artisan set:rosters
php artisan generate:rosters

php artisan migrate:fresh
php artisan db:seed --class=AdminUserSeeder

php artisan config:clear
php artisan route:clear
php artisan cache:clear

php artisan test
```

## Troubleshooting

**Make user admin:**
```bash
php artisan tinker
User::where('email', 'your@email.com')->update(['is_admin' => true]);
```

**Reset database:**
```bash
php artisan migrate:fresh
php artisan db:seed --class=AdminUserSeeder
```

**Fix permissions:**
```bash
chmod -R 775 storage bootstrap/cache
```

**Rebuild autoload:**
```bash
composer dump-autoload
```

## CSV Format

**Players CSV:**
```csv
Player,Team,Pos,PTS/G_2,TRB,AST,STL,BLK,TOV,MP
LeBron James,LAL,F,25.7,7.3,8.3,1.3,0.5,3.5,35.5
```

**Games CSV:**
```csv
Date,Start (ET),Visitor/Neutral,Home/Neutral,Arena,Notes
Fri Nov 17 2025,7:30p,LAL,GSW,Chase Center,
```

## Quick Start

```bash
composer install && npm install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan db:seed --class=AdminUserSeeder
php artisan import:players nba_player_stats_update.csv
php artisan import:games nba_full_calendar.csv
php artisan set:rosters
npm run build
```

Access at `http://nba-fantasy-bet.test` or `http://localhost:8000`
Login: `admin@nba-fantasy-bet.test` / `password`
