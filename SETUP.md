# 🏀 NBA Fantasy Bet - Complete Setup Guide

**Project**: NBA Fantasy Bet
**Author**: Mihai Landreev
**Tech**: Laravel 12, Alpine.js, Tailwind CSS

---

## 📋 What You Need

- PHP 8.3+
- Composer
- Node.js & npm
- Laravel Herd (or any PHP server)
- SQLite (default) or MySQL/PostgreSQL

---

## 🚀 Setup From Zero

### Step 1: Clone & Install

```bash
# Navigate to project directory
cd /Users/mihailandreev/boniato

# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

### Step 2: Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Create SQLite database (if not exists)
touch database/database.sqlite
```

### Step 3: Configure Herd

If using Laravel Herd:
1. Open Herd application
2. Add `/Users/mihailandreev/boniato` to your sites
3. Access at: `http://nba-fantasy-bet.test`

Or use artisan serve:
```bash
php artisan serve
# Access at: http://localhost:8000
```

### Step 4: Database Setup

```bash
# Run all migrations (creates 11 tables)
php artisan migrate

# Seed admin user
php artisan db:seed --class=AdminUserSeeder
```

**Default Admin Created:**
- Email: `admin@nba-fantasy-bet.test`
- Password: `password`

### Step 5: Import NBA Data

```bash
# Import player stats from CSV
php artisan import:players nba_stats_cleaned.csv

# Import game schedule from CSV
php artisan import:games nba_calendar_cleaned.csv

# Set team rosters (marks top 10 players per team as active)
php artisan set:rosters
```

### Step 6: Build Assets

```bash
# Compile JavaScript and CSS
npm run build

# Or run development server with hot reload
npm run dev
```

### Step 7: Visit Your App

**Main Site**: `http://nba-fantasy-bet.test` (or `http://localhost:8000`)

**Login as Admin**:
- Go to `/login`
- Email: `admin@nba-fantasy-bet.test`
- Password: `password`
- Access admin panel at `/admin/dashboard`

**Register as User**:
- Go to `/register`
- Create your account
- Start with 1000 virtual points

---

## 🎮 How to Use

### For Users

1. **Browse Contests** → `/contests`
2. **Enter Contest** → Click "Enter" button
3. **Build Lineup** → Select 8 players (PG, SG, SF, PF, C, G, F, UTIL)
4. **Stay Under Salary Cap** → $50,000 total
5. **Submit** → Entry fee deducted from balance
6. **Wait for Results** → Admin simulates contest
7. **Check Leaderboard** → View rankings at `/contests/{id}`
8. **Receive Prizes** → Automatically added to balance

### For Admins

1. **Login** → `admin@nba-fantasy-bet.test` / `password`
2. **Create Contest** → `/admin/contests/create`
   - Choose type: 50/50, GPP, or H2H
   - Set entry fee and max entries
   - Pick contest date (with NBA games)
   - Set lock time
3. **Simulate Contest** → `/admin/dashboard`
   - Click "Simulate" button
   - Results calculated automatically
   - Prizes distributed to winners
4. **View Game Scores** → `/games`

---

## 📊 Database Tables Created

After migration, you'll have:
- `users` - User accounts with points balance
- `players` - NBA player stats and salaries
- `games` - NBA game schedule and scores
- `contests` - Contest details
- `lineups` - User lineup submissions
- `lineup_players` - Players in lineups with stats
- `contest_payouts` - Prize structures
- `transactions` - Point movement history

---

## 🎯 Contest Types

**50/50 Contest**
- Top 50% win
- Each winner gets ~2× entry fee
- Example: 100 entries × $10 = $1,000 pool → Top 50 get $18 each

**GPP (Guaranteed Prize Pool)**
- Top-heavy payouts
- 1st: 20%, 2nd: 10%, 3rd: 7%, etc.
- Example: 100 entries × $10 = $1,000 pool → 1st gets $200

**H2H (Head-to-Head)**
- 2 players only
- Winner takes all (minus 10% rake)
- Example: 2 entries × $10 = $18 to winner

---

## 🏆 Fantasy Scoring (DraftKings Format)

```
Points × 1.0
+ Rebounds × 1.25
+ Assists × 1.5
+ Steals × 2.0
+ Blocks × 2.0
- Turnovers × 0.5
+ Double-Double: +1.5
+ Triple-Double: +3.0
```

---

## 💡 Important Commands

```bash
# Import players
php artisan import:players <csv-file>

# Import games
php artisan import:games <csv-file>

# Set active rosters
php artisan set:rosters

# View team rosters
php artisan generate:rosters

# Create admin user
php artisan db:seed --class=AdminUserSeeder

# Reset database (careful!)
php artisan migrate:fresh
php artisan db:seed --class=AdminUserSeeder

# Clear cache
php artisan config:clear
php artisan route:clear
php artisan cache:clear

# Run tests
php artisan test
```

---

## 📁 Project Structure

```
nba-fantasy-bet/
├── app/
│   ├── Console/Commands/         # Import & roster commands
│   ├── Http/
│   │   ├── Controllers/          # Contest, Lineup, Admin, Simulation
│   │   └── Middleware/           # IsAdmin protection
│   ├── Models/                   # User, Player, Contest, Lineup, Game
│   └── Services/                 # GameSimulator, SalaryCalculator
├── database/
│   ├── migrations/               # 11 migrations
│   └── seeders/                  # AdminUserSeeder, DatabaseSeeder
├── resources/views/
│   ├── admin/                    # Admin dashboard & forms
│   ├── contests/                 # Contest list & leaderboard
│   ├── lineups/                  # Lineup builder (Alpine.js)
│   └── userzone/                 # User dashboard & profile
└── routes/
    └── web.php                   # All routes (secured)
```

---

## 🔧 Troubleshooting

### Can't Access Admin Panel
```bash
# Make yourself admin
php artisan tinker
>>> User::where('email', 'your@email.com')->update(['is_admin' => true]);
```

### Migration Errors
```bash
# Reset database
php artisan migrate:fresh
php artisan db:seed --class=AdminUserSeeder
```

### Permission Errors
```bash
# Fix storage permissions
chmod -R 775 storage bootstrap/cache
```

### "Class not found" Errors
```bash
composer dump-autoload
```

### Frontend Not Loading
```bash
npm run build
```

---

## 📊 CSV Format Requirements

### Players CSV (nba_stats_cleaned.csv)
```csv
Player,Team,Pos,PTS/G_2,TRB,AST,STL,BLK,TOV,MP
LeBron James,LAL,F,25.7,7.3,8.3,1.3,0.5,3.5,35.5
```

**Columns**:
- Player: Full name
- Team: 3-letter abbreviation (LAL, GSW, etc.)
- Pos: Position (G, F, C, G-F, F-C, etc.)
- PTS/G_2: Points per game
- TRB: Total rebounds per game
- AST: Assists per game
- STL: Steals per game
- BLK: Blocks per game
- TOV: Turnovers per game
- MP: Minutes per game

### Games CSV (nba_calendar_cleaned.csv)
```csv
Date,Start (ET),Visitor/Neutral,Home/Neutral,Arena,Notes
Fri Nov 17 2025,7:30p,LAL,GSW,Chase Center,
```

**Columns**:
- Date: Day Month DD YYYY (e.g., "Fri Nov 17 2025")
- Start (ET): Time in format "H:MMp" or "HH:MMp" (e.g., "7:30p")
- Visitor/Neutral: Visiting team abbreviation
- Home/Neutral: Home team abbreviation
- Arena: Arena name (optional)
- Notes: Additional notes (optional)

---

## 🔐 Security Notes

### Before Production

1. **Change Admin Password**
   ```bash
   php artisan tinker
   >>> $admin = User::where('email', 'admin@nba-fantasy-bet.test')->first();
   >>> $admin->password = Hash::make('your-secure-password');
   >>> $admin->save();
   ```

2. **Update .env**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-domain.com
   ```

3. **Setup Database**
   - Use MySQL or PostgreSQL instead of SQLite
   - Configure proper backups

4. **Enable HTTPS**
   - Get SSL certificate
   - Force HTTPS in production

5. **Setup Queue Worker**
   ```bash
   php artisan queue:work --daemon
   ```

---

## ✅ Verification Checklist

After setup, verify:
- [ ] Can access homepage
- [ ] Can register new user
- [ ] Can login as admin
- [ ] Admin panel accessible at `/admin/dashboard`
- [ ] Regular users get 403 on admin routes
- [ ] Players imported (check `/admin/dashboard`)
- [ ] Games imported (check `/games`)
- [ ] Can create contest as admin
- [ ] Can enter contest as user
- [ ] Can build lineup under $50k cap
- [ ] Simulation works
- [ ] Prizes distributed correctly
- [ ] Game scores calculated

---

## 🎯 Quick Start Summary

```bash
# 1. Install
composer install && npm install

# 2. Setup
cp .env.example .env
php artisan key:generate
touch database/database.sqlite

# 3. Database
php artisan migrate
php artisan db:seed --class=AdminUserSeeder

# 4. Import Data
php artisan import:players nba_stats_cleaned.csv
php artisan import:games nba_calendar_cleaned.csv
php artisan set:rosters

# 5. Build
npm run build

# 6. Serve
# Use Herd or: php artisan serve

# 7. Login
# Admin: admin@nba-fantasy-bet.test / password
# URL: http://nba-fantasy-bet.test
```

---

## 🏀 You're Ready!

Your NBA Fantasy Bet platform is now fully set up and ready to use!

**What's Next?**
1. Login as admin
2. Create your first contest
3. Register test users
4. Build lineups
5. Simulate and see results!

**Need Help?**
- Check Laravel 12 documentation
- Review code comments in controllers/services
- Check routes in `routes/web.php`

---

**Built by Mihai Landreev** 🚀
