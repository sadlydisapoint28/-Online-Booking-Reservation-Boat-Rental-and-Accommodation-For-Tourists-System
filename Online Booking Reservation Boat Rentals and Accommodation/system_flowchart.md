# Online Booking Reservation System: Boat Rental Accommodation for Tourists

## Main System Flow
```
⭮ (START)
   ↓
⏢ [Landing Page]
   ↓
◇ {Select User Type?}
   ↙ (Tourist)    ↘ (Admin)
   ↓               ↓
◇ {Have Account?}  ◇ {Valid Credentials?}
   ↙ (No) ↘ (Yes)  ↙ (No) ↘ (Yes)
[Register] [Login] [Try Again] [Admin Dashboard]
     ↘     ↙            ↑
⬚ [Tourist Dashboard]
```

## Tourist Flow
```
⬚ [Tourist Dashboard]
        ↓
◇ {What would you like to do?}
↙ (Book)    ↓ (View)    ↘ (Profile)
↓           ↓            ↓
⬚ Book      ⬚ View       ⬚ Update
Boat        Calendar     Profile
↓           ↓            ↓
◇ {Boats    ◇ {View      ◇ {Save
Available?}  Options?}     Changes?}
↙ (No) ↘ (Yes) ↙ (My) ↘ (All) ↙ (No) ↘ (Yes)
Return   Continue  My    Available Edit   Save
         ↓         Bookings Dates    ↑     ↓
◇ {Date Available?}                  Back to
↙ (No)    ↘ (Yes)                   Dashboard
Choose    Proceed
Another   to Book
```

## Booking Process
```
⬚ [Proceed to Book]
        ↓
⏢ [Enter Details]
        ↓
◇ {Form Complete?}
↙ (No)      ↘ (Yes)
Edit         ↓
Details    ◇ {Confirm Booking?}
↑          ↙ (No)    ↘ (Yes)
|       Return    ⏢ [Submit]
|                   ↓
|                ◇ {Payment Ready?}
|                ↙ (No)    ↘ (Yes)
|             Wait      ⏢ [Process at Office]
|                         ↓
|                      ⏢ [Print Receipt]
|                         ↓
└─────────────────────→ ⭮ (END)
```

## Admin System
```
⬚ [Admin Dashboard]
        ↓
◇ {Select Function?}
↙ (Boats)    ↓ (Users)    ↘ (Reports)
↓            ↓             ↓
◇ {Action?}  ◇ {Action?}   ◇ {Type?}
↙    ↓    ↘  ↙    ↓    ↘   ↙    ↓    ↘
Add  Edit View Add Edit View Daily Weekly Monthly
↓    ↓    ↓   ↓    ↓    ↓    ↓     ↓     ↓
⌺ [Database]  ⌺ [Database]   ⏢ [Generate Report]
        ↘         ↓         ↙
         ⭮ (END) ← ─────────
```

## Symbol Legend:
⭮ = Start/End (Oval)
⬚ = Process (Rectangle)
◇ = Decision (Diamond)
⏢ = Input/Output (Parallelogram)
⌺ = Database (Cylinder)