## Visual Relationship Map

```
                    ┌─────────────┐
                    │    USERS    │
                    │─────────────│
                    │ id          │
                    │ name        │
                    │ email       │
                    │ points_bal  │
                    └──────┬──────┘
                           │
                           │ 1:N (has many)
                           │
            ┌──────────────┴──────────────┐
            │                             │
            ▼                             ▼
    ┌───────────────┐            ┌────────────────┐
    │   LINEUPS     │            │ TRANSACTIONS   │
    │───────────────│            │────────────────│
    │ id            │            │ id             │
    │ user_id   (FK)│            │ user_id    (FK)│
    │ contest_id(FK)│            │ type           │
    │ lineup_name   │            │ amount         │
    │ total_salary  │            │ balance_after  │
    │ total_fpts    │            │ contest_id (FK)│
    │ final_rank    │            └────────────────┘
    │ prize_won     │
    └───────┬───────┘
            │
            │ N:1 (belongs to)
            │
            ▼
    ┌───────────────┐
    │   CONTESTS    │
    │───────────────│
    │ id            │
    │ name          │
    │ contest_type  │
    │ entry_fee     │
    │ max_entries   │
    │ contest_date  │─────┐
    │ lock_time     │     │ (date match, not FK)
    │ status        │     │
    └───────────────┘     │
                          │
                          ▼
                    ┌──────────────┐
                    │    GAMES     │
                    │──────────────│
                    │ id           │
                    │ game_date    │
                    │ visitor_team │
                    │ home_team    │
                    │ status       │
                    │ visitor_score│
                    │ home_score   │
                    └──────┬───────┘
                           │
                           │ 1:N (has many)
                           │
                           ▼
             ┌─────────────────────────┐
             │  GAME_PLAYER_STATS      │
             │─────────────────────────│
             │ id                      │
             │ game_id             (FK)│
             │ player_id           (FK)│───┐
             │ points                  │   │
             │ rebounds                │   │
             │ assists                 │   │
             │ steals                  │   │
             │ blocks                  │   │
             │ turnovers               │   │
             │ fpts                    │   │
             └─────────────────────────┘   │
                                           │ N:1
            ┌──────────────────────────────┘
            │
            ▼
    ┌───────────────┐
    │   PLAYERS     │
    │───────────────│
    │ id            │
    │ name          │
    │ team          │
    │ position      │
    │ salary        │
    │ ppg, rpg, etc │
    │ roster_status │
    │ is_playing    │
    └───────┬───────┘
            │
            │ N:M (many-to-many)
            │
            ▼
    ┌───────────────────┐
    │  LINEUP_PLAYERS   │ ← PIVOT TABLE
    │───────────────────│
    │ id                │
    │ lineup_id     (FK)│───┐
    │ player_id     (FK)│   │
    │ position_slot     │   │
    │ fpts              │   │
    └───────────────────┘   │
                            │ N:1
                            │
                            └──→ Back to LINEUPS 
```

---