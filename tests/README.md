# NBA Fantasy Sports - Test Suite

This document describes the comprehensive test suite for the NBA Fantasy Sports application.

## Test Structure

### Feature Tests (`tests/Feature/`)
Feature tests verify the application's HTTP routes, controllers, and user interactions.

#### ContestTest.php
Tests for contest functionality:
- Viewing contest lobby
- Viewing contest details
- Entering contests with sufficient balance
- Preventing entry with insufficient balance
- Preventing entry to locked contests
- Enforcing max entries per user
- Viewing contest history
- Viewing contest leaderboard
- Guest access prevention

#### LineupTest.php
Tests for lineup management:
- Viewing lineups index
- Creating lineups
- Validating 8-player requirement
- Enforcing salary cap
- Editing lineups
- Updating lineups
- Deleting lineups
- Authorization checks (users cannot edit/delete other users' lineups)
- Guest access prevention

#### LeaderboardTest.php
Tests for leaderboard features:
- Viewing leaderboards page
- Displaying top winners
- Displaying most active players
- Guest access prevention

#### AdminTest.php
Tests for admin functionality:
- Admin dashboard access
- Regular user access denial
- Creating contests
- Cancelling contests
- Viewing games management
- Simulating individual games
- Simulating all games for a date
- Resetting games
- Roster manager access
- Updating player roster status
- Auto-rebalancing rosters
- Update data page access
- Authorization checks

### Unit Tests (`tests/Unit/`)
Unit tests verify individual model methods and service logic.

#### ContestModelTest.php
Tests for Contest model:
- Relationships (entries, games)
- Lock status checking
- Full status checking
- Prize pool calculation
- Max entries per user checking
- Date formatting

#### LineupModelTest.php
Tests for Lineup model:
- Relationships (user, contest, players)
- Total salary calculation
- Total FPTS calculation
- Unique name constraint

#### GameModelTest.php
Tests for Game model:
- Relationships (player stats)
- Simulation status checking
- Winner determination
- Time formatting
- Game reset functionality

#### GameSimulatorTest.php
Tests for GameSimulator service:
- Game simulation
- Realistic score generation
- Player stats creation
- Multiple game simulation
- Preventing re-simulation of completed games

## Factories (`database/factories/`)

Factories for generating test data:

- **ContestFactory** - Creates contests with various states (open, locked, completed)
- **LineupFactory** - Creates lineups for users and contests
- **PlayerFactory** - Creates players with realistic stats and statuses
- **GameFactory** - Creates games (scheduled or completed)
- **ContestEntryFactory** - Creates contest entries with scores and rankings
- **LineupPlayerFactory** - Creates lineup-player associations
- **GamePlayerStatFactory** - Creates game statistics for players

## Running Tests

### Run all tests:
```bash
php artisan test
```

### Run specific test suite:
```bash
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit
```

### Run specific test file:
```bash
php artisan test tests/Feature/ContestTest.php
php artisan test tests/Unit/GameSimulatorTest.php
```

### Run with coverage:
```bash
php artisan test --coverage
```

### Run specific test method:
```bash
php artisan test --filter test_user_can_enter_contest
```

## Test Database

Tests use an in-memory SQLite database configured in `phpunit.xml`:
- Fast execution
- No database pollution
- Isolated test runs

## Key Testing Principles

1. **Isolation** - Each test is independent and doesn't affect others
2. **RefreshDatabase** - Database is reset between tests
3. **Factories** - Use factories for consistent test data
4. **Assertions** - Clear, meaningful assertions
5. **Coverage** - Tests cover happy paths and edge cases

## Test Coverage

The test suite covers:
-  Authentication & Authorization
-  Contest Management (User & Admin)
-  Lineup Creation & Validation
-  Game Simulation
-  Leaderboards
-  Admin Dashboard
-  Roster Management
-  Points System
-  Access Control

## Future Test Additions

Consider adding tests for:
- Payment processing (if added)
- Email notifications (if added)
- Real-time updates (if added)
- API endpoints (if added)
- Performance tests for large datasets
- Browser tests with Laravel Dusk
